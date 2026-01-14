<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RegionSeeder::class,
            MunicipalitySeeder::class,
            CommunitySeeder::class,
            LanguageSeeder::class,
        ]);
    }
}
