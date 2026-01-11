<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\StoreCommunityRequest;
use App\Http\Requests\Api\V1\UpdateCommunityRequest;
use App\Http\Resources\Api\V1\CommunityCollection;
use App\Http\Resources\Api\V1\CommunityResource;
use App\Services\Api\V1\CommunityService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class CommunityController extends Controller
{
    protected CommunityService $communityService;

    public function __construct(CommunityService $communityService)
    {
        $this->communityService = $communityService;
    }

    public function index(Request $request)
    {
        try {
            $communities = $request->boolean('is_active')
                ? $this->communityService->getActiveCommunities()
                : $this->communityService->getAllCommunities();

            return (new CommunityCollection($communities))
                ->additional([
                    'message' => 'Comunidades obtenidas exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo obtener las comunidades de la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener las comunidades', $e->getMessage(), 500);
        }
    }

    public function store(StoreCommunityRequest $request)
    {
        try {
            $community = $this->communityService->createCommunity($request->validated());

            return (new CommunityResource($community))
                ->additional([
                    'message' => 'Comunidad creada exitosamente',
                    'status_code' => 201
                ])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo crear la comunidad en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error('Error al crear la comunidad', $e->getMessage(), 500);
        }
    }

    public function show(int $id)
    {
        try {
            $community = $this->communityService->getCommunityById($id);

            return (new CommunityResource($community))
                ->additional([
                    'message' => 'Comunidad obtenida exitosamente',
                    'status_code' => 200
                ]);
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al obtener la comunidad',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function update(UpdateCommunityRequest $request, int $id)
    {
        try {
            $communityUpdate = $this->communityService->updateCommunity($id, $request->validated());

            return (new CommunityResource($communityUpdate))
                ->additional([
                    'message' => 'Comunidad actualizada exitosamente',
                    'status_code' => 200
                ])
                ->response();
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo actualizar la comunidad en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al actualizar la comunidad',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->communityService->deleteCommunity($id);

            return response()->json([
                'status_code' => 200,
                'message' => 'Comunidad eliminada exitosamente'
            ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo eliminar la comunidad en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al eliminar la comunidad',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function toggleStatus(int $id)
    {
        try {
            $community = $this->communityService->toggleCommunityStatus($id);

            return (new CommunityResource($community))
                ->additional([
                    'message' => 'Estado de la comunidad actualizado exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo actualizar el estado de la comunidad en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al actualizar el estado de la comunidad',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }
}
