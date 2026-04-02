<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    /**
     * Dashboard customer - tampilkan vendor dan menu
     */
    public function dashboard()
    {
        $vendorPk = Schema::hasColumn('vendor', 'id_vendor') ? 'id_vendor' : 'idvendor';
        $menuVendorColumn = Schema::hasColumn('menu', 'id_vendor') ? 'id_vendor' : 'idvendor';
        $hasActiveColumn = Schema::hasColumn('menu', 'aktif');

        $vendors = Vendor::query()->get()->map(function ($vendor) use ($vendorPk, $menuVendorColumn, $hasActiveColumn) {
            $vendorId = (int) ($vendor->{$vendorPk} ?? 0);
            $menuQuery = Menu::query()->where($menuVendorColumn, $vendorId);

            if ($hasActiveColumn) {
                $menuQuery->where('aktif', true);
            }

            $vendor->vendor_route_id = $vendorId;
            $vendor->menu_count = $menuQuery->count();

            return $vendor;
        });

        return view('customer.dashboard', compact('vendors'));
    }

    /**
     * Tampilkan detail vendor dan menu
     */
    public function viewVendor(Vendor $vendor)
    {
        $vendorIdColumn = Schema::hasColumn('vendor', 'id_vendor') ? 'id_vendor' : 'idvendor';
        $menuVendorColumn = Schema::hasColumn('menu', 'id_vendor') ? 'id_vendor' : 'idvendor';
        $vendorId = $vendor->{$vendorIdColumn};

        $menuQuery = Menu::query()->where($menuVendorColumn, $vendorId);
        if (Schema::hasColumn('menu', 'aktif')) {
            $menuQuery->where('aktif', true);
        }

        $menus = $menuQuery->paginate(12);
        return view('customer.vendor-detail', compact('vendor', 'menus'));
    }

    public function cart(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $total = collect($cart)->sum(fn ($item) => ((int) $item['harga']) * ((int) $item['jumlah']));

        return view('customer.cart', compact('cart', 'total'));
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'menu_id' => 'required|integer',
            'qty' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($data['menu_id']);
        $menuId = (int) $menu->idmenu;
        $vendorColumn = Schema::hasColumn('menu', 'id_vendor') ? 'id_vendor' : 'idvendor';

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['jumlah'] += (int) $data['qty'];
        } else {
            $cart[$menuId] = [
                'idmenu' => $menuId,
                'nama_menu' => $menu->nama_menu,
                'harga' => (int) $menu->harga,
                'jumlah' => (int) $data['qty'],
                'id_vendor' => (int) ($menu->{$vendorColumn} ?? 0),
            ];
        }

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Menu ditambahkan ke keranjang.');
    }

    public function updateCartItem(Request $request, int $menuId)
    {
        $data = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cart = $request->session()->get('cart', []);
        if (isset($cart[$menuId])) {
            $cart[$menuId]['jumlah'] = (int) $data['qty'];
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', 'Jumlah item diperbarui.');
    }

    public function removeFromCart(Request $request, int $menuId)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$menuId]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clearCart(Request $request)
    {
        $request->session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }

    /**
     * Lihat riwayat pesanan customer
     */
    public function myOrders()
    {
        $sortColumn = Schema::hasColumn('pesanan', 'created_at')
            ? 'created_at'
            : (Schema::hasColumn('pesanan', 'timestamp') ? 'timestamp' : (Schema::hasColumn('pesanan', 'id_pesanan') ? 'id_pesanan' : 'idpesanan'));

        if (Auth::check()) {
            $orders = Auth::user()->pesanan()
                ->with(['vendor', 'detailPesanan.menu.vendor'])
                ->orderByDesc($sortColumn)
                ->paginate(10);
        } else {
            $guestName = session('guest_customer_name');
            $nameColumn = Schema::hasColumn('pesanan', 'nama_customer') ? 'nama_customer' : 'nama';

            $orders = Pesanan::query()
                ->when($guestName, fn ($q) => $q->where($nameColumn, $guestName))
                ->with(['vendor', 'detailPesanan.menu.vendor'])
                ->orderByDesc($sortColumn)
                ->paginate(10);
        }
        
        return view('customer.my-orders', compact('orders'));
    }

    /**
     * Detail pesanan
     */
    public function orderDetail(Pesanan $pesanan)
    {
        if (Auth::check()) {
            if ((int) $pesanan->user_id !== (int) Auth::id()) {
                abort(403);
            }
        } else {
            $guestName = session('guest_customer_name');
            if (! $guestName || ! in_array($guestName, [(string) ($pesanan->nama_customer ?? ''), (string) ($pesanan->nama ?? '')], true)) {
                abort(403);
            }
        }

        $pesanan->load(['vendor', 'detailPesanan.menu.vendor']);
        return view('customer.order-detail', compact('pesanan'));
    }

    /**
     * Buat pesanan baru (cart checkout)
     */
    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty');
        }

        // Validasi cart
        $validated = $request->validate([
            'alamat_pengiriman' => 'required|string|max:500',
            'metode_bayar' => 'required|in:transfer,virtual_account,cash',
            'catatan' => 'nullable|string|max:500',
        ]);

        $guestName = Pesanan::generateGuestUsername();
        session(['guest_customer_name' => $guestName]);

        try {
            $totalHarga = 0;
            $details = [];

            foreach ($cart as $item) {
                $subtotal = ((int) $item['harga']) * ((int) $item['jumlah']);
                $totalHarga += $subtotal;
                $details[] = [
                    'idmenu' => $item['idmenu'],
                    'harga' => (int) $item['harga'],
                    'jumlah' => (int) $item['jumlah'],
                    'subtotal' => $subtotal,
                ];
            }

            DB::beginTransaction();

            $orderData = [];
            $nameColumn = Schema::hasColumn('pesanan', 'nama_customer') ? 'nama_customer' : 'nama';
            $totalColumn = Schema::hasColumn('pesanan', 'total_harga') ? 'total_harga' : 'total';

            $orderData[$nameColumn] = $guestName;
            $orderData[$totalColumn] = $totalHarga;

            if (Schema::hasColumn('pesanan', 'metode_bayar')) {
                $orderData['metode_bayar'] = $validated['metode_bayar'];
            }
            if (Schema::hasColumn('pesanan', 'status_bayar')) {
                $orderData['status_bayar'] = 'pending';
            }
            if (Schema::hasColumn('pesanan', 'order_id')) {
                $orderData['order_id'] = Pesanan::generateNoPesanan();
            }
            if (Schema::hasColumn('pesanan', 'catatan')) {
                $orderData['catatan'] = $validated['catatan'] ?? null;
            }
            if (Schema::hasColumn('pesanan', 'alamat_pengiriman')) {
                $orderData['alamat_pengiriman'] = $validated['alamat_pengiriman'];
            }
            if (Schema::hasColumn('pesanan', 'user_id') && Auth::check()) {
                $orderData['user_id'] = Auth::id();
            }

            $idColumn = Schema::hasColumn('pesanan', 'id_pesanan') ? 'id_pesanan' : 'idpesanan';
            $pesananId = DB::table('pesanan')->insertGetId($orderData, $idColumn);

            foreach ($details as $detail) {
                $detailRow = [
                    'harga' => $detail['harga'],
                    'jumlah' => $detail['jumlah'],
                    'subtotal' => $detail['subtotal'],
                ];

                if (Schema::hasColumn('detail_pesanan', 'idmenu')) {
                    $detailRow['idmenu'] = $detail['idmenu'];
                }
                if (Schema::hasColumn('detail_pesanan', 'id_pesanan')) {
                    $detailRow['id_pesanan'] = $pesananId;
                }
                if (Schema::hasColumn('detail_pesanan', 'idpesanan')) {
                    $detailRow['idpesanan'] = $pesananId;
                }
                if (Schema::hasColumn('detail_pesanan', 'catatan')) {
                    $detailRow['catatan'] = $validated['catatan'] ?? null;
                }

                DB::table('detail_pesanan')->insert($detailRow);
            }

            DB::commit();

            // Clear cart
            $request->session()->forget('cart');

            return redirect()->route('customer.my-orders')
                ->with('success', 'Pesanan berhasil dibuat. Silahkan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat pesanan: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan status pembayaran
     */
    public function paymentStatus(Pesanan $pesanan)
    {
        if (Auth::check()) {
            if ((int) $pesanan->user_id !== (int) Auth::id()) {
                abort(403);
            }
        } else {
            $guestName = session('guest_customer_name');
            if (! $guestName || ! in_array($guestName, [(string) ($pesanan->nama_customer ?? ''), (string) ($pesanan->nama ?? '')], true)) {
                abort(403);
            }
        }

        return view('customer.payment-status', compact('pesanan'));
    }
}
