<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $table = 'wishlist_items';

    protected $fillable = [
        'user_id',
        'figurine_id',
        'name',
    ];

    public function figurine()
{
    return $this->belongsTo(Figurine::class, 'figurine_id');
}

}
