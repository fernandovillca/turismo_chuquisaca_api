<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Municipality;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        $data = [
            'sucre' => [
                'Chuqui Chuqui',
                'Potolo',
                'Maragua',
                'Quila Quila',
                'Chaunaca',
            ],
            'yotala' => [
                'Huayllas',
                'Pulqui',
                'Tuero',
                'Totora',
            ],
            'poroma' => [
                'Pojpo',
                'Sapse',
                'Sijcha',
                'Copavillkhi',
            ],
            'tarabuco' => [
                'Candelaria',
                'Pampa Lupiola',
                'Vila Vila',
            ],
            'zudáñez' => [
                'Calle Calle',
                'Mojotoro',
                'Thola Mayu',
            ],
            'yamparáez' => [
                'Sotomayor',
                'Escana',
                'Lavadero',
            ],
            'icla' => [
                'Jatun Mayu',
                'Lagunillas',
            ],
            'camargo' => [
                'San Pedro',
                'Lintaca',
                'Tacaquira',
            ],
            'san lucas' => [
                'Chinimayu',
                'Malliri',
                'Pirhuani',
            ],
            'villa abecia' => [
                'Charpaxi',
                'Higueras',
            ],
            'culpina' => [
                'El Palmar',
                'La Cueva',
            ],
            'monteagudo' => [
                'Candua',
                'San Juan del Piraí',
                'Fernández',
            ],
            'muyupampa' => [
                'Iguembe',
                'Iticuasu',
            ],
            'macharetí' => [
                'Carandaytí',
                'Tiguipa',
            ],
        ];

        foreach ($data as $municipalityName => $communities) {
            $municipality = Municipality::where('name', $municipalityName)->first();

            if (!$municipality) {
                continue;
            }

            foreach ($communities as $communityName) {
                Community::create([
                    'municipality_id' => $municipality->id,
                    'name' => $communityName,
                    'short_description' => $faker->sentence(6),
                    'address' => $faker->address(),
                    'is_active' => true,
                ]);
            }
        }
    }
}
