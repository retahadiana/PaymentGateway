@extends('layout.master')

@section('title', 'Detail Pesanan')

@section('sidebar')
    <div class="col-md-2 sidebar">
        <div class="px-3 mb-4">
            <h5 class="text-uppercase">Menu</h5>
        </div>
        <a href="{{ route('customer.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
            <a href="{{ route('customer.dashboard') }}" class="{{ Route::currentRouteName() === 'customer.dashboard' ? 'active' : '' }}">
            <i class="fas fa-list"></i> Pesanan Saya
        </a>
            <a href="{{ route('customer.my-orders') }}" class="{{ Route::currentRouteName() === 'customer.my-orders' ? 'active' : '' }}">
@endsection

@section('content')
    <div class="food-hero p-4 mb-4">
        <span class="food-chip mb-2"><i class="fas fa-receipt"></i> Order Detail</span>
        <h1 class="mb-0 food-title"><i class="fas fa-receipt me-2"></i>Detail Pesanan</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('customer.my-orders') }}" class="btn btn-food-outline mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card food-card mb-3">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);">
                    <h5 class="mb-0">Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>No. Pesanan:</strong><br>
                            {{ $pesanan->no_pesanan ?? $pesanan->order_id ?? $pesanan->{\App\Models\Pesanan::keyColumn()} ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Nama Customer:</strong><br>
                            {{ $pesanan->nama_customer ?? $pesanan->nama ?? 'Guest' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Vendor:</strong><br>
                            {{ optional(optional($pesanan->detailPesanan->first())->menu->vendor)->nama_vendor ?? optional($pesanan->vendor)->nama_vendor ?? 'Vendor tidak diketahui' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Pesanan:</strong><br>
                            @php
                                $orderDate = $pesanan->created_at ?? $pesanan->timestamp ?? null;
                            @endphp
                            {{ $orderDate ? \Illuminate\Support\Carbon::parse($orderDate)->format('d/m/Y H:i') : '-' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Alamat Pengiriman:</strong><br>
                            {{ $pesanan->alamat_pengiriman }}
                        </div>
                        <div class="col-md-6">
                            <strong>Metode Pembayaran:</strong><br>
                            {{ ucfirst(str_replace('_', ' ', $pesanan->metode_bayar)) }}
                        </div>
                    </div>
                    @if($pesanan->catatan)
                        <div class="mt-3">
                            <strong>Catatan:</strong><br>
                            {{ $pesanan->catatan }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card food-card">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);">
                    <h5 class="mb-0">Detail Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-food align-middle">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detailPesanan as $detail)
                                    <tr>
                                        <td>{{ $detail->nama_menu }}</td>
                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>Rp {{ number_format($pesanan->total_harga ?? $pesanan->total ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card food-card mb-3">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #b45309 0%, #ea580c 100%);">
                    <h5 class="mb-0">Status Pesanan</h5>
                </div>
                <div class="card-body text-center">
                    <h6 class="text-muted">Status Pesanan</h6>
                    @php
                        $displayStatusPesanan = $pesanan->status_pesanan ?? $pesanan->status_bayar ?? '-';
                    @endphp
                    <span class="badge bg-{{ $displayStatusPesanan === 'completed' ? 'success' : ($displayStatusPesanan === 'cancelled' ? 'danger' : 'warning') }} p-2 mb-3">
                        {{ ucfirst(str_replace('_', ' ', $displayStatusPesanan)) }}
                    </span>
                    <h6 class="text-muted mt-3">Status Pembayaran</h6>
                    <span class="badge bg-{{ $pesanan->status_bayar === 'terbayar' ? 'success' : ($pesanan->status_bayar === 'failed' ? 'danger' : 'info') }} p-2 mb-3">
                        {{ ucfirst(str_replace('_', ' ', $pesanan->status_bayar)) }}
                    </span>
                </div>
            </div>

            @if($pesanan->status_bayar === 'belum_bayar')
                <div class="card food-card">
                    <div class="card-body">
                        <a href="{{ route('customer.payment', $pesanan->{\App\Models\Pesanan::keyColumn()}) }}" class="btn btn-food w-100">
                            <i class="fas fa-credit-card"></i> Lakukan Pembayaran
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

