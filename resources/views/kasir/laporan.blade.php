@extends('layout.master')

@section('title', 'Laporan Kasir')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-chart-line"></i>
            </span>
            Laporan Kasir
        </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Transaksi Hari Ini</h6>
                    <h3 class="mb-1">{{ $todayCount }} transaksi</h3>
                    <h4 class="text-primary mb-0">Rp {{ number_format($todayTotal, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Transaksi Minggu Ini</h6>
                    <h3 class="mb-1">{{ $weekCount }} transaksi</h3>
                    <h4 class="text-success mb-0">Rp {{ number_format($weekTotal, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Transaksi Terbaru</h5>
                    <a href="{{ route('kasir.index') }}" class="btn btn-sm btn-primary">Kembali ke Kasir</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Penjualan</th>
                                    <th>No Invoice</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Total</th>
                                    <th style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestTransactions as $trx)
                                    <tr>
                                        <td>{{ $trx->id_penjualan }}</td>
                                        <td>{{ $trx->no_invoice ?? '-' }}</td>
                                        <td>{{ $trx->tanggal ? \Carbon\Carbon::parse($trx->tanggal)->format('d-m-Y H:i') : '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('kasir.struk', ['id' => $trx->id_penjualan]) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                Cetak Struk
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
