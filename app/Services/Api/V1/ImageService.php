<?php

namespace App\Services\Api\V1;

use App\Models\Image;
use App\Repositories\Api\V1\ImageRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Exception;

class ImageService
{
    protected ImageRepository $imageRepository;

    protected array $imageableMap = [
        'municipality' => \App\Models\Municipality::class,
    ];

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function addImageByType(
        string $type,
        int $id,
        UploadedFile $file,
        ?string $altText = null
    ): Image {
        $imageable = $this->resolveImageable($type, $id);

        return $this->addImage($imageable, $file, $altText);
    }

    /**
     * Agregar imagen a un modelo polimórfico
     *
     * @param Model $imageable Modelo relacionado (Municipality, etc.)
     * @param UploadedFile $file Archivo de imagen
     * @param string|null $altText Texto alternativo
     * @return Image
     */
    public function addImage(
        Model $imageable,
        UploadedFile $file,
        ?string $altText = null
    ): Image {
        return DB::transaction(function () use ($imageable, $file, $altText) {

            $path = $this->storeImage($file, $imageable);

            return $this->imageRepository->create($imageable, [
                'path' => $path,
                'alt_text' => $altText,
            ]);
        });
    }

    /**
     * Eliminar una imagen por su ID
     *
     * @param int $imageId
     * @return bool
     * @throws Exception
     */
    public function deleteImageById(int $imageId): bool
    {
        /** * todo: corregir esta linea para evitar la conexion a la base de datos desde aqui */
        $image = Image::find($imageId);

        if (!$image) {
            throw new Exception("Imagen con ID {$imageId} no encontrada", 404);
        }

        $fullPath = public_path($image->path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        return $this->imageRepository->delete($image);
    }


    /**
     * Guardar imagen en el sistema de archivos
     */
    protected function storeImage(UploadedFile $file, Model $imageable): string
    {
        $folder = $this->resolveFolder($imageable);

        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path("images/{$folder}"), $fileName);

        return "images/{$folder}/{$fileName}";
    }

    /**
     * Resolver carpeta según el modelo polimórfico
     *
     * Municipality → municipalities
     */
    protected function resolveFolder(Model $imageable): string
    {
        return match (class_basename($imageable)) {
            'Municipality' => 'municipalities',
            default => 'others',
        };
    }

    protected function resolveImageable(string $type, int $id): Model
    {
        if (!isset($this->imageableMap[$type])) {
            throw new Exception("Tipo de imagen no soportado", 400);
        }

        $modelClass = $this->imageableMap[$type];

        /** * todo: corregir esta linea para evitar la conexion a la base de datos desde aqui */
        $model = $modelClass::find($id);

        if (!$model) {
            throw new Exception("Registro no encontrado", 404);
        }

        return $model;
    }
}
