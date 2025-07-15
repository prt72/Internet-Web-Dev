<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collectible extends Model
{
    use HasFactory;

    protected $table = 'user_collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'figurine_id',
        'name',
        'series',
        'edition',
        'rarity',
        'purchase_date',
        'condition',
        'comment',
        'purchase_source',
        'quantity',
        'is_tradable',
        'in_wishlist',
        'status', // wishlist or owned
        'duplicates_info', // Add duplicates_info here
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'duplicates_info' => 'array', // Ensure it's treated as an array
    ];

    // Ensure new records start with an empty array
    protected $attributes = [
        'duplicates_info' => '[]', // Default to an empty array
    ];

    /**
     * Get the figurine that owns the Collectible.
     */
    public function figurine()
    {
        return $this->belongsTo(Figurine::class);
    }

    /**
     * Get the user that owns the Collectible.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
