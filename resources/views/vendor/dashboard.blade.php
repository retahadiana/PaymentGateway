@extends('layout.master')

@section('title', 'Vendor Dashboard')

@section('sidebar')
    <div class="col-md-2 sidebar">
        <div class="px-3 mb-4">
            <h5 class="text-uppercase">Menu Vendor</h5>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="active">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('vendor.orders') }}">
            <i class="fas fa-shopping-bag"></i> Pesanan
        </a>
        <a href="{{ route('vendor.menu-list') }}">
            <i class="fas fa-list"></i> Menu
        </a>
        <a href="{{ route('vendor.create-menu') }}">
            <i class="fas fa-plus"></i> Tambah Menu
        </a>
        <a href="{{ route('vendor.sales') }}">
            <i class="fas fa-chart-bar"></i> Laporan Penjualan
        </a>
    </div>
@endsection

@section('content')
    <div class="food-hero p-4 p-lg-5 mb-4">
        <span class="food-chip mb-2"><i class="fas fa-store"></i> Vendor Control Center</span>
        <h1 class="mb-0 food-title"><i class="fas fa-tachometer-alt me-2"></i>Vendor Dashboard</h1>
    </div>

    @if($vendor)
        <div class="alert border-0 mb-4" style="background:#fff7ed;color:#9a3412;">
            <i class="fas fa-info-circle"></i>
            <strong>{{ $vendor->nama_vendor }}</strong> - {{ $vendor->email }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card food-card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Total Pesanan</h6>
                    <h2 style="color:#ea580c;">{{ $stats['total_pesanan'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card food-card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Pesanan Pending</h6>
                    <h2 style="color:#f59e0b;">{{ $stats['pesanan_pending'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card food-card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Pendapatan Hari Ini</h6>
                    <h2 style="color:#16a34a;">Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card food-card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Total Menu</h6>
                    <h2 style="color:#7c2d12;">{{ $vendor->menus()->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="mb-3">Pesanan Terbaru</h3>
            <div class="table-responsive">
                <table class="table table-hover table-food align-middle">
                    <thead>
                        <tr>
                            <a href="{{ route('vendor.dashboard') }}" class="{{ Route::currentRouteName() === 'vendor.dashboard' ? 'active' : '' }}">
                            <th>Nama Customer</th>
                            <th>Total Harga</th>
                            <a href="{{ route('vendor.menu-list') }}" class="{{ Route::currentRouteName() === 'vendor.menu-list' ? 'active' : '' }}">
                            <th>Status Bayar</th>
                            <th>Tanggal</th>
                            <a href="{{ route('vendor.orders') }}" class="{{ Route::currentRouteName() === 'vendor.orders' ? 'active' : '' }}">
                        </tr>
                    </thead>
                            <a href="{{ route('vendor.sales') }}" class="{{ Route::currentRouteName() === 'vendor.sales' ? 'active' : '' }}">
                        @forelse($recentOrders as $order)
                            <tr>
                                @php
                                    $orderDate = $order->created_at ?? $order->timestamp ?? null;
                                    $orderId = $order->{\App\Models\Pesanan::keyColumn()} ?? null;
                                    $displayNoPesanan = $order->no_pesanan ?? $order->order_id ?? $orderId ?? '-';
                                    $displayCustomer = $order->nama_customer ?? $order->nama ?? 'Guest';
                                    $displayTotal = $order->total_harga ?? $order->total ?? 0;
                                    $displayStatusPesanan = $order->status_pesanan ?? $order->status_bayar ?? '-';
                                @endphp
                                <td><strong>{{ $displayNoPesanan }}</strong></td>
                                <td>{{ $displayCustomer }}</td>
                                <td>Rp {{ number_format($displayTotal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge-status bg-{{ $displayStatusPesanan === 'completed' ? 'success' : ($displayStatusPesanan === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $displayStatusPesanan)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status bg-{{ $order->status_bayar === 'terbayar' ? 'success' : ($order->status_bayar === 'failed' ? 'danger' : 'info') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status_bayar)) }}
                                    </span>
                                </td>
                                <td>{{ $orderDate ? \Illuminate\Support\Carbon::parse($orderDate)->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    @if($orderId)
                                        <a href="{{ route('vendor.order-detail', $orderId) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

