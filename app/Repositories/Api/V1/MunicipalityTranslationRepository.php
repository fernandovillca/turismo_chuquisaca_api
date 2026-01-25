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

    /**
     * Verificar si existe una traducción para un municipio en un idioma específico
     *
     * @param int $municipalityId ID del municipio
     * @param int $languageId ID del idioma
     * @return bool
     */
    public function translationExists(int $municipalityId, int $languageId): bool
    {
        return MunicipalityTranslation::where('municipality_id', $municipalityId)
            ->where('language_id', $languageId)
            ->exists();
    }

    /**
     * Actualizar una traducción existente de un municipio
     *
     * @param int $municipalityId ID del municipio
     * @param int $languageId ID del idioma
     * @param array $translationData Datos de la traducción
     * @return bool
     */
    public function updateExistingTranslation(int $municipalityId, int $languageId, array $translationData): bool
    {
        return MunicipalityTranslation::where('municipality_id', $municipalityId)
            ->where('language_id', $languageId)
            ->update([
                'short_description' => $translationData['short_description'],
                'long_description' => $translationData['long_description'] ?? null,
                'address' => $translationData['address'],
            ]);
    }
}
