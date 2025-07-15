<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function wishlist()
    {
        return $this
            ->belongsToMany(
                \App\Models\Figurine::class,
                'wishlist_items',
                'user_id',
                'figurine_id'
            )
            ->withTimestamps();
    }
    
    public function wishlistItems()
{
    return $this->hasMany(WishlistItem::class)->with('figurine');
}

    public function collection()
    {
    return $this->belongsToMany(Figurine::class, 'collectibles')
        ->withPivot('quantity', 'purchase_date', 'comment', 'condition', 'purchase_source')
        ->withTimestamps();
    }

    public function collectibles()
    {
        return $this->hasMany(Collectible::class, 'user_id');
    }
}
