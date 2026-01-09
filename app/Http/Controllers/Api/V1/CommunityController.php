<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\StoreCommunityRequest;
use App\Http\Resources\Api\V1\CommunityCollection;
use App\Http\Resources\Api\V1\CommunityResource;
use App\Services\Api\V1\CommunityService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;

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

    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
