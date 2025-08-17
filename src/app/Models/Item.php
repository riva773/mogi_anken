<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'buyer_id'
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
}
