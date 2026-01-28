<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipalityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $languageCode = $request->query('locale');

        if ($languageCode && $this->relationLoaded('translation')) {
            return $this->formatWithSingleLanguage($languageCode);
        }

        return $this->formatWithAllLanguages();
    }

    /**
     * Formato con un solo idioma específico
     */
    private function formatWithSingleLanguage(string $languageCode): array
    {
        $translation = $this->translation;

        return [
            'id' => $this->id,
            'region_id' => $this->region_id,
            'name' => $this->name,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'image' => $this->image ? url($this->image) : null,
            'is_active' => $this->is_active,
            'locale' => $languageCode,
            'short_description' => $translation->short_description ?? null,
            'long_description' => $translation->long_description ?? null,
            'address' => $translation->address ?? null,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'region' => [
                'id' => $this->region->id,
                'name' => $this->region->name,
                'is_active' => $this->region->is_active,
            ],
            'communities' => $this->communities->map(function ($municipality) {
                return [
                    'id' => $municipality->id,
                    'name' => $municipality->name,
                    'is_active' => $municipality->is_active,
                ];
            }),
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => url($image->path),
                    'alt_text' => $image->alt_text,
                ];
            }),
        ];
    }

    /**
     * Formato con todos los idiomas disponibles
     * Muestra estructura completa con idiomas vacíos si no tienen traducción
     */
    private function formatWithAllLanguages(): array
    {
        $allLanguages = Language::get();

        $translations = [];
        foreach ($allLanguages as $language) {
            $translations[$language->code] = [
                'short_description' => null,
                'long_description' => null,
                'address' => null,
                'language' => [
                    'id' => $language->id,
                    'code' => $language->code,
                    'name' => $language->name,
                ]
            ];
        }

        if ($this->relationLoaded('translations')) {
            foreach ($this->translations as $translation) {
                $code = $translation->language->code;
                $translations[$code] = [
                    'short_description' => $translation->short_description,
                    'long_description' => $translation->long_description,
                    'address' => $translation->address,
                    'language' => [
                        'id' => $translation->language->id,
                        'code' => $translation->language->code,
                        'name' => $translation->language->name,
                    ]
                ];
            }
        }

        return [
            'id' => $this->id,
            'region_id' => $this->region_id,
            'name' => $this->name,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'image' => $this->image ? url($this->image) : null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'translations' => $translations,
            'region' => [
                'id' => $this->region->id,
                'name' => $this->region->name,
                'is_active' => $this->region->is_active,
            ],
            'communities' => $this->communities->map(function ($municipality) {
                return [
                    'id' => $municipality->id,
                    'name' => $municipality->name,
                    'is_active' => $municipality->is_active,
                ];
            }),
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => url($image->path),
                    'alt_text' => $image->alt_text,
                ];
            }),
        ];
    }
}
