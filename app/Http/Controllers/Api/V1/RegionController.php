<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\StoreRegionRequest;
use App\Http\Requests\Api\V1\UpdateRegionRequest;
use App\Http\Resources\Api\V1\RegionCollection;
use App\Http\Resources\Api\V1\RegionResource;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(Request $request): RegionCollection
    {
        $perPage = min($request->input('per_page', 10), 100);

        $regions = Region::paginate($perPage);

        return (new RegionCollection($regions))
            ->additional([
                'message' => 'Regions retrieved successfully',
                'status_code' => 200
            ]);
    }

    public function store(StoreRegionRequest $request): JsonResponse
    {
        try {
            $region = Region::create($request->validated());

            return (new RegionResource($region))
                ->additional([
                    'message' => 'Region created successfully',
                    'status_code' => 201
                ])
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al crear la región', $e->getMessage(), 500);
        }
    }

    public function show(Region $region): RegionResource
    {
        return (new RegionResource($region))
            ->additional([
                'message' => 'Region retrieved successfully',
                'status_code' => 200
            ]);
    }

    public function update(UpdateRegionRequest  $request, Region $region): JsonResponse
    {
        try {
            $region->update($request->validated());

            return (new RegionResource($region))
                ->additional([
                    'message' => 'Region updated successfully',
                    'status_code' => 200
                ])
                ->response();
        } catch (\Exception $e) {
            return ApiResponse::error('Error al actualizar la región', $e->getMessage(), 500);
        }
    }

    public function destroy(Region $region): JsonResponse
    {
        try {
            $region->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Region deleted successfully'
            ]);
        } catch (\Exception $e) {
            return ApiResponse::conflict('No se puede eliminar la región porque tiene registros relacionados');
        }
    }
}
