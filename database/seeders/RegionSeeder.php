<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            [
                'name' => 'norte',
                'description' => 'Es el corazón político y administrativo del departamento. Está situada en la zona de cabeceras de valle y serranías.',
                'is_active' => true,
            ],
            [
                'name' => 'centro',
                'description' => 'Es una zona de transición con una geografía accidentada, compuesta por valles y montañas.',
                'is_active' => true,
            ],
            [
                'name' => 'cintis',
                'description' => 'Ubicada al sur del departamento, se caracteriza por sus cañones profundos y valles estrechos con un clima templado.',
                'is_active' => true,
            ],
            [
                'name' => 'chaco',
                'description' => 'Se encuentra al este y es la región más extensa y cálida, formando parte del ecosistema del Gran Chaco Americano.',
                'is_active' => true,
            ],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
