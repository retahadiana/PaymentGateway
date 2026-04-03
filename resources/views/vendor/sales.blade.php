@extends('layout.master')

@section('title', 'Laporan Penjualan')

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
        <a href="{{ route('vendor.sales') }}" class="{{ Route::currentRouteName() === 'vendor.sales' ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Laporan Penjualan
        </a>
    </div>
@endsection

@section('content')
    <div class="food-hero p-4 mb-4">
        <span class="food-chip mb-2"><i class="fas fa-chart-line"></i> Sales Report</span>
        <h1 class="mb-0 food-title"><i class="fas fa-chart-bar me-2"></i>Laporan Penjualan</h1>
    </div>

    <div class="card food-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('vendor.sales') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" name="from" id="from" value="{{ $from }}">
                </div>
                <div class="col-md-4">
                    <label for="to" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="to" id="to" value="{{ $to }}">
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-food w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card food-card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Total Penjualan</h6>
                    <h2 style="color:#ea580c;">{{ $count }}</h2>
                    <small class="text-muted">pesanan</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card food-card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Total Pendapatan</h6>
                    <h2 style="color:#16a34a;">Rp {{ number_format($total, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card food-card">
        <div class="card-header text-white" style="background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);">
            <h5 class="mb-0">Detail Penjualan ({{ $count }} pesanan)</h5>
        </div>
        <div class="card-body">
            @if($sales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-food align-middle">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Tanggal</th>
                                <th>Nama Customer</th>
                                <th>Jumlah Item</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                @php
                                    $saleDateRaw = $sale->created_at ?? $sale->timestamp ?? null;
                                    $saleDate = $saleDateRaw ? \Illuminate\Support\Carbon::parse($saleDateRaw)->format('d/m/Y H:i') : '-';
                                    $saleOrderNo = $sale->no_pesanan ?? $sale->order_id ?? $sale->{\App\Models\Pesanan::keyColumn()} ?? '-';
                                    $saleCustomer = $sale->nama_customer ?? $sale->nama ?? 'Guest';
                                    $saleTotal = $sale->total_harga ?? $sale->total ?? 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $saleOrderNo }}</strong></td>
                                    <td>{{ $saleDate }}</td>
                                    <td>{{ $saleCustomer }}</td>
                                    <td>{{ $sale->detailPesanan->sum('jumlah') }} item</td>
                                    <td><strong>Rp {{ number_format($saleTotal, 0, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Tidak ada data penjualan untuk periode ini.
                </div>
            @endif
        </div>
    </div>
@endsection

