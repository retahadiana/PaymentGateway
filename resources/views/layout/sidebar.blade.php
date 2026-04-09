<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('images/faces/face1.jpg') }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ auth()->user()->name ?? 'Guest' }}</span>
                    <span class="text-secondary text-small">{{ auth()->user()->email ?? '' }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item">
            @if(auth()->check() && auth()->user()->isVendor())
                <a class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                    <span class="menu-title">Dashboard Vendor</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            @else
                <a class="nav-link {{ request()->routeIs('customer.dashboard') || request()->routeIs('customer.vendor-detail') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                    <span class="menu-title">Dashboard Customer</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            @endif
        </li>

        @if(auth()->check() && auth()->user()->isVendor())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('vendor.orders') || request()->routeIs('vendor.order-detail') ? 'active' : '' }}" href="{{ route('vendor.orders') }}">
                    <span class="menu-title">Pesanan</span>
                    <i class="mdi mdi-receipt menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('vendor.menu-list') || request()->routeIs('vendor.create-menu') || request()->routeIs('vendor.edit-menu') ? 'active' : '' }}" href="{{ route('vendor.menu-list') }}">
                    <span class="menu-title">Kelola Menu</span>
                    <i class="mdi mdi-food menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('vendor.sales') ? 'active' : '' }}" href="{{ route('vendor.sales') }}">
                    <span class="menu-title">Laporan Penjualan</span>
                    <i class="mdi mdi-chart-line menu-icon"></i>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customer.my-orders') || request()->routeIs('customer.order-detail') ? 'active' : '' }}" href="{{ route('customer.my-orders') }}">
                    <span class="menu-title">Pesanan Saya</span>
                    <i class="mdi mdi-cart menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customer.cart') ? 'active' : '' }}" href="{{ route('customer.cart') }}">
                    <span class="menu-title">Keranjang</span>
                    <i class="mdi mdi-basket menu-icon"></i>
                </a>
            </li>
        @endif
    </ul>
</nav>

