<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'seller_id', 'id');
    }

    public function purchasedItems(): HasMany
    {
        return $this->hasMany(Item::class, 'buyer_id', 'id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function likedItems(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            'likes'
        )->withTimestamps();
    }

    public function shippingOverrides()
    {
        return $this->hasMany(ItemShippingOverride::class);
    }

   

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPostalCodeFormattedAttribute()
    {
        $zip = $this->postal_code;
        if (!is_string($zip) || !preg_match('/^\d{7}$/', $zip)) {
            return null;
        }
        return substr($zip, 0, 3) . '-' . substr($zip, 3, 4);
    }

    public function getAvatarUrlAttribute()
    {
        if (empty($this->avatar)) {
            return asset('images/default-avatar.png');
        }

        if (Str::startsWith($this->avatar, ['http://', 'https://'])) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }
}
