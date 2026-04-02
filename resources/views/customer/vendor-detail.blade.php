@extends('layout.master')

@section('title', $vendor->nama_vendor)

@section('sidebar')
    <div class="col-md-2 sidebar">
        <div class="px-3 mb-4">
            <h5 class="text-uppercase">Menu</h5>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="{{ Route::currentRouteName() === 'customer.dashboard' ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('customer.my-orders') }}" class="{{ Route::currentRouteName() === 'customer.my-orders' ? 'active' : '' }}">
            <i class="fas fa-list"></i> Pesanan Saya
        </a>
        <a href="{{ route('customer.dashboard') }}" class="{{ Route::currentRouteName() === 'customer.dashboard' ? 'active' : '' }}">
            <i class="fas fa-store"></i> Jelajahi Vendor
        </a>
    </div>
@endsection

@section('content')
    <div class="food-hero p-4 p-lg-5 mb-4">
        <span class="food-chip mb-2"><i class="fas fa-store"></i> Vendor Detail</span>
        <h1 class="mb-2 food-title"><i class="fas fa-store me-2"></i>{{ $vendor->nama_vendor }}</h1>
        @if($vendor->alamat)
            <p class="mb-0 text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $vendor->alamat }}</p>
        @endif
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-food-outline mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('customer.cart') }}" class="btn btn-food mb-3 ms-2">
                <i class="fas fa-shopping-cart"></i> Lihat Keranjang
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card food-card">
                <div class="card-body">
                    <h5 class="food-title">Informasi Vendor</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $vendor->email }}<br>
                            @if($vendor->phone)
                                <strong>Telepon:</strong> {{ $vendor->phone }}<br>
                            @endif
                            <strong>Kota:</strong> {{ $vendor->kota ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Menu Tersedia</h3>
    
    @if($menus->count() > 0)
        <div class="row">
            @foreach($menus as $menu)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 food-card-soft">
                        @php
                            $imagePath = $menu->gambar ?? $menu->path_gambar ?? null;
                            $stock = $menu->stok ?? 99;
                            $imageUrl = $imagePath
                                ? request()->getBaseUrl() . '/storage/' . ltrim($imagePath, '/')
                                : null;
                        @endphp
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $menu->nama_menu }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title food-title">{{ $menu->nama_menu }}</h5>
                            <p class="card-text">
                                <small class="text-muted">{{ $menu->kategori }}</small>
                            </p>
                            @if($menu->detail)
                                <p class="card-text">{{ $menu->detail }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <strong style="color:#ea580c;">Rp {{ number_format($menu->harga, 0, ',', '.') }}</strong>
                                <span class="badge bg-{{ $stock > 0 ? 'success' : 'danger' }}">
                                    Stok: {{ $stock }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            @if($stock > 0)
                                <form action="{{ route('customer.cart.add') }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->idmenu }}">
                                    <input type="number" name="qty" min="1" value="1" class="form-control form-control-sm" style="max-width: 90px;">
                                    <button class="btn btn-food btn-sm w-100" type="submit">
                                        <i class="fas fa-shopping-cart"></i> Tambah
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm w-100" disabled>
                                    <i class="fas fa-ban"></i> Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($menus->hasPages())
            <div class="mt-4">
                {{ $menus->links() }}
            </div>
        @endif
    @else
        <div class="alert border-0" style="background:#fff7ed;color:#9a3412;">
            Vendor ini belum memiliki menu tersedia.
        </div>
    @endif
@endsection

