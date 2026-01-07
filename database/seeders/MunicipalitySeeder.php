<?php

namespace Database\Seeders;

use App\Models\Municipality;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MunicipalitySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        $regions = [
            'norte' => [
                'Sucre',
                'Yotala',
                'Poroma',
            ],
            'centro' => [
                'Tarabuco',
                'Zudáñez',
                'Yamparáez',
                'Icla',
            ],
            'cintis' => [
                'Camargo',
                'San Lucas',
                'Villa Abecia',
                'Culpina',
            ],
            'chaco' => [
                'Monteagudo',
                'Muyupampa',
                'Macharetí',
            ],
        ];

        foreach ($regions as $regionName => $municipalities) {
            $region = Region::where('name', $regionName)->first();

            if (!$region) {
                continue;
            }

            foreach ($municipalities as $municipalityName) {
                Municipality::create([
                    'region_id' => $region->id,
                    'name' => $municipalityName,
                    'short_description' => $faker->sentence(6),
                    'long_description' => $faker->paragraph(3),
                    'latitud' => $faker->latitude(-22, -18),
                    'longitud' => $faker->longitude(-66, -62),
                    'address' => $faker->address(),
                    'image' => 'images/municipalities/default.jpg',
                    'is_active' => true,
                ]);
            }
        }
    }
}
