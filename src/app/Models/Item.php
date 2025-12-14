<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'condition',
        'seller_id',
        'status',
        'brand',
        'buyer_id',
        'categories'
    ];

    protected $casts = [
        'categories' => 'array',
    ];

    use HasFactory;
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }


    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'likes'
        )->withTimestamps();
    }

    public function shippingOverrides()
    {
        return $this->hasMany(ItemShippingOverride::class);
    }

    public function effectiveShippingAddressFor(User $user)
    {
        $override = $this->shippingOverrides()->where('user_id', $user->id)->first();

        if ($override) {
            return [
                'postal_code' => $override->postal_code,
                'address' => $override->address,
                'building' => $override->building,
            ];
        }
        return [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ];
    }
}
