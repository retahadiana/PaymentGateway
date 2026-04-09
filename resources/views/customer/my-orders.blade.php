@extends('layout.master')

@section('title', 'Pesanan Saya')

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
    .customer-section-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(254, 215, 170, 0.95);
        border-radius: 2rem;
        background: linear-gradient(135deg, rgba(255, 250, 245, 0.98), rgba(255, 237, 213, 0.96));
        box-shadow: 0 22px 55px rgba(234, 88, 12, 0.08);
    }

    .customer-section-hero::after {
        content: '';
        position: absolute;
        inset: auto -40px -50px auto;
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(251, 146, 60, 0.22) 0%, rgba(251, 146, 60, 0) 70%);
        pointer-events: none;
    }

    .section-chip {
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

    .section-title {
        color: #7c2d12;
        font-weight: 900;
        letter-spacing: -.04em;
    }

    .section-subtitle {
        color: #64748b;
        line-height: 1.8;
        max-width: 48rem;
    }

    .order-summary-card {
        border: 1px solid rgba(254, 215, 170, 0.95);
        border-radius: 1.5rem;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .order-summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 42px rgba(234, 88, 12, 0.12);
    }

    .order-kpi {
        color: #0f172a;
        font-size: 1.5rem;
        font-weight: 900;
        letter-spacing: -.03em;
    }

    .order-kpi-label {
        color: #64748b;
        font-size: .88rem;
        font-weight: 700;
    }

    .orders-table-wrap {
        border: 1px solid rgba(254, 215, 170, 0.95);
        border-radius: 1.75rem;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .orders-table thead th {
        background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);
        color: #fff;
        border: 0;
        font-size: .82rem;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .orders-table tbody tr {
        transition: background .2s ease, transform .2s ease;
    }

    .orders-table tbody tr:hover {
        background: #fff7ed;
    }

    .orders-table td,
    .orders-table th {
        border-color: rgba(254, 215, 170, 0.55) !important;
        vertical-align: middle;
    }

    .order-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .35rem .7rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .order-pill-soft {
        background: #fff7ed;
        color: #c2410c;
    }

    .order-action-btn {
        border-radius: .95rem;
        font-weight: 800;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .order-action-btn:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
    @php
        $ordersCollection = $orders->getCollection();
        $totalOrders = $orders->total();
        $pendingOrders = $ordersCollection->filter(function ($order) {
            return ($order->status_bayar ?? '') === 'belum_bayar';
        })->count();
        $paidOrders = $ordersCollection->filter(function ($order) {
            return ($order->status_bayar ?? '') === 'terbayar';
        })->count();
    @endphp

    <div class="customer-section-hero p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <span class="section-chip mb-3"><i class="fas fa-receipt"></i> Order Journal</span>
                <h1 class="section-title mb-3" style="font-size: clamp(2rem, 4vw, 3.4rem); line-height: 1.02;">
                    Pesanan Saya
                    <span style="display:block;background: linear-gradient(90deg, #ea580c 0%, #fb923c 45%, #f43f5e 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">Rapi, Jelas, dan Menarik</span>
                </h1>
                <p class="section-subtitle mb-0">
                    Lihat riwayat pesanan dengan tampilan yang lebih modern, status yang mudah dipindai, dan detail yang tersusun bersih.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="d-grid gap-3">
                    <div class="order-summary-card p-4">
                        <div class="order-kpi">{{ $totalOrders }}</div>
                        <div class="order-kpi-label">Total pesanan</div>
                    </div>
                    <div class="order-summary-card p-4">
                        <div class="order-kpi">{{ $pendingOrders }}</div>
                        <div class="order-kpi-label">Belum dibayar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="order-summary-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:54px;height:54px;background:linear-gradient(135deg,#ea580c,#fb923c);color:#fff;">
                        <i class="fas fa-list fs-5"></i>
                    </div>
                    <div>
                        <div class="order-kpi-label">Riwayat</div>
                        <div class="order-kpi">Pesanan Saya</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="order-summary-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:54px;height:54px;background:linear-gradient(135deg,#14b8a6,#22c55e);color:#fff;">
                        <i class="fas fa-bolt fs-5"></i>
                    </div>
                    <div>
                        <div class="order-kpi-label">Total terbayar</div>
                        <div class="order-kpi">{{ $paidOrders }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="order-summary-card p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:54px;height:54px;background:linear-gradient(135deg,#f43f5e,#fb7185);color:#fff;">
                        <i class="fas fa-mug-hot fs-5"></i>
                    </div>
                    <div>
                        <div class="order-kpi-label">Nuansa</div>
                        <div class="order-kpi">Food Journal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="orders-table-wrap">
        <div class="p-3 p-lg-4 border-bottom" style="background: linear-gradient(180deg, rgba(255,247,237,.9), rgba(255,255,255,.92));">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-2">
                <div>
                    <h3 class="section-title mb-1" style="font-size: clamp(1.3rem, 2.5vw, 1.9rem);">Daftar Pesanan</h3>
                    <p class="text-muted mb-0">Ringkasan pesanan yang lebih mudah dibaca dan lebih enak dilihat.</p>
                </div>
                <span class="section-chip"><i class="fas fa-calendar-days"></i> Terbaru di atas</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 orders-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Vendor</th>
                        <th>Total Harga</th>
                        <th>Status Pesanan</th>
                        <th>Status Bayar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $orderDate = $order->created_at ?? $order->timestamp ?? null;
                            $vendorName = data_get($order, 'vendor.nama_vendor')
                                ?? data_get($order, 'detailPesanan.0.menu.vendor.nama_vendor')
                                ?? 'Vendor tidak diketahui';
                            $orderId = $order->{\App\Models\Pesanan::keyColumn()} ?? null;
                            $displayNoPesanan = $order->no_pesanan ?? $order->order_id ?? $orderId ?? '-';
                            $displayTotal = $order->total_harga ?? $order->total ?? 0;
                            $displayStatusPesanan = $order->status_pesanan ?? $order->status_bayar ?? '-';
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold" style="color:#0f172a;">{{ $displayNoPesanan }}</div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="fw-semibold" style="color:#1e293b;">{{ $vendorName }}</span>
                                    <span class="order-pill order-pill-soft" style="width: fit-content;">
                                        <i class="fas fa-store"></i> Food vendor
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold" style="color:#ea580c;">Rp {{ number_format($displayTotal, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 bg-{{ $displayStatusPesanan === 'completed' ? 'success' : ($displayStatusPesanan === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $displayStatusPesanan)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 bg-{{ $order->status_bayar === 'terbayar' ? 'success' : ($order->status_bayar === 'failed' ? 'danger' : 'info') }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status_bayar)) }}
                                </span>
                            </td>
                            <td>
                                <span style="color:#64748b;">{{ $orderDate ? \Illuminate\Support\Carbon::parse($orderDate)->format('d/m/Y H:i') : '-' }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($orderId)
                                        <a href="{{ route('customer.order-detail', $orderId) }}"
                                           class="btn btn-sm btn-food-outline d-inline-flex align-items-center gap-1 px-3 order-action-btn">
                                            <i class="fas fa-eye"></i>
                                            <span>Detail</span>
                                        </a>
                                    @else
                                        <span class="text-muted">ID pesanan tidak valid</span>
                                    @endif
                                    @if($orderId && $order->status_bayar === 'belum_bayar')
                                        <a href="{{ route('customer.payment', $orderId) }}"
                                           class="btn btn-sm btn-food d-inline-flex align-items-center gap-1 px-3 order-action-btn">
                                            <i class="fas fa-credit-card"></i>
                                            <span>Bayar</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-inline-flex flex-column align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:72px;height:72px;background:#fff7ed;color:#c2410c;">
                                        <i class="fas fa-receipt fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 section-title">Belum ada pesanan</h5>
                                        <p class="text-muted mb-0">Saat ada pesanan, riwayatnya akan tampil di sini.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $orders->links() }}
        </div>
    @endif
@endsection

