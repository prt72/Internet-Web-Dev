<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Ensure at least one user exists in the database
        $user = User::latest()->first() ?? User::factory()->create();

        return [
            'username'         => $user->username,  // Use the real user's username
            'first_name'       => $user->first_name,
            'last_name'        => $user->last_name,
            'email'            => $user->email,     // Use the real user's email
            'password'         => bcrypt('password'),  // Real passwords may vary
            'remember_token'   => Str::random(10),
        ];
    }
}
