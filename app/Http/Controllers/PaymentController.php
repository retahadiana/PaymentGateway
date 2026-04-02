<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman pembayaran
     */
    public function show(Pesanan $pesanan)
    {
        if (! $this->canAccessCustomerOrder($pesanan)) {
            abort(403);
        }

        $pesanan->load(['vendor', 'detailPesanan.menu']);
        
        return view('customer.payment-gateway', compact('pesanan'));
    }

    /**
     * Process payment - ini adalah simulasi payment gateway
     * Dalam production, integrate dengan payment gateway seperti Midtrans/GoPay
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
            // Simulasi proses pembayaran
            // Dalam production, hubungi API payment gateway di sini
            
            // Update status pembayaran
            $pesanan->update([
                'status_bayar' => 'waiting_confirmation',
                'metode_bayar' => $validated['metode_pembayaran'],
            ]);

            return redirect()->route('customer.payment-status', $pesanan)
                ->with('success', 'Pembayaran sedang diproses. Mohon menunggu konfirmasi dari vendor.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memproses pembayaran: ' . $e->getMessage()]);
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
        $payload = $request->all();

        // Validasi signature dari payment gateway
        // Ini tergantung pada payment gateway yang digunakan

        try {
            // Cari pesanan berdasarkan transaction_id atau order_id
            $orderId = $payload['order_id'] ?? null;
            $pesanan = Pesanan::where('no_pesanan', $orderId)->first();

            if (!$pesanan) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            $transactionStatus = $payload['transaction_status'] ?? null;

            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                // Pembayaran berhasil
                $pesanan->update([
                    'status_bayar' => 'terbayar',
                    'status_pesanan' => 'confirmed',
                ]);

                return response()->json(['success' => true, 'message' => 'Payment confirmed']);
            } elseif ($transactionStatus === 'pending') {
                // Pembayaran pending
                $pesanan->update(['status_bayar' => 'waiting_confirmation']);
                return response()->json(['success' => true, 'message' => 'Payment pending']);
            } elseif ($transactionStatus === 'deny' || $transactionStatus === 'cancel' || $transactionStatus === 'expire') {
                // Pembayaran gagal
                $pesanan->update(['status_bayar' => 'failed']);
                return response()->json(['success' => true, 'message' => 'Payment failed']);
            }

            return response()->json(['success' => false, 'message' => 'Unknown transaction status']);

        } catch (\Exception $e) {
            \Log::error('Payment webhook error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Webhook processing failed'], 500);
        }
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
}
