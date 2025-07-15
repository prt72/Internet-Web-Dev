<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\FigurinesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1) Bulk‑insert 10 users, ignoring duplicates to prevent unique constraint errors
        $users = \App\Models\User::factory()
            ->count(10)
            ->make()
            ->toArray();
        DB::table('users')->insertOrIgnore($users);

        // 2) Bulk‑insert 50 collectibles, ignoring duplicates
        $collectibles = \App\Models\Collectible::factory()
            ->count(50)
            ->make()
            ->toArray();
        DB::table('collectibles')->insertOrIgnore($collectibles);

        // 3) Seed figurines using the chunked seeder
        $this->call(FigurinesTableSeeder::class);
    }
}
