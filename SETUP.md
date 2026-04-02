# Payment Gateway - Setup & Implementation Guide

## Project Overview

Sistem Payment Gateway dengan dual role (Customer & Vendor) yang memungkinkan:
- **Customer**: Menjelajahi vendor, memesan menu, melakukan pembayaran
- **Vendor**: Mengelola menu, memproses pesanan, menerima pembayaran

## Database Structure

Sistem menggunakan 5 tabel utama:
- `users` - User account dengan role (customer/vendor)
- `vendor` - Profil vendor  
- `menu` - Daftar menu/produk
- `pesanan` - Order/pesanan
- `detail_pesanan` - Detail item dalam pesanan

Lihat [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) untuk detail lengkap.

## Installation & Setup

### 1. Environment Setup

```bash
# Pastikan .env sudah dikonfigurasi dengan database baru
DB_DATABASE=paymentgateway
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Run Migrations

```bash
php artisan migrate
```

Migrations akan membuat struktur database baru sesuai Payment Gateway system.

### 3. Create Test Data (Optional)

```bash
php artisan db:seed
```

(Sebuah seeder bisa dibuat di `database/seeders/`)

### 4. Start Development Server

```bash
php artisan serve
```

Akses di `http://127.0.0.1:8000`

## User Flow

### Customer Flow

1. **Login/Register**
   - `/login` - Login dengan email & password
   - Google OAuth support di `/auth/google/redirect`
   - OTP verification di `/otp`

2. **Browse & Order**
   - `/customer/dashboard` - Lihat daftar vendor
   - `/customer/vendor/{vendor}` - Lihat detail vendor & menu
   - Add to cart & checkout

3. **Payment**
   - `/customer/payment/{pesanan}` - Pilih metode pembayaran
   - Metode: Transfer Bank, Virtual Account, E-Wallet, Cicilan

4. **Order Management**
   - `/customer/orders` - Lihat riwayat pesanan
   - `/customer/order/{pesanan}` - Detail pesanan
   - `/customer/payment-status/{pesanan}` - Status pembayaran

### Vendor Flow

1. **Login/Register**
   - Same sebagai customer, but dengan type `vendor`

2. **Dashboard**
   - `/vendor/dashboard` - Overview stats (total pesanan, pendapatan, dll)

3. **Menu Management**
   - `/vendor/menu` - Daftar menu
   - `/vendor/menu/create` - Tambah menu baru
   - `/vendor/menu/{menu}/edit` - Edit menu
   - `/vendor/menu/{menu}` - Hapus menu

4. **Order Management**
   - `/vendor/orders` - Lihat semua pesanan (dengan status filter)
   - `/vendor/order/{pesanan}` - Detail pesanan & update status
   - `/vendor/order/{pesanan}/confirm-payment` - Konfirmasi pembayaran

5. **Report**
   - `/vendor/sales` - Laporan penjualan dengan date range filter

## Route Structure

```
GET  /                                - Welcome page
GET  /login                           - Login form
POST /login                           - Process login
GET  /auth/google/redirect           - Google OAuth redirect
GET  /auth/google/callback           - Google OAuth callback
GET  /otp                            - OTP verification form
POST /otp                            - Verify OTP
POST /logout                         - Logout

# Customer Routes (Protected, auth middleware)
GET  /customer/dashboard             - Dashboard customer
GET  /customer/vendor/{vendor}       - Detail vendor & menu
GET  /customer/orders                - Riwayat pesanan
GET  /customer/order/{pesanan}       - Detail pesanan
POST /customer/checkout              - Buat pesanan baru
GET  /customer/payment/{pesanan}     - Form pembayaran
POST /customer/payment/{pesanan}     - Process pembayaran
GET  /customer/payment-status/{pesanan}  - Status pembayaran
POST /customer/payment/retry/{pesanan}   - Retry pembayaran

# Vendor Routes (Protected, auth + check.vendor middleware)
GET  /vendor/dashboard               - Dashboard vendor
GET  /vendor/menu                    - Daftar menu
GET  /vendor/menu/create             - Form tambah menu
POST /vendor/menu                    - Save menu baru
GET  /vendor/menu/{menu}/edit        - Form edit menu
PUT  /vendor/menu/{menu}             - Update menu
DELETE /vendor/menu/{menu}           - Delete menu
GET  /vendor/orders                  - Daftar pesanan
GET  /vendor/order/{pesanan}         - Detail pesanan
PUT  /vendor/order/{pesanan}/status  - Update status pesanan
POST /vendor/order/{pesanan}/confirm-payment - Konfirmasi pembayaran
GET  /vendor/sales                   - Laporan penjualan

# Webhook
POST /webhook/payment                - Payment gateway webhook
```

## Authentication & Authorization

### Middleware
- `auth` - Check user logged in
- `check.vendor` - Check user is vendor

### User Types
- `customer` - Pembeli, bisa lihat vendor & buat pesanan
- `vendor` - Penjual, kelola menu & pesanan

### Helper Methods (User Model)
```php
auth()->user()->isVendor()   // Check if vendor
auth()->user()->isCustomer() // Check if customer
auth()->user()->vendor       // Get vendor profile (if vendor)
auth()->user()->pesanan      // Get customer orders (if customer)
```

## Models & Relationships

### User
```php
hasOne: Vendor (through id)
hasMany: Pesanan (as customer)
```

### Vendor
```php
belongsTo: User
hasMany: Menu
hasMany: Pesanan
```

### Menu
```php
belongsTo: Vendor
hasMany: DetailPesanan
```

### Pesanan
```php
belongsTo: User (customer)
belongsTo: Vendor
hasMany: DetailPesanan
```

### DetailPesanan
```php
belongsTo: Pesanan
belongsTo: Menu
```

## Controllers

### AuthController
- `showLogin()` - Login form
- `login()` - Process login with OTP
- `redirectToGoogle()` - Google OAuth redirect
- `handleGoogleCallback()` - Google OAuth callback
- `showOtpForm()` - OTP form
- `verifyOtp()` - Verify OTP & login
- `logout()` - Logout user

### CustomerController
- `dashboard()` - Show vendor list
- `viewVendor()` - Show vendor detail & menu
- `myOrders()` - Customer order history
- `orderDetail()` - Order details
- `checkout()` - Create order from cart
- `paymentStatus()` - Payment status page

### VendorController
- `dashboard()` - Vendor stats
- `menuList()` - Menu management
- `createMenu()` / `storeMenu()` - Create menu
- `editMenu()` / `updateMenu()` - Edit menu
- `deleteMenu()` - Delete menu
- `orders()` - Order list with filter
- `orderDetail()` - Order detail
- `updateOrderStatus()` - Update order status
- `sales()` - Sales report

### PaymentController
- `show()` - Payment form
- `process()` - Process payment
- `confirmPayment()` - Confirm payment (vendor)
- `virtualAccountInfo()` - VA info
- `webhook()` - Payment gateway webhook
- `retry()` - Retry payment

## TODO & Future Features

- [ ] Implement shopping cart system (database/session based)
- [ ] Integrate payment gateway (Midtrans, GoPay, etc)
- [ ] Email notifications for orders
- [ ] SMS notification for OTP
- [ ] Order tracking real-time
- [ ] Rating & review system
- [ ] Inventory management improvements
- [ ] Commission system for platform
- [ ] Export invoice/receipt PDF
- [ ] Mobile app (Flutter/React Native)
- [ ] Advanced analytics for vendors

## File Structure

```
app/
  Http/
    Controllers/
      AuthController.php
      CustomerController.php
      VendorController.php
      PaymentController.php
    Middleware/
      CheckVendor.php
  Models/
    User.php
    Vendor.php
    Menu.php
    Pesanan.php
    DetailPesanan.php

database/
  migrations/
    2026_04_02_000000_add_role_to_users_table.php
    2026_04_02_000001_create_vendor_table.php
    2026_04_02_000002_create_menu_table.php
    2026_04_02_000003_create_pesanan_table.php
    2026_04_02_000004_create_detail_pesanan_table.php

resources/
  views/
    layouts/
      app.blade.php
    customer/
      dashboard.blade.php
      vendor-detail.blade.php
      my-orders.blade.php
      order-detail.blade.php
      payment-gateway.blade.php
    vendor/
      dashboard.blade.php
      menu-list.blade.php
      menu-form.blade.php
      orders.blade.php
      order-detail.blade.php
      sales.blade.php

routes/
  web.php
```

## Key Features

### Dual Role System
- Users dapat registrasi sebagai customer atau vendor
- Role-based access control
- Separate dashboard untuk setiap role

### Order Management
- Customer create order dari multiple vendor
- Order split by vendor otomatis
- Real-time status tracking

### Payment Processing
- Multiple payment methods support
- OTP verification
- Webhook integration for payment confirmation
- Payment retry mechanism

### Vendor Dashboard
- Real-time statistics
- Order management dengan status tracking
- Menu inventory management
- Sales reporting dengan date range filter

## Testing

### Test Customer Flow
```
1. Login: retahadiana190306@gmail.com (jika sudah ada)
2. Lihat vendor di dashboard
3. Pilih vendor & browse menu
4. Buat pesanan
5. Lakukan pembayaran
```

### Test Vendor Flow
```
1. Login dengan akun vendor
2. Tambah/edit menu
3. Lihat pesanan coming in
4. Update status pesanan
5. Konfirmasi pembayaran
```

## Support & Documentation

- Database Schema: [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md)
- Laravel Docs: https://laravel.com/docs
- Bootstrap Docs: https://getbootstrap.com/docs

## Notes

- System menggunakan OTP verification untuk security
- Google OAuth terintegrasi untuk kemudahan login
- Payment gateway masih simulasi (bisa diganti dengan real gateway)
- Semua timestamps in local timezone
- Default locale: Indonesia (id)
