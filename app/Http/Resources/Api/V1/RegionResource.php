<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'), // Formato: 05/01/2026 14:30:45
            'municipalities' => $this->municipalities->map(function ($municipality) {
                return [
                    'id' => $municipality->id,
                    'name' => $municipality->name,
                    'short_description' => $municipality->short_description,
                    'long_description' => $municipality->long_description,
                    'latitud' => $municipality->latitud,
                    'longitud' => $municipality->longitud,
                    'address' => $municipality->address,
                    'image' => url($municipality->image),
                    'is_active' => $municipality->is_active,
                    'communities' => $municipality->communities->map(function ($community) {
                        return [
                            'id' => $community->id,
                            'name' => $community->name,
                            'short_description' => $community->short_description,
                            'address' => $community->address,
                            'is_active' => $community->is_active,
                        ];
                    }),
                ];
            }),
        ];
    }
}
