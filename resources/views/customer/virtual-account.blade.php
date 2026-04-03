@extends('layout.master')

@section('title', 'Status Pembayaran')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="food-hero p-4 mb-4">
                <span class="food-chip mb-2"><i class="fas fa-info-circle"></i> Informasi Pembayaran</span>
                <h1 class="mb-0 food-title">Status Pembayaran Pesanan</h1>
            </div>

            <div class="card food-card">
                <div class="card-body p-4">
                    <div class="mb-2"><strong>No. Pesanan:</strong> {{ $pesanan->no_pesanan }}</div>
                    <div class="mb-2"><strong>Status Bayar:</strong> {{ $pesanan->status_bayar }}</div>
                    <div class="mb-4"><strong>Total:</strong> Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>

                    <div class="alert alert-warning mb-4">
                        Jika memilih virtual account, silakan gunakan nomor berikut:
                        <div class="fw-bold mt-2">{{ $vaNumber }}</div>
                    </div>

                    <a href="{{ route('customer.order-detail', $pesanan) }}" class="btn btn-food w-100">
                        Lihat Detail Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
