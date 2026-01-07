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
            'region' => [
                'id' => $this->region->id,
                'name' => $this->region->name,
            ],
            'name' => $this->name,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'image' => url($this->image),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
