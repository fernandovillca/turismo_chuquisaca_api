<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\StoreMunicipalityRequest;
use App\Http\Requests\Api\V1\UpdateMunicipalityRequest;
use App\Http\Resources\Api\V1\MunicipalityCollection;
use App\Http\Resources\Api\V1\MunicipalityResource;
use App\Services\Api\V1\MunicipalityService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    protected MunicipalityService $municipalityService;

    public function __construct(MunicipalityService $municipalityService)
    {
        $this->municipalityService = $municipalityService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = min($request->query('per_page', 10), 100);

            $municipalities = $request->boolean('is_active')
                ? $this->municipalityService->getActiveMunicipalities($perPage)
                : $this->municipalityService->getAllMunicipalities($perPage);

            return (new MunicipalityCollection($municipalities))
                ->additional([
                    'message' => 'Municipios obtenidos exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo obtener las municipios de la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al obtener municipios',
                $e->getMessage(),
                500
            );
        }
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
        try {
            $municipality = $this->municipalityService
                ->getMunicipalityById($id);

            return (new MunicipalityResource($municipality))
                ->additional([
                    'message' => 'Municipio obtenido exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo obtener el municipio en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al obtener el municipio',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function update(UpdateMunicipalityRequest $request, int $id)
    {
        try {
            $data = $request->except('image');
            $image = $request->hasFile('image')
                ? $request->file('image')
                : null;

            $updatedMunicipality = $this->municipalityService
                ->updateMunicipality($id, $data, $image);

            return (new MunicipalityResource($updatedMunicipality))
                ->additional([
                    'message' => 'Municipio actualizado exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo actualizar el municipio en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al actualizar el municipio',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->municipalityService->deleteMunicipality($id);

            return response()->json([
                'status_code' => 200,
                'message' => 'Municipio eliminado exitosamente'
            ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo eliminar el municipio en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al eliminar el municipio',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function toggleStatus(int $id)
    {
        try {
            $municipality = $this->municipalityService->toggleMunicipalityStatus($id);

            return (new MunicipalityResource($municipality))
                ->additional([
                    'message' => 'Estado del municipio actualizado exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo actualizar el estado del municipio en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Error al actualizar el estado del municipio',
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }
}
