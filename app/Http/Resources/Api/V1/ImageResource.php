<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'alt_text' => $this->alt_text,
            'imageable_type' => $this->imageable_type,
            'imageable_id' => $this->imageable_id,
            'path' => url($this->path),
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
