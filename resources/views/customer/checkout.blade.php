@extends('layout.master')

@section('title', 'Checkout Midtrans')

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
    @php
        $orderNumber = $pesanan->no_pesanan ?? $pesanan->order_id ?? '-';
        $orderTotal = $pesanan->total_harga ?? $pesanan->total ?? 0;
        $autoOpenSnap = (bool) ($autoOpenSnap ?? false);
    @endphp

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="food-hero p-4 mb-4">
                <span class="food-chip mb-2"><i class="fas fa-lock"></i> Midtrans Secure Payment</span>
                <h1 class="mb-0 food-title"><i class="fas fa-receipt me-2"></i>Checkout Pembayaran</h1>
            </div>

            <div class="card food-card">
                <div class="card-body p-4 p-lg-5">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">No. Pesanan</div>
                            <div class="fw-bold">{{ $orderNumber }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="small text-muted">Total Pembayaran</div>
                            <div class="fw-bold fs-5">Rp {{ number_format($orderTotal, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <p class="text-muted mb-4">
                        Klik tombol di bawah untuk melanjutkan ke popup pembayaran Midtrans.
                    </p>

                    <button id="pay-button" class="btn btn-food btn-lg w-100">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>

                    <a href="{{ route('customer.order-detail', $pesanan) }}" class="btn btn-food-outline w-100 mt-3">
                        Kembali ke Detail Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-scripts')
    <script src="https://app{{ env('MIDTRANS_IS_PRODUCTION', false) ? '' : '.sandbox' }}.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        (function () {
            const payButton = document.getElementById('pay-button');
            const token = @json($snapToken);
            const autoOpen = @json($autoOpenSnap);
            const orderId = @json($orderNumber);
            const syncUrl = @json(route('customer.payment.sync-status', $pesanan));
            const csrfToken = @json(csrf_token());
            let opened = false;

            if (!payButton || !window.snap) {
                return;
            }

            const openSnap = function () {
                if (opened) {
                    return;
                }
                opened = true;

                const syncStatus = function (status, redirectUrl) {
                    fetch(syncUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            transaction_status: status,
                        }),
                    }).finally(function () {
                        window.location.href = redirectUrl;
                    });
                };

                window.snap.pay(token, {
                    onSuccess: function () {
                        syncStatus('settlement', @json(route('customer.order-detail', $pesanan)));
                    },
                    onPending: function () {
                        syncStatus('pending', @json(route('customer.order-detail', $pesanan)));
                    },
                    onError: function () {
                        window.location.href = @json(route('customer.payment', $pesanan));
                    },
                    onClose: function () {
                        window.location.href = @json(route('customer.payment', $pesanan));
                    }
                });
            };

            payButton.addEventListener('click', openSnap);

            if (autoOpen) {
                setTimeout(openSnap, 200);
            }
        })();
    </script>
@endpush
