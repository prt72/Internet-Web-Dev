<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Figurine;

class CollectibleFactory extends Factory
{
    public function definition()
    {
        // Pick a random existing figurine from the figurines table
        $figurine = Figurine::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'user_id'         => $user->id,
            'name'            => $figurine->name,
            'image'           => $figurine->image_url,
            'series'          => $figurine->series,
            'edition'         => $figurine->edition,
            'rarity'          => $figurine->rarity,
            'purchase_date'   => null, // Current date (user input later)
            'condition'       => null, // Default condition (user can edit later)
            'purchase_source' => null, // Default source (user can edit later)
            'quantity'        => 1,
            'comment' => null,
        ];
    }
}
