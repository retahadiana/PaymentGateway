<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo d-flex align-items-center gap-2 px-3" href="{{ url('/') }}" style="text-decoration:none;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:12px;background:linear-gradient(135deg,#ea580c 0%,#fb923c 100%);color:#fff;box-shadow:0 10px 20px rgba(234,88,12,.18);">
                <i class="mdi mdi-food-turkey"></i>
            </span>
            <span style="font-weight:900;font-size:1.2rem;color:#7c2d12;letter-spacing:-.02em;">Fast Order</span>
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}" style="font-weight:900;color:#7c2d12;text-decoration:none;">FO</a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                    </div>
                    <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
                </div>
            </form>
        </div>
        <ul class="navbar-nav navbar-nav-right">
            @auth
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="nav-profile-img">
                            <img src="{{ asset('images/faces/face1.jpg') }}" alt="image">
                            <span class="availability-status online"></span>
                        </div>
                        <div class="nav-profile-text">
                            <p class="mb-1 text-black">{{ auth()->user()->name }}</p>
                        </div>
                    </a>
                    <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit"><i class="mdi mdi-logout me-2 text-primary"></i> Signout</button>
                        </form>
                    </div>
                </li>
            @else
                <li class="nav-item nav-profile">
                    <a class="nav-link" href="{{ route('customer.cart') }}">
                        <i class="mdi mdi-cart-outline me-1"></i> Keranjang
                    </a>
                </li>
                <li class="nav-item nav-profile">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="mdi mdi-account me-1"></i> Login Vendor
                    </a>
                </li>
            @endauth
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
