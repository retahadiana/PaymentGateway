<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorController extends Controller
{
    /**
     * Dashboard vendor - tampilkan pesanan dan menu
     */
    public function dashboard()
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();
        $orderKeyColumn = Pesanan::keyColumn();
        $timestampColumn = Pesanan::timestampColumn();
        $totalColumn = Pesanan::totalColumn();
        $statusBayarColumn = Pesanan::statusBayarColumn();

        $orderIds = DB::table('detail_pesanan')
            ->join('menu', 'detail_pesanan.idmenu', '=', 'menu.idmenu')
            ->where('menu.' . $menuVendorColumn, $vendor->{$vendorKeyColumn})
            ->pluck('detail_pesanan.idpesanan')
            ->unique()
            ->values();
        
        $stats = [
            'total_pesanan' => Pesanan::whereIn($orderKeyColumn, $orderIds)->count(),
            'pesanan_pending' => Pesanan::whereIn($orderKeyColumn, $orderIds)
                ->where($statusBayarColumn, 'belum_bayar')->count(),
            'pendapatan_hari_ini' => Pesanan::whereIn($orderKeyColumn, $orderIds)
                ->where($statusBayarColumn, 'terbayar')
                ->whereDate($timestampColumn, today())
                ->sum($totalColumn),
        ];

        $recentOrders = Pesanan::whereIn($orderKeyColumn, $orderIds)
            ->with('detailPesanan.menu')
            ->orderByDesc($timestampColumn)
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact('vendor', 'stats', 'recentOrders'));
    }

    /**
     * Kelola menu - list
     */
    public function menuList()
    {
        $vendor = Auth::user()->vendor;
        $menus = $vendor->menus()->paginate(15);

        $menuSchema = [
            'hasKategori' => Schema::hasColumn('menu', 'kategori'),
            'hasStok' => Schema::hasColumn('menu', 'stok'),
            'hasAktif' => Schema::hasColumn('menu', 'aktif'),
            'hasCreatedAt' => Schema::hasColumn('menu', 'created_at'),
            'hasTimestamp' => Schema::hasColumn('menu', 'timestamp'),
        ];

        return view('vendor.menu-list', compact('menus', 'menuSchema'));
    }

    /**
     * Tambah menu baru
     */
    public function createMenu()
    {
        return view('vendor.menu-form');
    }

    /**
     * Store menu baru
     */
    public function storeMenu(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();

        $validated = $request->validate($this->menuValidationRules());

        // Handle file upload
        $uploadedPath = null;
        if ($request->hasFile('gambar')) {
            $uploadedPath = $request->file('gambar')->store('menus', 'public');
        }

        $payload = $this->buildMenuPayload(
            $validated,
            (int) $vendor->{$vendorKeyColumn},
            $menuVendorColumn,
            $uploadedPath,
            true
        );

        Menu::create($payload);

        return redirect()->route('vendor.menu-list')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    /**
     * Edit menu
     */
    public function editMenu(Menu $menu)
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();
        
        if ((int) $menu->{$menuVendorColumn} !== (int) $vendor->{$vendorKeyColumn}) {
            abort(403);
        }

        return view('vendor.menu-form', compact('menu'));
    }

    /**
     * Update menu
     */
    public function updateMenu(Request $request, Menu $menu)
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();
        
        if ((int) $menu->{$menuVendorColumn} !== (int) $vendor->{$vendorKeyColumn}) {
            abort(403);
        }

        $validated = $request->validate($this->menuValidationRules());

        // Handle file upload
        $uploadedPath = null;
        if ($request->hasFile('gambar')) {
            $uploadedPath = $request->file('gambar')->store('menus', 'public');
        }

        $payload = $this->buildMenuPayload(
            $validated,
            (int) $vendor->{$vendorKeyColumn},
            $menuVendorColumn,
            $uploadedPath,
            false
        );

        $menu->update($payload);

        return redirect()->route('vendor.menu-list')
            ->with('success', 'Menu berhasil diperbarui');
    }

    /**
     * Delete menu
     */
    public function deleteMenu(Menu $menu)
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();
        
        if ((int) $menu->{$menuVendorColumn} !== (int) $vendor->{$vendorKeyColumn}) {
            abort(403);
        }

        $menu->delete();

        return redirect()->route('vendor.menu-list')
            ->with('success', 'Menu berhasil dihapus');
    }

    /**
     * Lihat pesanan vendor
     */
    public function orders(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();
        $orderKeyColumn = Pesanan::keyColumn();
        $timestampColumn = Pesanan::timestampColumn();
        $statusBayarColumn = Pesanan::statusBayarColumn();

        $orderIds = DB::table('detail_pesanan')
            ->join('menu', 'detail_pesanan.idmenu', '=', 'menu.idmenu')
            ->where('menu.' . $menuVendorColumn, $vendor->{$vendorKeyColumn})
            ->pluck('detail_pesanan.idpesanan')
            ->unique()
            ->values();
        
        $query = Pesanan::whereIn($orderKeyColumn, $orderIds)
            ->with('detailPesanan.menu');

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'pending') {
                $query->where($statusBayarColumn, 'belum_bayar');
            } elseif ($request->status === 'confirmed') {
                $query->where($statusBayarColumn, 'terbayar');
            }
        }

        $orders = $query->orderByDesc($timestampColumn)->paginate(15);

        return view('vendor.orders', compact('orders'));
    }

    /**
     * Detail pesanan
     */
    public function orderDetail(Pesanan $pesanan)
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

        $pesanan->load('detailPesanan.menu');
        return view('vendor.order-detail', compact('pesanan'));
    }

    /**
     * Update status pesanan
     */
    public function updateOrderStatus(Request $request, Pesanan $pesanan)
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();
        $orderKeyColumn = Pesanan::keyColumn();
        $statusBayarColumn = Pesanan::statusBayarColumn();

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
            'status_bayar' => 'required|in:belum_bayar,waiting_confirmation,terbayar,failed,pending',
        ]);

        $pesanan->update($validated);

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    /**
     * Laporan penjualan
     */
    public function sales(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $vendorKeyColumn = Vendor::keyColumn();
        $menuVendorColumn = Vendor::menuForeignKeyColumn();
        $orderKeyColumn = Pesanan::keyColumn();
        $timestampColumn = Pesanan::timestampColumn();
        $statusBayarColumn = Pesanan::statusBayarColumn();
        $totalColumn = Pesanan::totalColumn();

        $orderIds = DB::table('detail_pesanan')
            ->join('menu', 'detail_pesanan.idmenu', '=', 'menu.idmenu')
            ->where('menu.' . $menuVendorColumn, $vendor->{$vendorKeyColumn})
            ->pluck('detail_pesanan.idpesanan')
            ->unique()
            ->values();

        $from = $request->query('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->query('to', now()->format('Y-m-d'));

        $fromDateTime = Carbon::parse($from)->startOfDay();
        $toDateTime = Carbon::parse($to)->endOfDay();

        $sales = Pesanan::whereIn($orderKeyColumn, $orderIds)
            ->whereBetween($timestampColumn, [$fromDateTime, $toDateTime])
            ->where($statusBayarColumn, 'terbayar')
            ->with('detailPesanan')
            ->get();

        $total = $sales->sum($totalColumn);
        $count = $sales->count();

        return view('vendor.sales', compact('sales', 'total', 'count', 'from', 'to'));
    }

    private function menuValidationRules(): array
    {
        $rules = [
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ];

        if (Schema::hasColumn('menu', 'kategori')) {
            $rules['kategori'] = 'nullable|string|max:100';
        }

        if (Schema::hasColumn('menu', 'detail')) {
            $rules['detail'] = 'nullable|string|max:500';
        }

        if (Schema::hasColumn('menu', 'deskripsi')) {
            $rules['deskripsi'] = 'nullable|string|max:1000';
        }

        if (Schema::hasColumn('menu', 'stok')) {
            $rules['stok'] = 'nullable|integer|min:0';
        }

        if (Schema::hasColumn('menu', 'aktif')) {
            $rules['aktif'] = 'nullable|boolean';
        }

        if (Schema::hasColumn('menu', 'gambar') || Schema::hasColumn('menu', 'path_gambar')) {
            $rules['gambar'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    private function buildMenuPayload(
        array $validated,
        int $vendorId,
        string $menuVendorColumn,
        ?string $uploadedPath,
        bool $includeVendor
    ): array {
        $payload = [
            'nama_menu' => $validated['nama_menu'],
            'harga' => $validated['harga'],
        ];

        if ($includeVendor) {
            $payload[$menuVendorColumn] = $vendorId;
        }

        if (Schema::hasColumn('menu', 'kategori') && array_key_exists('kategori', $validated)) {
            $payload['kategori'] = $validated['kategori'];
        }

        if (Schema::hasColumn('menu', 'detail') && array_key_exists('detail', $validated)) {
            $payload['detail'] = $validated['detail'];
        }

        if (Schema::hasColumn('menu', 'deskripsi') && array_key_exists('deskripsi', $validated)) {
            $payload['deskripsi'] = $validated['deskripsi'];
        }

        if (Schema::hasColumn('menu', 'stok') && array_key_exists('stok', $validated)) {
            $payload['stok'] = $validated['stok'];
        }

        if (Schema::hasColumn('menu', 'aktif')) {
            if (array_key_exists('aktif', $validated)) {
                $payload['aktif'] = (bool) $validated['aktif'];
            } elseif ($includeVendor) {
                $payload['aktif'] = true;
            }
        }

        if ($uploadedPath) {
            if (Schema::hasColumn('menu', 'gambar')) {
                $payload['gambar'] = $uploadedPath;
            }

            if (Schema::hasColumn('menu', 'path_gambar')) {
                $payload['path_gambar'] = $uploadedPath;
            }
        }

        return $payload;
    }
}
