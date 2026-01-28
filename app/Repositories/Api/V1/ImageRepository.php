<?php

namespace App\Repositories\Api\V1;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class ImageRepository
{
    /**
     * Crear una imagen asociada a un modelo polimórfico
     *
     * @param Model $imageable Modelo relacionado (Municipality, Region, etc.)
     * @param array $data Datos de la imagen
     * @return Image
     */
    public function create(Model $imageable, array $data): Image
    {
        return $imageable->images()->create([
            'path' => $data['path'],
            'alt_text' => $data['alt_text'] ?? null,
        ]);
    }

    /**
     * Eliminar una imagen
     *
     * @param Image $image
     * @return bool
     */
    public function delete(Image $image): bool
    {
        return $image->delete();
    }
}
