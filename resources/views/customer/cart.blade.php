@extends('layout.master')

@section('title', 'Keranjang Belanja')

@push('page-styles')
<style>
    .cart-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(254, 215, 170, 0.95);
        border-radius: 2rem;
        background: linear-gradient(135deg, rgba(255, 250, 245, 0.98), rgba(255, 237, 213, 0.96));
        box-shadow: 0 22px 55px rgba(234, 88, 12, 0.08);
    }

    .cart-hero::after {
        content: '';
        position: absolute;
        inset: auto -40px -50px auto;
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(251, 146, 60, 0.22) 0%, rgba(251, 146, 60, 0) 70%);
        pointer-events: none;
    }

    .cart-chip {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .42rem .82rem;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c;
        font-size: .8rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .cart-title {
        color: #7c2d12;
        font-weight: 900;
        letter-spacing: -.04em;
    }

    .cart-subtitle {
        color: #64748b;
        line-height: 1.8;
        max-width: 46rem;
    }

    .cart-summary-card,
    .checkout-card,
    .cart-empty-card {
        border: 1px solid rgba(254, 215, 170, 0.95);
        border-radius: 1.5rem;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06);
    }

    .cart-table thead th {
        background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);
        color: #fff;
        border: 0;
        font-size: .82rem;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .cart-table td,
    .cart-table th {
        border-color: rgba(254, 215, 170, 0.55) !important;
        vertical-align: middle;
    }

    .cart-row:hover {
        background: #fff7ed;
    }

    .cart-action-btn {
        border-radius: .9rem;
        font-weight: 800;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .cart-action-btn:hover {
        transform: translateY(-2px);
    }

    .cart-mini {
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

    .checkout-label {
        color: #64748b;
        font-size: .88rem;
        font-weight: 700;
        margin-bottom: .45rem;
    }

    .checkout-total {
        color: #ea580c;
        font-size: 1.6rem;
        font-weight: 900;
        letter-spacing: -.03em;
    }
</style>
@endpush

@section('content')
<div class="cart-hero p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
        <div class="col-lg-8">
            <span class="cart-chip mb-3"><i class="fas fa-cart-shopping"></i> Shopping Basket</span>
            <h1 class="cart-title mb-3" style="font-size: clamp(2rem, 4vw, 3.4rem); line-height: 1.02;">
                Keranjang Belanja
                <span style="display:block;background: linear-gradient(90deg, #ea580c 0%, #fb923c 45%, #f43f5e 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">Rapi, Hangat, dan Menggoda</span>
            </h1>
            <p class="cart-subtitle mb-0">
                Susun pilihan makanan dan minuman dengan tampilan yang lebih modern, informatif, dan nyaman dipandang sebelum checkout.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-food-outline px-4 py-3 fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
            </a>
        </div>
    </div>
</div>

@if(empty($cart))
    <div class="cart-empty-card">
        <div class="card-body text-center py-5 p-4 p-lg-5">
            <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:#fff7ed;color:#c2410c;">
                <i class="fas fa-cart-shopping fs-3"></i>
            </div>
            <h4 class="cart-title mb-2">Keranjang masih kosong</h4>
            <p class="text-muted mb-4">Tambahkan menu pilihan dari vendor agar bagian checkout terisi.</p>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-food px-4 py-3 fw-bold">Lihat Vendor</a>
        </div>
    </div>
@else
    @php
        $itemCount = collect($cart)->sum('jumlah');
        $itemTypes = count($cart);
    @endphp

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="cart-summary-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:54px;height:54px;background:linear-gradient(135deg,#ea580c,#fb923c);color:#fff;">
                        <i class="fas fa-burger fs-5"></i>
                    </div>
                    <div>
                        <div class="checkout-label mb-0">Jenis menu</div>
                        <div class="checkout-total" style="font-size:1.4rem;">{{ $itemTypes }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cart-summary-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:54px;height:54px;background:linear-gradient(135deg,#14b8a6,#22c55e);color:#fff;">
                        <i class="fas fa-cart-plus fs-5"></i>
                    </div>
                    <div>
                        <div class="checkout-label mb-0">Jumlah item</div>
                        <div class="checkout-total" style="font-size:1.4rem;">{{ $itemCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cart-summary-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:54px;height:54px;background:linear-gradient(135deg,#f43f5e,#fb7185);color:#fff;">
                        <i class="fas fa-wallet fs-5"></i>
                    </div>
                    <div>
                        <div class="checkout-label mb-0">Total belanja</div>
                        <div class="checkout-total" style="font-size:1.4rem;">Rp {{ number_format($total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="cart-summary-card p-0 overflow-hidden mb-4">
                <div class="p-3 p-lg-4 border-bottom" style="background: linear-gradient(180deg, rgba(255,247,237,.9), rgba(255,255,255,.92));">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-2">
                        <div>
                            <span class="cart-mini mb-2"><i class="fas fa-utensils"></i> Selected items</span>
                            <h3 class="cart-title mb-1" style="font-size: clamp(1.3rem, 2.5vw, 1.9rem);">Menu di Keranjang</h3>
                            <p class="text-muted mb-0">Sesuaikan jumlah menu sebelum checkout.</p>
                        </div>
                        <form action="{{ route('customer.cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary cart-action-btn">
                                <i class="fas fa-trash me-1"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 cart-table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                                <tr class="cart-row">
                                    <td>
                                        <div class="fw-bold" style="color:#0f172a;">{{ $item['nama_menu'] }}</div>
                                        <small class="text-muted">Menu pilihan yang siap dinikmati</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold" style="color:#ea580c;">Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('customer.cart.update', $item['idmenu']) }}" method="POST" class="d-flex gap-2 align-items-center flex-wrap">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="qty" min="1" value="{{ $item['jumlah'] }}" class="form-control" style="width: 92px; border-radius: .9rem;">
                                            <button type="submit" class="btn btn-sm btn-food-outline d-inline-flex align-items-center gap-1 px-3 cart-action-btn">
                                                <i class="fas fa-rotate-right"></i>
                                                <span>Update</span>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="fw-bold" style="color:#1e293b;">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('customer.cart.remove', $item['idmenu']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3 cart-action-btn"
                                                    onclick="return confirm('Yakin ingin menghapus item ini dari keranjang?')">
                                                <i class="fas fa-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="checkout-card sticky-top p-4 p-lg-4" style="top: 20px;">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="cart-mini mb-2"><i class="fas fa-receipt"></i> Checkout</span>
                        <h4 class="cart-title mb-0" style="font-size: clamp(1.3rem, 2.5vw, 1.8rem);">Rangkuman Pesanan</h4>
                    </div>
                    <span class="badge rounded-pill text-bg-success px-3 py-2">Ready</span>
                </div>

                <div class="rounded-4 p-3 mb-4" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);">
                    <div class="checkout-label mb-1">Total saat ini</div>
                    <div class="checkout-total">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>

                <form action="{{ route('customer.checkout') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label checkout-label">Alamat Pengiriman</label>
                        <textarea name="alamat_pengiriman" class="form-control" rows="4" required style="border-radius:1rem;" placeholder="Tulis alamat lengkap pengiriman">{{ old('alamat_pengiriman') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label checkout-label">Metode Bayar</label>
                        <select name="metode_bayar" class="form-select" required style="border-radius:1rem;">
                            <option value="transfer">Transfer</option>
                            <option value="virtual_account">Virtual Account</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label checkout-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}" style="border-radius:1rem;" placeholder="Contoh: tanpa pedas, saus dipisah">
                    </div>
                    <button type="submit" class="btn btn-food w-100 py-3 cart-action-btn">
                        <i class="fas fa-bag-shopping me-2"></i> Buat Pesanan
                    </button>
                </form>

            </div>
        </div>
    </div>
@endif
@endsection
