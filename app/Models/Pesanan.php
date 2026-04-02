<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Pesanan extends Model
{
    /** @use HasFactory<\Database\Factories\PesananFactory> */
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'id_vendor',
        'nama_customer',
        'no_pesanan',
        'total_harga',
        'metode_bayar',
        'status_pesanan',
        'status_bayar',
        'catatan',
        'alamat_pengiriman',
        'tanggal_pesan',
        'tanggal_selesai',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'tanggal_pesan' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user (customer) associated with this order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the vendor for this order
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, static::vendorForeignKeyColumn(), Vendor::keyColumn());
    }

    /**
     * Get all detail pesanan for this order
     */
    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, static::detailForeignKeyColumn(), static::keyColumn());
    }

    public static function keyColumn(): string
    {
        return Schema::hasColumn('pesanan', 'id_pesanan') ? 'id_pesanan' : 'idpesanan';
    }

    public static function vendorForeignKeyColumn(): string
    {
        return Schema::hasColumn('pesanan', 'id_vendor') ? 'id_vendor' : 'idvendor';
    }

    public static function detailForeignKeyColumn(): string
    {
        return Schema::hasColumn('detail_pesanan', 'id_pesanan') ? 'id_pesanan' : 'idpesanan';
    }

    public static function timestampColumn(): string
    {
        return Schema::hasColumn('pesanan', 'created_at') ? 'created_at' : 'timestamp';
    }

    public static function totalColumn(): string
    {
        return Schema::hasColumn('pesanan', 'total_harga') ? 'total_harga' : 'total';
    }

    public static function statusBayarColumn(): string
    {
        return Schema::hasColumn('pesanan', 'status_bayar') ? 'status_bayar' : 'status';
    }

    /**
     * Generate unique order number
     */
    public static function generateNoPesanan()
    {
        $prefix = 'PO' . date('YmdH');
        $count = static::whereDate(static::timestampColumn(), today())->count() + 1;
        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate guest username with format Guest_0000001.
     *
     * Logic: ambil ID terakhir dari tabel pesanan, tambah 1,
     * lalu pad ke 7 digit.
     */
    public static function generateGuestUsername(): string
    {
        $lastId = (int) static::max(static::keyColumn());
        $nextId = $lastId + 1;

        return 'Guest_' . str_pad((string) $nextId, 7, '0', STR_PAD_LEFT);
    }

    public function getKeyName(): string
    {
        return static::keyColumn();
    }

    public function usesTimestamps(): bool
    {
        return Schema::hasColumn($this->table, 'created_at') && Schema::hasColumn($this->table, 'updated_at');
    }

    public static function customerNameColumn(): string
    {
        return Schema::hasColumn('pesanan', 'nama_customer') ? 'nama_customer' : 'nama';
    }

    public static function noPesananColumn(): string
    {
        return Schema::hasColumn('pesanan', 'no_pesanan') ? 'no_pesanan' : 'order_id';
    }

    public static function addressColumn(): ?string
    {
        return Schema::hasColumn('pesanan', 'alamat_pengiriman') ? 'alamat_pengiriman' : null;
    }
}
