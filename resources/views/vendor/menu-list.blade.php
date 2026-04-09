@extends('layout.master')

@section('title', 'Daftar Menu')

@section('sidebar')
    <div class="col-md-2 sidebar">
        <div class="px-3 mb-4">
            <h5 class="text-uppercase">Menu Vendor</h5>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="{{ Route::currentRouteName() === 'vendor.dashboard' ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('vendor.orders') }}" class="{{ Route::currentRouteName() === 'vendor.orders' ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i> Pesanan
        </a>
        <a href="{{ route('vendor.menu-list') }}" class="{{ Route::currentRouteName() === 'vendor.menu-list' ? 'active' : '' }}">
            <i class="fas fa-list"></i> Menu
        </a>
        <a href="{{ route('vendor.create-menu') }}" class="{{ Route::currentRouteName() === 'vendor.create-menu' ? 'active' : '' }}">
            <i class="fas fa-plus"></i> Tambah Menu
        </a>
        <a href="{{ route('vendor.sales') }}">
            <i class="fas fa-chart-bar"></i> Laporan Penjualan
        </a>
    </div>
@endsection

@section('content')
    <div class="food-hero p-4 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <span class="food-chip mb-2"><i class="fas fa-list"></i> Menu Management</span>
            <h1 class="mb-0 food-title">Daftar Menu</h1>
        </div>
        <a href="{{ route('vendor.create-menu') }}" class="btn btn-food">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-food align-middle">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                    <tr>
                        @php
                            $menuDate = $menu->created_at ?? $menu->timestamp ?? null;
                            $hasKategori = $menuSchema['hasKategori'] ?? false;
                            $hasStok = $menuSchema['hasStok'] ?? false;
                            $hasAktif = $menuSchema['hasAktif'] ?? false;
                            $isActive = $hasAktif ? (bool) ($menu->aktif ?? false) : true;
                        @endphp
                        <td><strong>{{ $menu->nama_menu }}</strong></td>
                        <td>{{ $hasKategori ? ($menu->kategori ?? '-') : '-' }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td>{{ $hasStok ? ($menu->stok ?? 0) : '-' }}</td>
                        <td>
                            <span class="badge" style="background: {{ $isActive ? '#16a34a' : '#9ca3af' }};">
                                {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>{{ $menuDate ? \Illuminate\Support\Carbon::parse($menuDate)->format('d/m/Y') : '-' }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('vendor.edit-menu', $menu->idmenu) }}"
                                   class="btn btn-sm btn-food-outline d-inline-flex align-items-center gap-1 px-3"
                                   aria-label="Edit menu {{ $menu->nama_menu }}">
                                    <i class="fas fa-pen-to-square"></i>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('vendor.delete-menu', $menu->idmenu) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3"
                                            onclick="return confirm('Yakin ingin menghapus menu {{ $menu->nama_menu }}? Tindakan ini tidak bisa dibatalkan.')"
                                            aria-label="Hapus menu {{ $menu->nama_menu }}">
                                        <i class="fas fa-trash"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada menu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($menus->hasPages())
        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    @endif
@endsection

