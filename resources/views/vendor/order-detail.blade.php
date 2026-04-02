@extends('layout.master')

@section('title', 'Detail Pesanan Vendor')

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
    </div>
@endsection

@section('content')
    <div class="food-hero p-4 mb-4">
        <span class="food-chip mb-2"><i class="fas fa-receipt"></i> Order Detail</span>
        <h1 class="mb-0 food-title"><i class="fas fa-receipt me-2"></i>Detail Pesanan</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('vendor.orders') }}" class="btn btn-food-outline mb-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Pesanan
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
                    @php
                        $noPesananCol = \App\Models\Pesanan::noPesananColumn();
                        $customerNameCol = \App\Models\Pesanan::customerNameColumn();
                        $timestampCol = \App\Models\Pesanan::timestampColumn();
                        $addressCol = \App\Models\Pesanan::addressColumn();
                    @endphp
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>No. Pesanan:</strong><br>
                            {{ $pesanan->{$noPesananCol} ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Nama Customer:</strong><br>
                            {{ $pesanan->{$customerNameCol} ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Pesanan:</strong><br>
                            @php
                                $orderDate = $pesanan->{$timestampCol} ?? null;
                            @endphp
                            {{ $orderDate ? \Illuminate\Support\Carbon::parse($orderDate)->format('d/m/Y H:i') : '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Alamat Pengiriman:</strong><br>
                            @if($addressCol && isset($pesanan->{$addressCol}))
                                {{ $pesanan->{$addressCol} }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Metode Pembayaran:</strong><br>
                            {{ $pesanan->metode_bayar ? ucfirst(str_replace('_', ' ', $pesanan->metode_bayar)) : '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Jumlah Item:</strong><br>
                            {{ $pesanan->detailPesanan->sum('jumlah') }} item
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

            <div class="card food-card mb-3">
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
                                @php
                                    $totalCol = \App\Models\Pesanan::totalColumn();
                                @endphp
                                @foreach($pesanan->detailPesanan as $detail)
                                    <tr>
                                        <td>{{ $detail->menu->nama_menu ?? $detail->idmenu ?? '-' }}</td>
                                        <td>Rp {{ number_format($detail->harga ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $detail->jumlah ?? 1 }}</td>
                                        <td>Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>Rp {{ number_format($pesanan->{$totalCol} ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card food-card">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);">
                    <h5 class="mb-0">Update Status Pesanan</h5>
                </div>
                <div class="card-body">
                    @php
                        $pesananId = $pesanan->{\App\Models\Pesanan::keyColumn()} ?? null;
                    @endphp

                    <form action="{{ $pesananId ? route('vendor.update-order-status', $pesananId) : '#' }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status_bayar" class="form-label">Status Pembayaran</label>
                            <select class="form-select" name="status_bayar" id="status_bayar" required>
                                <option value="">- Pilih Status -</option>
                                <option value="belum_bayar" {{ $pesanan->status_bayar === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="waiting_confirmation" {{ $pesanan->status_bayar === 'waiting_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="terbayar" {{ $pesanan->status_bayar === 'terbayar' ? 'selected' : '' }}>Terbayar</option>
                                <option value="pending" {{ $pesanan->status_bayar === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ $pesanan->status_bayar === 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-food">
                            <i class="fas fa-check"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card food-card">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%);">
                    <h5 class="mb-0">Status Pembayaran</h5>
                </div>
                <div class="card-body text-center">
                    <h6 class="text-muted">Status Saat Ini</h6>
                    @php
                        $statusBayarCol = \App\Models\Pesanan::statusBayarColumn();
                        $statusBayar = $pesanan->{$statusBayarCol} ?? 'unknown';
                        $statusColor = match($statusBayar) {
                            'terbayar' => 'success',
                            'failed' => 'danger',
                            'waiting_confirmation' => 'info',
                            'belum_bayar' => 'warning',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $statusColor }} p-2 mb-3" style="font-size: 1rem;">
                        {{ ucfirst(str_replace('_', ' ', $statusBayar)) }}
                    </span>
                    
                    @if($pesanan->status_bayar === 'waiting_confirmation')
                        <div class="mt-3">
                            <form action="{{ $pesananId ? route('vendor.confirm-payment', $pesananId) : '#' }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-food btn-sm w-100">
                                    <i class="fas fa-check"></i> Konfirmasi Pembayaran
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

