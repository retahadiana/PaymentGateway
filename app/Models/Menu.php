<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    public $timestamps = false;

    protected $fillable = [
        'id_vendor',
        'idvendor',
        'nama_menu',
        'harga',
        'path_gambar',
        'detail',
        'deskripsi',
        'gambar',
        'kategori',
        'stok',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'aktif' => 'boolean',
        'stok' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the vendor that owns this menu
     */
    public function vendor(): BelongsTo
    {
        $foreignKey = Schema::hasColumn($this->table, 'id_vendor') ? 'id_vendor' : 'idvendor';
        return $this->belongsTo(Vendor::class, $foreignKey, (new Vendor())->getKeyName());
    }

    /**
     * Get all detail pesanan for this menu
     */
    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'idmenu', 'idmenu');
    }

    public function usesTimestamps(): bool
    {
        return Schema::hasColumn($this->table, 'created_at') && Schema::hasColumn($this->table, 'updated_at');
    }
}
