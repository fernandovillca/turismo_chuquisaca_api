<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\StoreRegionRequest;
use App\Http\Requests\Api\V1\UpdateRegionRequest;
use App\Http\Resources\Api\V1\RegionCollection;
use App\Http\Resources\Api\V1\RegionResource;
use App\Models\Region;
use App\Services\Api\V1\RegionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    protected RegionService $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = min($request->input('per_page', 10), 100);

            $regions = $this->regionService->getAllRegions($perPage);

            return (new RegionCollection($regions))
                ->additional([
                    'message' => 'Regiones obtenidas exitosamente',
                    'status_code' => 200
                ]);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener las regiones', $e->getMessage(), 500);
        }
    }

    public function store(StoreRegionRequest $request): JsonResponse
    {
        try {
            $region = $this->regionService->createRegion($request->validated());

            return (new RegionResource($region))
                ->additional([
                    'message' => 'Región creada exitosamente',
                    'status_code' => 201
                ])
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al crear la región', $e->getMessage(), 500);
        }
    }

    public function show(int $id)
    {
        try {
            $region = $this->findRegionOrFail($id);

            if ($region instanceof JsonResponse) return $region;

            return (new RegionResource($region))
                ->additional([
                    'message' => 'Región obtenida exitosamente',
                    'status_code' => 200
                ]);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener la región', $e->getMessage(), 500);
        }
    }

    public function update(UpdateRegionRequest  $request, int $id)
    {
        try {
            $region = $this->findRegionOrFail($id);

            if ($region instanceof JsonResponse) return $region;

            $updatedRegion = $this->regionService->updateRegion($region, $request->validated());

            return (new RegionResource($updatedRegion))
                ->additional([
                    'message' => 'Región actualizada exitosamente',
                    'status_code' => 200
                ])
                ->response();
        } catch (\Exception $e) {
            return ApiResponse::error('Error al actualizar la región', $e->getMessage(), 500);
        }
    }

    public function destroy(Int $id): JsonResponse
    {
        try {
            $region = $this->regionService->getRegionById($id);

            if (!$region) return ApiResponse::notFound('La región con ID ' . $id . ' no existe');

            $this->regionService->deleteRegion($region);

            return response()->json([
                'status_code' => 200,
                'message' => 'Región eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return ApiResponse::conflict('No se puede eliminar la región porque tiene registros relacionados');
        }
    }

    private function findRegionOrFail(int $id): Region|JsonResponse
    {
        $region = $this->regionService->getRegionById($id);

        if (!$region) {
            return ApiResponse::notFound('La región con ID ' . $id . ' no existe');
        }

        return $region;
    }
}
