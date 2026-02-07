<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipalityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'region_id' => $this->region_id,
            'name' => $this->name,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'image' => $this->image ? url($this->image) : null,
            'is_active' => $this->is_active,
            'short_description' => $this->short_description ?? null,
            'long_description' => $this->long_description ?? null,
            'address' => $this->address ?? null,
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
}
