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
     * Eliminar una imagen
     *
     * @param Image $image
     * @return bool
     */
    public function deleteImage(Image $image): bool
    {
        return DB::transaction(function () use ($image) {

            $this->deletePhysicalImage($image->path);

            return $this->imageRepository->delete($image);
        });
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

    /**
     * Eliminar imagen física
     */
    protected function deletePhysicalImage(string $path): void
    {
        $fullPath = public_path($path);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    protected function resolveImageable(string $type, int $id): Model
    {
        if (!isset($this->imageableMap[$type])) {
            throw new Exception("Tipo de imagen no soportado", 400);
        }

        $modelClass = $this->imageableMap[$type];

        $model = $modelClass::find($id);

        if (!$model) {
            throw new Exception("Registro no encontrado", 404);
        }

        return $model;
    }
}
