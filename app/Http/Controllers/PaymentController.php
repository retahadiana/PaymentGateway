<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    /**
     * Checkout Midtrans Snap (compatible dengan skema lama: idpesanan, nama, total, snap_token, status_bayar).
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'total_harga' => 'nullable|numeric|min:1',
            'total' => 'nullable|numeric|min:1',
        ]);

        $grossAmount = (int) round((float) ($validated['total_harga'] ?? $validated['total'] ?? 0));
        if ($grossAmount < 1) {
            return back()->withErrors(['error' => 'Total pembayaran tidak valid.']);
        }

        if (! env('MIDTRANS_SERVER_KEY') || ! env('MIDTRANS_CLIENT_KEY')) {
            return back()->withErrors([
                'error' => 'Konfigurasi Midtrans belum lengkap. Silakan set MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di .env.',
            ]);
        }

        Config::$serverKey = (string) env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $guestName = $this->generateAutoGuestName();
        $orderId = 'KANTIN-' . strtoupper(uniqid());

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $guestName,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $orderKeyColumn = Schema::hasColumn('pesanan', 'idpesanan') ? 'idpesanan' : Pesanan::keyColumn();
            $nameColumn = Schema::hasColumn('pesanan', 'nama') ? 'nama' : Pesanan::customerNameColumn();
            $totalColumn = Schema::hasColumn('pesanan', 'total') ? 'total' : Pesanan::totalColumn();
            $orderIdColumn = Schema::hasColumn('pesanan', 'order_id') ? 'order_id' : Pesanan::noPesananColumn();
            $statusBayarColumn = Schema::hasColumn('pesanan', 'status_bayar') ? 'status_bayar' : null;

            $insert = [
                $nameColumn => $guestName,
                $totalColumn => $grossAmount,
                $orderIdColumn => $orderId,
            ];

            if (Schema::hasColumn('pesanan', 'snap_token')) {
                $insert['snap_token'] = $snapToken;
            }
            if ($statusBayarColumn !== null) {
                $insert[$statusBayarColumn] = Schema::hasColumn('pesanan', 'no_pesanan') ? 'belum_bayar' : 'pending';
            }

            $pesananId = DB::table('pesanan')->insertGetId($insert, $orderKeyColumn);
            $pesanan = Pesanan::query()->where($orderKeyColumn, $pesananId)->first();

            session(['guest_customer_name' => $guestName]);

            return view('customer.checkout', [
                'pesanan' => $pesanan,
                'snapToken' => $snapToken,
                'midtransClientKey' => (string) env('MIDTRANS_CLIENT_KEY'),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membuat transaksi Midtrans: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan halaman pembayaran
     */
    public function show(Pesanan $pesanan)
    {
        if (! $this->canAccessCustomerOrder($pesanan)) {
            abort(403);
        }

        $pesanan->load(['vendor', 'detailPesanan.menu']);

        $metodeBayar = (string) ($pesanan->metode_bayar ?? '');
        $statusBayar = (string) ($pesanan->status_bayar ?? 'belum_bayar');
        $isSnapMethod = in_array($metodeBayar, ['transfer', 'virtual_account'], true);

        if ($isSnapMethod && in_array($statusBayar, ['belum_bayar', 'failed'], true)) {
            try {
                $snapData = $this->createSnapCheckoutData($pesanan);

                return view('customer.checkout', [
                    'pesanan' => $pesanan,
                    'snapToken' => $snapData['snapToken'],
                    'midtransClientKey' => $snapData['midtransClientKey'],
                    'autoOpenSnap' => true,
                ]);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Gagal mempersiapkan pembayaran Midtrans: ' . $e->getMessage()]);
            }
        }
        
        return view('customer.payment-gateway', compact('pesanan'));
    }

    /**
     * Process payment menggunakan Midtrans Snap.
     */
    public function process(Request $request, Pesanan $pesanan)
    {
        if (! $this->canAccessCustomerOrder($pesanan)) {
            abort(403);
        }

        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:transfer_bank,virtual_account,e_wallet,cicilan',
            'nomor_rekening' => 'nullable|string|max:50',
            'nama_akun' => 'nullable|string|max:255',
        ]);

        try {
            $snapData = $this->createSnapCheckoutData($pesanan);

            $pesanan->update([
                'status_bayar' => 'belum_bayar',
                'metode_bayar' => $this->mapPaymentMethod($validated['metode_pembayaran']),
            ]);

            return view('customer.checkout', [
                'pesanan' => $pesanan,
                'snapToken' => $snapData['snapToken'],
                'midtransClientKey' => $snapData['midtransClientKey'],
                'autoOpenSnap' => true,
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memproses pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Sinkronisasi status transaksi dari Midtrans setelah callback di frontend.
     */
    public function syncStatus(Request $request, Pesanan $pesanan)
    {
        if (! $this->canAccessCustomerOrder($pesanan)) {
            abort(403);
        }

        $validated = $request->validate([
            'order_id' => 'nullable|string|max:100',
            'transaction_status' => 'nullable|string|max:50',
        ]);

        if (! env('MIDTRANS_SERVER_KEY')) {
            return response()->json(['success' => false, 'message' => 'Midtrans server key tidak tersedia'], 500);
        }

        Config::$serverKey = (string) env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderColumn = Schema::hasColumn('pesanan', 'order_id') ? 'order_id' : Pesanan::noPesananColumn();
        $orderId = (string) ($validated['order_id'] ?? $pesanan->{$orderColumn} ?? '');

        if ($orderId === '') {
            return response()->json(['success' => false, 'message' => 'order_id tidak ditemukan'], 422);
        }

        try {
            $statusResponse = Transaction::status($orderId);
            $transactionStatus = '';
            if (is_array($statusResponse)) {
                $transactionStatus = (string) ($statusResponse['transaction_status'] ?? '');
            } elseif (is_object($statusResponse)) {
                $transactionStatus = (string) ($statusResponse->transaction_status ?? '');
            }

            if ($transactionStatus === '') {
                $transactionStatus = (string) ($validated['transaction_status'] ?? '');
            }

            if ($transactionStatus === '') {
                return response()->json(['success' => false, 'message' => 'transaction_status kosong'], 422);
            }

            $newStatusBayar = $this->mapWebhookStatus($transactionStatus);
            if ($newStatusBayar === null) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status transaksi tidak memerlukan update',
                    'transaction_status' => $transactionStatus,
                ]);
            }

            $currentStatusBayar = (string) ($pesanan->status_bayar ?? 'belum_bayar');
            if (! $this->shouldApplyPaymentStatusTransition($currentStatusBayar, $newStatusBayar)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status lokal sudah terbaru',
                    'from' => $currentStatusBayar,
                    'to' => $newStatusBayar,
                ]);
            }

            $updatePayload = ['status_bayar' => $newStatusBayar];
            if ($newStatusBayar === 'terbayar' && Schema::hasColumn('pesanan', 'status_pesanan')) {
                $updatePayload['status_pesanan'] = 'confirmed';
            }

            $pesanan->update($updatePayload);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil disinkronkan',
                'from' => $currentStatusBayar,
                'to' => $newStatusBayar,
                'transaction_status' => $transactionStatus,
            ]);
        } catch (\Exception $e) {
            \Log::warning('Midtrans sync status failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi status ke Midtrans',
            ], 500);
        }
    }

    /**
     * Konfirmasi pembayaran (untuk vendor)
     */
    public function confirmPayment(Request $request, Pesanan $pesanan)
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();
        $orderKeyColumn = Pesanan::keyColumn();

        $allowedOrderIds = DB::table('detail_pesanan')
            ->join('menu', 'detail_pesanan.idmenu', '=', 'menu.idmenu')
            ->where('menu.' . $menuVendorColumn, $vendor->{$vendorKeyColumn})
            ->pluck('detail_pesanan.idpesanan')
            ->unique()
            ->values();

        if (! $allowedOrderIds->contains((int) $pesanan->{$orderKeyColumn})) {
            abort(403);
        }

        $validated = $request->validate([
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            $pesanan->update([
                'status_bayar' => 'terbayar',
                'status_pesanan' => 'confirmed',
            ]);

            return redirect()->route('vendor.order-detail', $pesanan)
                ->with('success', 'Pembayaran berhasil dikonfirmasi');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Virtual Account Information (untuk display ke customer)
     */
    public function virtualAccountInfo(Pesanan $pesanan)
    {
        if (! $this->canAccessCustomerOrder($pesanan)) {
            abort(403);
        }

        // Generate virtual account number (simulasi)
        $orderIdColumn = Schema::hasColumn('pesanan', 'id_pesanan') ? 'id_pesanan' : 'idpesanan';
        $orderIdValue = (string) ($pesanan->{$orderIdColumn} ?? '0');
        $vaNumber = substr($orderIdValue . '000', 0, 10);
        
        return view('customer.virtual-account', compact('pesanan', 'vaNumber'));
    }

    /**
     * Payment notification webhook (dari payment gateway)
     * Endpoint ini akan dipanggil oleh payment gateway setelah transaksi
     */
    public function webhook(Request $request)
    {
        $payload = $request->json()->all() ?: $request->all();

        // Validasi signature dari payment gateway
        // Ini tergantung pada payment gateway yang digunakan
        if (! $this->isValidMidtransSignature($payload)) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        try {
            // Cari pesanan berdasarkan transaction_id atau order_id
            $orderId = $payload['order_id'] ?? null;
            $orderColumn = Pesanan::noPesananColumn();
            $pesanan = $orderId
                ? Pesanan::where($orderColumn, $orderId)->first()
                : null;

            if (!$pesanan) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            $transactionStatus = (string) ($payload['transaction_status'] ?? '');
            $newStatusBayar = $this->mapWebhookStatus($transactionStatus);

            if ($newStatusBayar === null) {
                return response()->json(['success' => false, 'message' => 'Unknown transaction status']);
            }

            $currentStatusBayar = (string) ($pesanan->status_bayar ?? 'belum_bayar');
            if (! $this->shouldApplyPaymentStatusTransition($currentStatusBayar, $newStatusBayar)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No status update applied (duplicate or outdated webhook)',
                ]);
            }

            $updatePayload = ['status_bayar' => $newStatusBayar];
            if ($newStatusBayar === 'terbayar') {
                $updatePayload['status_pesanan'] = 'confirmed';
            }

            $pesanan->update($updatePayload);

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated',
                'from' => $currentStatusBayar,
                'to' => $newStatusBayar,
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment webhook error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handler notifikasi Midtrans.
     * Jika settlement => status_bayar menjadi Lunas (atau terbayar pada skema baru).
     */
    public function notificationHandler(Request $request)
    {
        $payload = $request->json()->all() ?: $request->all();

        if (! $this->isValidMidtransSignature($payload)) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');

        if ($orderId === '') {
            return response()->json(['success' => false, 'message' => 'order_id is required'], 422);
        }

        $orderColumn = Schema::hasColumn('pesanan', 'order_id') ? 'order_id' : Pesanan::noPesananColumn();
        $pesanan = Pesanan::query()->where($orderColumn, $orderId)->first();

        if (! $pesanan) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        if ($transactionStatus === 'settlement') {
            $statusValue = Schema::hasColumn('pesanan', 'no_pesanan') ? 'terbayar' : 'Lunas';
            $pesanan->update(['status_bayar' => $statusValue]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Retry pembayaran
     */
    public function retry(Pesanan $pesanan)
    {
        if (! $this->canAccessCustomerOrder($pesanan)) {
            abort(403);
        }

        if ($pesanan->status_bayar !== 'failed' && $pesanan->status_bayar !== 'belum_bayar') {
            return back()->withErrors(['error' => 'Pesanan tidak bisa di-retry']);
        }

        $pesanan->update(['status_bayar' => 'belum_bayar']);

        return redirect()->route('customer.payment', $pesanan)
            ->with('success', 'Silahkan lakukan pembayaran ulang');
    }

    private function canAccessCustomerOrder(Pesanan $pesanan): bool
    {
        if (Auth::check()) {
            if (! Schema::hasColumn('pesanan', 'user_id')) {
                return true;
            }

            return (int) $pesanan->user_id === (int) Auth::id();
        }

        $guestName = session('guest_customer_name');
        if (! $guestName) {
            return false;
        }

        $nameColumn = Schema::hasColumn('pesanan', 'nama_customer') ? 'nama_customer' : 'nama';
        return (string) ($pesanan->{$nameColumn} ?? '') === $guestName;
    }

    private function mapPaymentMethod(string $method): string
    {
        return match ($method) {
            'virtual_account' => 'virtual_account',
            'transfer_bank', 'e_wallet', 'cicilan' => 'transfer',
            default => 'transfer',
        };
    }

    private function isValidMidtransSignature(array $payload): bool
    {
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $serverKey = (string) env('MIDTRANS_SERVER_KEY', '');

        if ($signatureKey === '' || $orderId === '' || $statusCode === '' || $grossAmount === '' || $serverKey === '') {
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expected, $signatureKey);
    }

    private function mapWebhookStatus(string $transactionStatus): ?string
    {
        return match ($transactionStatus) {
            'settlement', 'capture' => 'terbayar',
            'pending' => 'waiting_confirmation',
            'deny', 'cancel', 'expire' => 'failed',
            default => null,
        };
    }

    private function shouldApplyPaymentStatusTransition(string $currentStatus, string $newStatus): bool
    {
        if ($currentStatus === $newStatus) {
            return false;
        }

        return $this->statusRank($newStatus) > $this->statusRank($currentStatus);
    }

    private function statusRank(string $status): int
    {
        return match ($status) {
            'belum_bayar' => 1,
            'waiting_confirmation' => 2,
            'failed', 'terbayar' => 3,
            default => 0,
        };
    }

    private function generateAutoGuestName(): string
    {
        $orderKeyColumn = Schema::hasColumn('pesanan', 'idpesanan') ? 'idpesanan' : Pesanan::keyColumn();
        $lastId = (int) DB::table('pesanan')->max($orderKeyColumn);
        $nextId = $lastId + 1;

        return 'Guest_' . str_pad((string) $nextId, 7, '0', STR_PAD_LEFT);
    }

    private function createSnapCheckoutData(Pesanan $pesanan): array
    {
        if (! env('MIDTRANS_SERVER_KEY') || ! env('MIDTRANS_CLIENT_KEY')) {
            throw new \RuntimeException('Konfigurasi Midtrans belum lengkap di .env.');
        }

        Config::$serverKey = (string) env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderIdColumn = Schema::hasColumn('pesanan', 'order_id') ? 'order_id' : Pesanan::noPesananColumn();
        $orderId = (string) ($pesanan->{$orderIdColumn} ?? 'KANTIN-' . strtoupper(uniqid()));

        if (! $pesanan->{$orderIdColumn}) {
            $pesanan->update([$orderIdColumn => $orderId]);
            $pesanan->refresh();
        }

        $totalColumn = Schema::hasColumn('pesanan', 'total') ? 'total' : Pesanan::totalColumn();
        $grossAmount = (int) round((float) ($pesanan->{$totalColumn} ?? 0));
        if ($grossAmount < 1) {
            throw new \RuntimeException('Total pembayaran tidak valid.');
        }

        $nameColumn = Schema::hasColumn('pesanan', 'nama') ? 'nama' : Pesanan::customerNameColumn();
        $customerName = (string) ($pesanan->{$nameColumn} ?? session('guest_customer_name', 'Guest'));

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $customerName,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        if (Schema::hasColumn('pesanan', 'snap_token')) {
            $pesanan->update(['snap_token' => $snapToken]);
        }

        return [
            'snapToken' => $snapToken,
            'midtransClientKey' => (string) env('MIDTRANS_CLIENT_KEY'),
        ];
    }
}
