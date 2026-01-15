<?php

namespace App\Repositories\Api\V1;

use App\Models\MunicipalityTranslation;

class MunicipalityTranslationRepository
{
    public function create(array $data): MunicipalityTranslation
    {
        return MunicipalityTranslation::create([
            'municipality_id' => $data['municipality_id'],
            'language_id' => $data['language_id'],
            'short_description' => $data['short_description'],
            'long_description' => $data['long_description'] ?? null,
            'address' => $data['address'],
        ]);
    }

    public function exists(int $municipalityId, string $locale): bool
    {
        return MunicipalityTranslation::where('municipality_id', $municipalityId)
            ->where('locale', $locale)
            ->exists();
    }
}
