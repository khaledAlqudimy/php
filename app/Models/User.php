<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    // نفس القيم الافتراضية في قاعدة البيانات — حتى تكون صحيحة قبل إعادة تحميل النموذج
    protected $attributes = [
        'role'      => 'CUSTOMER',
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'password'   => 'hashed',
            'is_active'  => 'boolean',
        ];
    }

    // ─── صلاحيات ───
    public function isAdmin(): bool
    {
        return in_array($this->role, ['SUPER_ADMIN', 'MANAGER', 'STAFF']);
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['SUPER_ADMIN', 'MANAGER']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'SUPER_ADMIN';
    }

    // ─── العلاقات ───
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function personalShopperRequests()
    {
        return $this->hasMany(PersonalShopperRequest::class);
    }

    public function auctionInterests()
    {
        return $this->hasMany(AuctionInterest::class);
    }
}
