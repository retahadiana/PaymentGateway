# Payment Gateway - Database Schema

## Updated Database Structure

### 1. Users Table
- **id** (primary key)
- **name** - Nama user
- **email** - Email unique
- **type** - Role: 'customer' atau 'vendor'
- **id_vendor** - Referensi ke vendor (untuk vendor users)
- **phone** - Nomor telepon
- **id_google** - Google ID untuk OAuth
- **password** - password hashed
- **otp** - One Time Password untuk login verification
- **email_verified_at** - Timestamp verifikasi email
- **timestamps** - created_at, updated_at

### 2. Vendor Table
- **id_vendor** (primary key) - Referensi ke users.id
- **nama_vendor** - Nama toko/restoran
- **email** - Email vendor
- **phone** - Nomor telepon vendor
- **alamat** - Alamat lengkap
- **kota** - Kota vendor
- **timestamps** - created_at, updated_at

Foreign Key: id_vendor -> users.id (cascade delete)

### 3. Menu Table
- **idmenu** (primary key)
- **id_vendor** - Foreign key ke vendor.id_vendor
- **nama_menu** - Nama item menu
- **harga** - Harga in decimal(10,2)
- **detail** - Detail singkat menu
- **deskripsi** - Deskripsi lengkap
- **gambar** - Path foto menu
- **kategori** - Kategori menu
- **stok** - Jumlah stok tersedia
- **aktif** - Boolean status aktif
- **timestamps** - created_at, updated_at

Foreign Key: id_vendor -> vendor.id_vendor (cascade delete)

### 4. Pesanan Table
- **id_pesanan** (primary key)
- **user_id** - Foreign key ke users.id (customer)
- **id_vendor** - Foreign key ke vendor.id_vendor
- **nama_customer** - Nama customer
- **no_pesanan** - Unique pesanan number (PO20260402XXXX format)
- **total_harga** - Total harga in decimal(12,2)
- **metode_bayar** - transfer, virtual_account, e_wallet, cicilan
- **status_pesanan** - pending, confirmed, processing, completed, cancelled
- **status_bayar** - belum_bayar, waiting_confirmation, terbayar, failed
- **catatan** - Catatan pesanan
- **alamat_pengiriman** - Alamat pengiriman
- **tanggal_pesan** - Tanggal pesanan dibuat
- **tanggal_selesai** - Tanggal pesanan selesai
- **timestamps** - created_at, updated_at

Foreign Keys:
- user_id -> users.id (cascade delete)
- id_vendor -> vendor.id_vendor (restrict delete)

### 5. Detail_Pesanan Table
- **IdDetail_pesanan** (primary key)
- **id_pesanan** - Foreign key ke pesanan.id_pesanan
- **idmenu** - Foreign key ke menu.idmenu
- **nama_menu** - Nama menu (snapshot)
- **harga** - Harga saat pembelian in decimal(10,2)
- **jumlah** - Jumlah item
- **subtotal** - Subtotal (harga x jumlah) in decimal(12,2)
- **timestamps** - created_at, updated_at

Foreign Keys:
- id_pesanan -> pesanan.id_pesanan (cascade delete)
- idmenu -> menu.idmenu (restrict delete)

## Relationships

### User Model
- hasOne: Vendor
- hasMany: Pesanan

### Vendor Model
- belongsTo: User
- hasMany: Menu
- hasMany: Pesanan

### Menu Model
- belongsTo: Vendor
- hasMany: DetailPesanan

### Pesanan Model
- belongsTo: User (customer)
- belongsTo: Vendor
- hasMany: DetailPesanan

### DetailPesanan Model
- belongsTo: Pesanan
- belongsTo: Menu

## API Status Values

### Metode Bayar
- transfer_bank - Transfer langsung ke bank vendor
- virtual_account - Virtual account number
- e_wallet - E-wallet (OVO, GoPay, DANA)
- cicilan - Cicilan 0%

### Status Pesanan
- pending - Pesanan baru, menunggu konfirmasi vendor
- confirmed - Pesanan dikonfirmasi vendor
- processing - Sedang diproses vendor
- completed - Pesanan selesai
- cancelled - Pesanan dibatalkan

### Status Bayar
- belum_bayar - Belum melakukan pembayaran
- waiting_confirmation - Menunggu konfirmasi pembayaran dari vendor
- terbayar - Pembayaran berhasil
- failed - Pembayaran gagal

## Migrations Created

1. `2026_04_02_000000_add_role_to_users_table` - Add role and vendor fields to users
2. `2026_04_02_000001_create_vendor_table` - Create vendor table
3. `2026_04_02_000002_create_menu_table` - Create menu table
4. `2026_04_02_000003_create_pesanan_table` - Create pesanan table
5. `2026_04_02_000004_create_detail_pesanan_table` - Create detail_pesanan table

## Running Migrations

```bash
php artisan migrate
```

## Seeding (Optional)

To populate test data, create seeders in `database/seeders/`:
- VendorSeeder
- MenuSeeder
- PesananSeeder
- DetailPesananSeeder

Then run:
```bash
php artisan db:seed
```
