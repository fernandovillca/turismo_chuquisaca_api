<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\StoreImageRequest;
use App\Http\Resources\Api\V1\ImageResource;
use App\Services\Api\V1\ImageService;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Database\QueryException;

class ImageController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function storeMunicipality(StoreImageRequest $request, int $id): JsonResponse
    {
        return $this->storeByType('municipality', $id, $request);
    }

    protected function storeByType(string $type, int $id, StoreImageRequest $request): JsonResponse
    {
        try {
            $image = $this->imageService->addImageByType(
                $type,
                $id,
                $request->file('image'),
                $request->alt_text
            );

            return (new ImageResource($image))
                ->additional([
                    'message' => 'Imagen subida correctamente',
                    'status_code' => 201
                ])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo agregar la imagen en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error('Error al agregar la imagen', $e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->imageService->deleteImageById($id);

            return response()->json([
                'status_code' => 200,
                'message' => 'Imagen eliminada correctamente'
            ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo eliminar la imagen en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al eliminar la imagen',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }
}
