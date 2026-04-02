@extends('layout.master')

@section('title', 'Customer Dashboard')

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

@push('page-styles')
<style>
    .customer-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(254, 215, 170, 0.95);
        border-radius: 2rem;
        background: linear-gradient(135deg, rgba(255, 250, 245, 0.98), rgba(255, 238, 213, 0.96));
        box-shadow: 0 24px 60px rgba(234, 88, 12, 0.10);
    }

    .customer-hero::before,
    .customer-hero::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        filter: blur(18px);
        pointer-events: none;
    }

    .customer-hero::before {
        width: 180px;
        height: 180px;
        top: -40px;
        right: -20px;
        background: rgba(251, 146, 60, 0.18);
    }

    .customer-hero::after {
        width: 220px;
        height: 220px;
        left: -50px;
        bottom: -80px;
        background: rgba(251, 113, 133, 0.14);
    }

    .customer-pill {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .45rem .85rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.9);
        color: #9a3412;
        font-size: .8rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .customer-title {
        color: #7c2d12;
        font-weight: 900;
        letter-spacing: -.04em;
    }

    .customer-subtitle {
        color: #64748b;
        max-width: 46rem;
        line-height: 1.8;
    }

    .hero-action {
        border-radius: 1rem;
        padding: .85rem 1.25rem;
        font-weight: 800;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .hero-action:hover {
        transform: translateY(-2px);
    }

    .hero-action-primary {
        background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
        color: #fff;
        box-shadow: 0 10px 20px rgba(234, 88, 12, 0.16);
    }

    .hero-action-secondary {
        background: rgba(255, 255, 255, 0.9);
        color: #7c2d12;
        border: 1px solid rgba(251, 146, 60, 0.22);
    }

    .metric-card {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(254, 215, 170, 0.9);
        border-radius: 1.4rem;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 40px rgba(234, 88, 12, 0.12);
    }

    .metric-label {
        color: #64748b;
        font-size: .88rem;
        font-weight: 700;
    }

    .metric-value {
        color: #1e293b;
        font-size: 1.3rem;
        font-weight: 900;
        letter-spacing: -.03em;
    }

    .section-heading {
        color: #7c2d12;
        font-weight: 900;
        letter-spacing: -.03em;
    }

    .vendor-card {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(254, 215, 170, 0.95);
        border-radius: 1.5rem;
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(255,247,237,0.96));
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .vendor-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 60px rgba(234, 88, 12, 0.14);
    }

    .vendor-accent {
        position: absolute;
        inset: auto -36px -40px auto;
        width: 110px;
        height: 110px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(251, 146, 60, 0.22) 0%, rgba(251, 146, 60, 0) 70%);
        pointer-events: none;
    }

    .vendor-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .35rem .7rem;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c;
        font-size: .76rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .12em;
    }
</style>
@endpush

@section('content')
    @php
        $displayName = auth()->check() ? auth()->user()->name : (session('guest_customer_name') ?? 'Guest');
    @endphp
    <div class="customer-hero p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
            <div class="col-lg-7">
                <span class="customer-pill mb-3"><i class="fas fa-bowl-food"></i> Food & Drink Selection</span>
                <h1 class="customer-title mb-3" style="font-size: clamp(2.2rem, 5vw, 4rem); line-height: 1.02;">
                    Nikmati Sajian
                    <span style="display:block;background: linear-gradient(90deg, #ea580c 0%, #fb923c 45%, #f43f5e 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">yang Hangat dan Segar</span>
                </h1>
                <p class="customer-subtitle mb-4">
                    Selamat datang, {{ $displayName }}. Jelajahi pilihan makanan dan minuman dari vendor terbaik dengan tampilan yang lebih rapi, lebih hidup, dan lebih menggugah selera.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                    <a href="#vendor-list" class="hero-action hero-action-primary text-center">
                        <i class="fas fa-store me-2"></i> Lihat Vendor
                    </a>
                    <a href="{{ route('customer.my-orders') }}" class="hero-action hero-action-secondary text-center">
                        <i class="fas fa-receipt me-2"></i> Pesanan Saya
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="metric-card p-3 h-100">
                            <div class="metric-label mb-1">Vendor tersedia</div>
                            <div class="metric-value">{{ $vendors->count() }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="metric-card p-3 h-100">
                            <div class="metric-label mb-1">Pilihan rasa</div>
                            <div class="metric-value">Food & Drink</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="metric-card p-3 h-100">
                            <div class="metric-label mb-1">Nuansa</div>
                            <div class="metric-value">Modern</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="metric-card p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <div class="vendor-badge mb-2"><i class="fas fa-mug-hot"></i> Highlight Hari Ini</div>
                            <h2 class="section-heading mb-0" style="font-size: clamp(1.5rem, 3vw, 2.2rem);">Pilih, lihat, lalu nikmati</h2>
                        </div>
                        <span class="badge rounded-pill text-bg-success px-3 py-2">Fresh</span>
                    </div>
                    <div class="d-grid gap-3">
                        <div class="rounded-4 p-3" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold" style="color:#0f172a;">Aroma kopi</div>
                                    <small class="text-muted">Hangat, lembut, dan menenangkan</small>
                                </div>
                                <i class="fas fa-coffee text-orange-500 fs-3"></i>
                            </div>
                        </div>
                        <div class="rounded-4 p-3" style="background: linear-gradient(135deg, #fdf2f8 0%, #ffe4e6 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold" style="color:#0f172a;">Minuman dingin</div>
                                    <small class="text-muted">Segar, ringan, dan cerah</small>
                                </div>
                                <i class="fas fa-glass-water text-pink-500 fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="metric-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:linear-gradient(135deg,#ea580c,#fb923c);color:#fff;">
                        <i class="fas fa-utensils fs-5"></i>
                    </div>
                    <div>
                        <div class="metric-label">Pilihan menu</div>
                        <div class="metric-value">Lengkap</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:linear-gradient(135deg,#f43f5e,#fb7185);color:#fff;">
                        <i class="fas fa-sparkles fs-5"></i>
                    </div>
                    <div>
                        <div class="metric-label">Tampilan</div>
                        <div class="metric-value">Aesthetic</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:linear-gradient(135deg,#14b8a6,#22c55e);color:#fff;">
                        <i class="fas fa-bolt fs-5"></i>
                    </div>
                    <div>
                        <div class="metric-label">Kesan</div>
                        <div class="metric-value">Modern</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="food-card p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <div class="vendor-badge mb-3"><i class="fas fa-bowl-food"></i> Pilihan Hari Ini</div>
                        <h3 class="section-heading mb-3" style="font-size: clamp(1.5rem, 3vw, 2.1rem);">Ragam rasa yang disusun agar mudah dipilih dan enak dilihat</h3>
                        <p class="mb-0 text-muted" style="line-height: 1.8;">
                            Setiap vendor ditampilkan dengan komposisi visual yang lebih premium: kartu yang bersih, informasi singkat yang jelas, dan aksen warna yang tetap hangat.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="#vendor-list" class="hero-action hero-action-primary d-inline-flex align-items-center">
                            <i class="fas fa-arrow-down me-2"></i> Scroll Vendor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-end justify-content-between gap-3 mb-3" id="vendor-list">
        <div>
            <h3 class="section-heading mb-1">Daftar Vendor</h3>
            <p class="text-muted mb-0">Temukan tempat makan dan minum yang sesuai dengan selera Anda.</p>
        </div>
        <span class="vendor-badge"><i class="fas fa-store"></i> {{ $vendors->count() }} vendor</span>
    </div>

    <div class="row g-4">
        @forelse($vendors as $vendor)
            <div class="col-md-4 mb-4">
                <div class="vendor-card h-100">
                    <div class="vendor-accent"></div>
                    <div class="card-body p-4 p-lg-4">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bolder shadow-sm" style="width:56px;height:56px;background:linear-gradient(135deg,#ea580c,#fb923c);">
                                    {{ strtoupper(mb_substr($vendor->nama_vendor ?? 'V', 0, 1)) }}
                                </div>
                                <div>
                                    <h5 class="mb-1 food-title">{{ $vendor->nama_vendor }}</h5>
                                    <div class="vendor-badge"><i class="fas fa-signal"></i> Ready</div>
                                </div>
                            </div>
                            <span class="badge rounded-pill text-bg-light border" style="color:#c2410c;">{{ $vendor->menu_count }} menu</span>
                        </div>

                        <div class="d-grid gap-2 mb-4">
                            <div class="rounded-4 p-3" style="background:#fff7ed;">
                                <small class="text-muted d-block mb-1"><i class="fas fa-phone me-1"></i> Kontak</small>
                                <div class="fw-semibold" style="color:#1e293b;">{{ $vendor->phone ?? 'Tidak tersedia' }}</div>
                            </div>
                            <div class="rounded-4 p-3" style="background:#fff7ed;">
                                <small class="text-muted d-block mb-1"><i class="fas fa-map-marker-alt me-1"></i> Lokasi</small>
                                <div class="fw-semibold" style="color:#1e293b;">{{ $vendor->alamat ?? 'Tidak tersedia' }}</div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="vendor-badge">Makanan</span>
                            <span class="vendor-badge">Minuman</span>
                            <span class="vendor-badge">Fresh</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <a href="{{ route('customer.vendor-detail', $vendor->vendor_route_id) }}" class="btn btn-food w-100">
                            <i class="fas fa-eye me-2"></i> Buka Vendor
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert border-0 rounded-4 p-4" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); color:#9a3412;">
                    Belum ada vendor yang tersedia saat ini.
                </div>
            </div>
        @endforelse
    </div>
@endsection

