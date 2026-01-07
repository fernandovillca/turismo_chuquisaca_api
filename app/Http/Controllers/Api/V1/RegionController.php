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
use Illuminate\Database\QueryException;
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
            $regions = $request->boolean('is_active')
                ? $this->regionService->getActiveRegions()
                : $this->regionService->getAllRegions();

            return (new RegionCollection($regions))
                ->additional([
                    'message' => 'Regiones obtenidas exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo obtener las regiones de la base de datos',
                500
            );
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
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo crear la región en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error('Error al crear la región', $e->getMessage(), 500);
        }
    }

    public function show(int $id)
    {
        try {
            $region = $this->regionService->getRegionById($id);

            return (new RegionResource($region))
                ->additional([
                    'message' => 'Región obtenida exitosamente',
                    'status_code' => 200
                ]);
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al obtener la región',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function update(UpdateRegionRequest  $request, int $id)
    {
        try {
            $updatedRegion = $this->regionService->updateRegion($id, $request->validated());

            return (new RegionResource($updatedRegion))
                ->additional([
                    'message' => 'Región actualizada exitosamente',
                    'status_code' => 200
                ])
                ->response();
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo actualizar la región en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al actualizar la región',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->regionService->deleteRegion($id);

            return response()->json([
                'status_code' => 200,
                'message' => 'Región eliminada exitosamente'
            ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo eliminar la región en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al eliminar la región',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function toggleStatus(int $id)
    {
        try {
            $region = $this->regionService->toggleRegionStatus($id);

            return (new RegionResource($region))
                ->additional([
                    'message' => 'Estado de la región actualizado exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo actualizar el estado de la región en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al actualizar el estado de la región',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }
}
