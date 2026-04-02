<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;

    protected $table = 'vendor';
    protected $primaryKey = 'id_vendor';
    public $timestamps = false;

    protected $fillable = [
        'id_vendor',
        'nama_vendor',
        'email',
        'phone',
        'alamat',
        'kota',
    ];

    /**
     * Get the user associated with the vendor
     */
    public function user(): BelongsTo
    {
        $foreignKey = static::keyColumn();

        return $this->belongsTo(User::class, $foreignKey, 'id');
    }

    public static function keyColumn(): string
    {
        return Schema::hasColumn('vendor', 'id_vendor') ? 'id_vendor' : 'idvendor';
    }

    public static function menuForeignKeyColumn(): string
    {
        return Schema::hasColumn('menu', 'id_vendor') ? 'id_vendor' : 'idvendor';
    }

    public static function pesananForeignKeyColumn(): string
    {
        return Schema::hasColumn('pesanan', 'id_vendor') ? 'id_vendor' : 'idvendor';
    }

    /**
     * Get all menus for this vendor
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, static::menuForeignKeyColumn(), $this->getKeyName());
    }

    /**
     * Get all orders for this vendor
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, static::pesananForeignKeyColumn(), $this->getKeyName());
    }

    public function getKeyName(): string
    {
        return static::keyColumn();
    }

    public function usesTimestamps(): bool
    {
        return Schema::hasColumn($this->table, 'created_at') && Schema::hasColumn($this->table, 'updated_at');
    }
}
