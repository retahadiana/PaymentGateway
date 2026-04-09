@extends('layout.master')

@section('title', 'Pesanan Vendor')

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
        <a href="{{ route('vendor.menu-list') }}">
            <i class="fas fa-list"></i> Menu
        </a>
        <a href="{{ route('vendor.sales') }}">
            <i class="fas fa-chart-bar"></i> Laporan Penjualan
        </a>
    </div>
@endsection

@section('content')
    <div class="food-hero p-4 mb-4">
        <span class="food-chip mb-2"><i class="fas fa-shopping-bag"></i> Order Queue</span>
        <h1 class="mb-0 food-title"><i class="fas fa-shopping-bag me-2"></i>Pesanan</h1>
    </div>

    <div class="mb-3">
        <a href="{{ route('vendor.orders') }}" class="btn btn-sm {{ !request('status') ? 'btn-food' : 'btn-food-outline' }}">
            Semua
        </a>
        <a href="{{ route('vendor.orders', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-food' : 'btn-food-outline' }}">
            Pending
        </a>
        <a href="{{ route('vendor.orders', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-food' : 'btn-food-outline' }}">
            Confirmed
        </a>
        <a href="{{ route('vendor.orders', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') === 'completed' ? 'btn-food' : 'btn-food-outline' }}">
            Completed
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-food align-middle">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Nama Customer</th>
                    <th>Total Harga</th>
                    <th>Status Pesanan</th>
                    <th>Status Bayar</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
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
                            <span class="badge bg-{{ $displayStatusPesanan === 'completed' ? 'success' : ($displayStatusPesanan === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $displayStatusPesanan)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->status_bayar === 'terbayar' ? 'success' : ($order->status_bayar === 'failed' ? 'danger' : 'info') }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status_bayar)) }}
                            </span>
                        </td>
                        <td>{{ $orderDate ? \Illuminate\Support\Carbon::parse($orderDate)->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            @if($orderId)
                                <a href="{{ route('vendor.order-detail', $orderId) }}"
                                   class="btn btn-sm btn-food-outline d-inline-flex align-items-center gap-1 px-3">
                                    <i class="fas fa-eye"></i>
                                    <span>Detail</span>
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

    @if($orders->hasPages())
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
@endsection

