<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Payment Gateway') - Food Order Platform</title>
    @include('layout.head')
    <style>
      :root {
        --food-primary: #ea580c;
        --food-primary-dark: #c2410c;
        --food-secondary: #7c2d12;
        --food-surface: #fff7ed;
        --food-surface-strong: #ffffff;
        --food-border: #fed7aa;
        --food-text: #1f2937;
        --food-muted: #6b7280;
      }

      body {
        background: linear-gradient(180deg, #fff7ed 0%, #fffaf4 100%);
        color: var(--food-text);
      }

      .main-panel .content-wrapper {
        background: transparent;
      }

      .navbar.default-layout-navbar,
      .navbar.default-layout-navbar .navbar-menu-wrapper {
        background: rgba(255, 251, 245, 0.96) !important;
      }

      .navbar.default-layout-navbar {
        border-bottom: 1px solid #fed7aa;
      }

      .navbar-brand-wrapper {
        background: transparent !important;
      }

      .navbar-brand.brand-logo,
      .navbar-brand.brand-logo-mini {
        color: var(--food-secondary) !important;
        font-weight: 900;
      }

      .sidebar.sidebar-offcanvas {
        background: linear-gradient(180deg, #fffdf9 0%, #fff7ed 100%) !important;
        border-right: 1px solid #fed7aa;
      }

      .sidebar .nav .nav-item .nav-link {
        color: #7c2d12 !important;
        border-radius: 1rem;
        margin: .2rem .75rem;
        transition: all .2s ease;
      }

      .sidebar .nav .nav-item .nav-link:hover {
        background: #ffedd5;
        color: #c2410c !important;
      }

      .sidebar .nav .nav-item .nav-link.active {
        background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
        color: #fff !important;
        box-shadow: 0 10px 20px rgba(234, 88, 12, 0.18);
      }

      .sidebar .nav .nav-item .nav-link.active .menu-icon,
      .sidebar .nav .nav-item .nav-link:hover .menu-icon {
        color: inherit !important;
      }

      .sidebar .nav .nav-profile .nav-profile-text,
      .sidebar .nav .nav-profile .nav-profile-image,
      .navbar .nav-profile-text {
        color: #7c2d12 !important;
      }

      .food-hero {
        background: linear-gradient(135deg, rgba(255,247,237,0.95), rgba(255,237,213,0.95));
        border: 1px solid var(--food-border);
        border-radius: 1.5rem;
        box-shadow: 0 20px 45px rgba(234, 88, 12, 0.08);
      }

      .food-card {
        background: var(--food-surface-strong);
        border: 1px solid var(--food-border);
        border-radius: 1.25rem;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
      }

      .food-card-soft {
        background: linear-gradient(180deg, #fff 0%, #fff7ed 100%);
        border: 1px solid #fed7aa;
        border-radius: 1.25rem;
      }

      .card {
        border-color: #fed7aa;
      }

      .food-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .8rem;
        border-radius: 999px;
        background: #ffedd5;
        color: var(--food-secondary);
        font-weight: 700;
        font-size: .82rem;
      }

      .food-title {
        color: var(--food-secondary);
        font-weight: 900;
        letter-spacing: -.02em;
      }

      .btn-food {
        background: linear-gradient(135deg, var(--food-primary) 0%, #fb923c 100%);
        border: 0;
        color: #fff;
        box-shadow: 0 10px 20px rgba(234, 88, 12, 0.18);
      }

      .btn-food:hover,
      .btn-food:focus {
        background: linear-gradient(135deg, var(--food-primary-dark) 0%, var(--food-primary) 100%);
        color: #fff;
      }

      .btn-food-outline {
        border: 1px solid var(--food-border);
        color: var(--food-primary-dark);
        background: #fff;
      }

      .btn-food-outline:hover {
        background: #fff7ed;
        color: var(--food-primary-dark);
      }

      .table-food thead {
        background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);
        color: #fff;
      }

      .table-food tbody tr:hover {
        background: #fff7ed;
      }

      /* Sidebar direct links styling */
      .sidebar > a,
      .sidebar > div > a {
        display: block;
        padding: 0.875rem 1rem;
        margin: 0.25rem 0.75rem;
        color: #7c2d12 !important;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        text-decoration: none;
      }

      .sidebar > a:hover,
      .sidebar > div > a:hover {
        background: #ffedd5;
        color: #c2410c !important;
      }

      .sidebar > a.active,
      .sidebar > div > a.active {
        background: linear-gradient(135deg, #b66dff 0%, #d084f5 100%);
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 10px 20px rgba(182, 109, 255, 0.2);
      }

      .sidebar > a.active i,
      .sidebar > div > a.active i,
      .sidebar > a.active span,
      .sidebar > div > a.active span,
      .sidebar > a.active * {
        color: #ffffff !important;
      }

      .sidebar > div > a.active * {
        color: #ffffff !important;
      }
    </style>
    @stack('page-styles')
  </head>
  <body>
    @include('layout.navbar')

    <div class="container-fluid page-body-wrapper">
      @include('layout.sidebar')

      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>

        @include('layout.footer')
      </div>
    </div>

    @include('layout.scripts')
    @stack('page-scripts')
  </body>
</html>