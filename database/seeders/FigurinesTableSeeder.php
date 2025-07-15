<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FigurinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'         => 'Tong Niu Fei Tian',
                'category'     => 'Molly',
                'series'       => 'Anniversary Statues Classical Retro 2',
                'edition'      => 'Silver',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/tong-niu-fei-tan-silver-ver--.png',
                'release_date'=> '2023-08-15',
            ],
            [
                'name'         => 'Tong Niu Fei Tian',
                'category'     => 'Molly',
                'series'       => 'Anniversary Statues Classical Retro 2',
                'edition'      => 'Golden',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/tong-niu-fei-tian-golden-ver.png',
                'release_date'=> '2022-06-10',
            ],
            [
                'name'         => "It's a Beautiful Day",
                'category'     => 'Molly',
                'series'       => 'Anniversary Statues Classical Retro 2',
                'edition'      => 'Original',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/it-s-a-beautiful-day-original-ver.png',
                'release_date'=> '2024-01-25',
            ],
            [
                'name'         => 'You Are Not Alone Rebirth 2006',
                'category'     => 'Molly',
                'series'       => 'Anniversary Statues Classical Retro 2',
                'edition'      => 'Special',
                'rarity'       => 'Rare',
                'image_url'    => '/img/figurines/you-are-not-alone-rebirth-2006-special-ver.png',
                'release_date'=> '2023-07-06',
            ],
            [
                'name'         => 'MC MOLLY (White Stone)',
                'category'     => 'Molly',
                'series'       => 'Anniversary Statues Classical Retro 2',
                'edition'      => 'Secret',
                'rarity'       => 'Very Rare',
                'image_url'    => '/img/figurines/mc-molly-white-stone-secret.png',
                'release_date'=> '2024-02-14',
            ],
            [
                'name'         => 'Dreams of Good Fortune',
                'category'     => 'Dimoo',
                'series'       => 'Weaving Wonders',
                'edition'      => 'Secret',
                'rarity'       => 'Very Rare',
                'image_url'    => '/img/figurines/dreams-of-good-fortune-secret.png',
                'release_date'=> '2024-01-12',
            ],
            [
                'name'         => 'Fortune Cat',
                'category'     => 'Dimoo',
                'series'       => 'Weaving Wonders',
                'edition'      => 'Super Secret',
                'rarity'       => 'Rarest',
                'image_url'    => '/img/figurines/fortune-cat-super-secret.png',
                'release_date'=> '2024-02-03',
            ],
            [
                'name'         => 'Butterfly Wizard',
                'category'     => 'Dimoo',
                'series'       => 'Weaving Wonders',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/butterfly-wizard.png',
                'release_date'=> '2024-03-15',
            ],
            [
                'name'         => 'Bear Guardian',
                'category'     => 'Dimoo',
                'series'       => 'Weaving Wonders',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/bear-guardian.png',
                'release_date'=> '2024-04-05',
            ],
            [
                'name'         => 'Dream with a Butterfly',
                'category'     => 'Dimoo',
                'series'       => 'Weaving Wonders',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/dream-with-a-butterfly.png',
                'release_date'=> '2024-04-19',
            ],
            [
                'name'         => 'Imagination',
                'category'     => 'Hacipupu',
                'series'       => 'A Night of Fantasy',
                'edition'      => 'Secret',
                'rarity'       => 'Very Rare',
                'image_url'    => '/img/figurines/imagination-secret.png',
                'release_date'=> '2024-05-10',
            ],
            [
                'name'         => 'Little Wizard',
                'category'     => 'Hacipupu',
                'series'       => 'A Night of Fantasy',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/little-wizard.png',
                'release_date'=> '2024-05-15',
            ],
            [
                'name'         => 'Monologue',
                'category'     => 'Hacipupu',
                'series'       => 'A Night of Fantasy',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/monologue.png',
                'release_date'=> '2024-05-20',
            ],
            [
                'name'         => 'Bad Apple',
                'category'     => 'Hacipupu',
                'series'       => 'A Night of Fantasy',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/bad-apple.png',
                'release_date'=> '2024-05-25',
            ],
            [
                'name'         => 'Jokers',
                'category'     => 'Hacipupu',
                'series'       => 'A Night of Fantasy',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/jokers.png',
                'release_date'=> '2024-05-30',
            ],
            [
                'name'         => 'Taichi Panda',
                'category'     => 'Dimoo',
                'series'       => 'Animal Kingdom',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/taichi-panda.png',
                'release_date'=> '2024-06-01',
            ],
            [
                'name'         => 'Baby Elephant Bubble',
                'category'     => 'Dimoo',
                'series'       => 'Animal Kingdom',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/baby-elephant-bubble.png',
                'release_date'=> '2024-06-05',
            ],
            [
                'name'         => 'Penguin Tango',
                'category'     => 'Dimoo',
                'series'       => 'Animal Kingdom',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/penguin-tango.png',
                'release_date'=> '2024-06-10',
            ],
            [
                'name'         => 'Monkey Catches the Moon',
                'category'     => 'Dimoo',
                'series'       => 'Animal Kingdom',
                'edition'      => 'Secret',
                'rarity'       => 'Very Rare',
                'image_url'    => '/img/figurines/monkey-catches-the-moon-secret.png',
                'release_date'=> '2024-06-15',
            ],
            [
                'name'         => 'Foodie Giraffe',
                'category'     => 'Dimoo',
                'series'       => 'Animal Kingdom',
                'edition'      => 'Standard',
                'rarity'       => 'Common',
                'image_url'    => '/img/figurines/foodie-giraffe.png',
                'release_date'=> '2024-06-20',
            ],
        ];

        // Insert in chunks of 100 for efficiency
        collect($data)
            ->chunk(100)
            ->each(function ($chunk) {
                DB::table('figurines')->insert($chunk->toArray());
            });
    }
}
