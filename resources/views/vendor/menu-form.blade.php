@extends('layout.master')

@section('title', 'Tambah/Edit Menu')

@section('sidebar')
    <div class="col-md-2 sidebar">
        <div class="px-3 mb-4">
            <h5 class="text-uppercase">Menu Vendor</h5>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="{{ Route::currentRouteName() === 'vendor.dashboard' ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('vendor.menu-list') }}" class="{{ Route::currentRouteName() === 'vendor.menu-list' ? 'active' : '' }}">
            <i class="fas fa-list"></i> Menu
        </a>
        <a href="{{ route('vendor.orders') }}" class="{{ Route::currentRouteName() === 'vendor.orders' ? 'active' : '' }}">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="food-hero p-4 mb-4">
                <span class="food-chip mb-2"><i class="fas fa-pen-to-square"></i> Menu Editor</span>
                <h1 class="mb-0 food-title">{{ isset($menu) ? 'Edit Menu' : 'Tambah Menu Baru' }}</h1>
            </div>

            <div class="card food-card">
                <div class="card-body">
                    <form action="{{ isset($menu) ? route('vendor.update-menu', $menu->idmenu) : route('vendor.store-menu') }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($menu))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="nama_menu" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" 
                                   name="nama_menu" id="nama_menu" 
                                   value="{{ old('nama_menu', $menu->nama_menu ?? '') }}" required>
                            @error('nama_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kategori') is-invalid @enderror" 
                                   name="kategori" id="kategori" 
                                   value="{{ old('kategori', $menu->kategori ?? '') }}" required>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                           name="harga" id="harga" step="0.01" min="0"
                                           value="{{ old('harga', $menu->harga ?? '') }}" required>
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                           name="stok" id="stok" min="0"
                                           value="{{ old('stok', $menu->stok ?? '0') }}" required>
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="detail" class="form-label">Detail Menu</label>
                            <textarea class="form-control @error('detail') is-invalid @enderror" 
                                      name="detail" id="detail" rows="2">{{ old('detail', $menu->detail ?? '') }}</textarea>
                            @error('detail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Lengkap</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      name="deskripsi" id="deskripsi" rows="4">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar Menu</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                                   name="gambar" id="gambar" accept="image/*">
                            @if(isset($menu) && $menu->gambar)
                                <small class="text-muted">Gambar saat ini: {{ $menu->gambar }}</small>
                            @endif
                            @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-food">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('vendor.menu-list') }}" class="btn btn-food-outline">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

