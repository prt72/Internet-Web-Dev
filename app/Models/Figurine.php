<?php
// Catalogue
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Figurine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'series', 'edition', 'comment', 'purchase_date', 'condition', 'category', 'image_url', 'status', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship with Collectible (user collections)
    public function userCollections()
    {
        return $this->hasMany(Collectible::class);
    }
}
