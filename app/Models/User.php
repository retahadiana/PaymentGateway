<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'is_admin',
        'id_vendor',
        'phone',
        'id_google',
        'password',
        'otp',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is a vendor
     */
    public function isVendor(): bool
    {
        return $this->type === 'vendor';
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->type === 'customer';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get the vendor profile if user is vendor
     */
    public function vendor(): HasOne
    {
        $foreignKey = Schema::hasColumn('vendor', 'id_vendor') ? 'id_vendor' : 'idvendor';

        return $this->hasOne(Vendor::class, $foreignKey, 'id');
    }

    /**
     * Get all orders for this customer
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'user_id', 'id');
    }
}
