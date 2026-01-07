<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\StoreMunicipalityRequest;
use App\Http\Requests\Api\V1\UpdateMunicipalityRequest;
use App\Http\Resources\Api\V1\MunicipalityResource;
use App\Services\Api\V1\MunicipalityService;
use Exception;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    protected MunicipalityService $municipalityService;

    public function __construct(MunicipalityService $municipalityService)
    {
        $this->municipalityService = $municipalityService;
    }

    public function index()
    {
        /*

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
        */
    }


    public function store(StoreMunicipalityRequest $request)
    {
        try {
            $data = $request->except('image');
            $image = $request->file('image');

            $municipality = $this->municipalityService->createMunicipality($data, $image);;
            return (new MunicipalityResource($municipality))
                ->additional([
                    'message' => 'Municipio creado exitosamente',
                    'status_code' => 201
                ])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return ApiResponse::error('Error al crear el municipio', $e->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function update(UpdateMunicipalityRequest $request, int $id)
    {
        return $request;
        try {

            $municipality = $this->municipalityService->getMunicipalityById($id);

            if (!$municipality) {
                return ApiResponse::notFound('El municipio con ID ' . $id . ' no existe');
            }

            $data = $request->except('image');
            $image = $request->hasFile('image') ? $request->file('image') : null;

            $updatedMunicipality = $this->municipalityService->updateMunicipality($municipality, $data, $image);
            return (new MunicipalityResource($updatedMunicipality))
                ->additional([
                    'message' => 'Municipio actualizado exitosamente',
                    'status_code' => 200
                ]);
        } catch (Exception $e) {
            return ApiResponse::error('Error al actualizar el municipio', $e->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
