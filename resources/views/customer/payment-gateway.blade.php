@extends('layout.master')

@section('title', 'Pembayaran')

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
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="food-hero p-4 mb-4">
                <span class="food-chip mb-2"><i class="fas fa-credit-card"></i> Secure Checkout</span>
                <h1 class="mb-0 food-title"><i class="fas fa-credit-card me-2"></i>Pembayaran Pesanan</h1>
            </div>

            <div class="card food-card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);">
                    <h5 class="mb-0">Detail Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>No. Pesanan:</strong><br>
                            {{ $pesanan->no_pesanan }}
                        </div>
                        <div class="col-md-6">
                            <strong>Vendor:</strong><br>
                            {{ $pesanan->vendor->nama_vendor }}
                        </div>
                    </div>
                    <h6 class="mt-4 mb-3">Item Pesanan:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-food align-middle">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detailPesanan as $detail)
                                    <tr>
                                        <td>{{ $detail->nama_menu }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card food-card">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);">
                    <h5 class="mb-0">Pilih Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.payment.process', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="transfer" value="transfer_bank" required>
                                <label class="form-check-label" for="transfer">
                                    <strong>Transfer Bank</strong>
                                    <small class="text-muted d-block">Transfer ke rekening vendor</small>
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="va" value="virtual_account">
                                <label class="form-check-label" for="va">
                                    <strong>Virtual Account</strong>
                                    <small class="text-muted d-block">Nomor akun virtual yang dibuat khusus</small>
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="ewallet" value="e_wallet">
                                <label class="form-check-label" for="ewallet">
                                    <strong>E-Wallet</strong>
                                    <small class="text-muted d-block">OVO, GoPay, DANA, dll</small>
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="cicilan" value="cicilan">
                                <label class="form-check-label" for="cicilan">
                                    <strong>Cicilan</strong>
                                    <small class="text-muted d-block">Cicilan 0% tersedia</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nomor_rekening" class="form-label">Nomor Rekening/E-Wallet (opsional)</label>
                            <input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" placeholder="Masukkan nomor rekening">
                        </div>

                        <div class="mb-3">
                            <label for="nama_akun" class="form-label">Atas Nama (opsional)</label>
                            <input type="text" class="form-control" name="nama_akun" id="nama_akun" placeholder="Atas nama pemilik rekening">
                        </div>

                        <button type="submit" class="btn btn-food btn-lg w-100">
                            <i class="fas fa-check"></i> Lanjutkan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card food-card sticky-top" style="top: 20px;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #b91c1c 0%, #ea580c 100%);">
                    <h5 class="mb-0">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Item:</span>
                        <strong>{{ $pesanan->detailPesanan->sum('jumlah') }} item</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkir:</span>
                        <strong>-</strong>
                    </div>
                    <hr style="border-top: 2px solid #ea580c;">
                    <div class="d-flex justify-content-between">
                        <span class="h5">Total Bayar:</span>
                        <strong class="h5">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

