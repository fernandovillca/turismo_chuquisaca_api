<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'code' => 'es',
                'name' => 'Español',
                'is_active' => true,
                'translate_automatically' => false,
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'is_active' => true,
                'translate_automatically' => true,
            ],
            [
                'code' => 'qu',
                'name' => 'Quechua',
                'is_active' => true,
                'translate_automatically' => false,
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
