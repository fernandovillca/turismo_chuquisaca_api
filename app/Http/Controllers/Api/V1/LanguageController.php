<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\StoreLanguageRequest;
use App\Http\Resources\Api\V1\LanguageCollection;
use App\Http\Resources\Api\V1\LanguageResource;
use App\Services\Api\V1\LanguageService;
use Exception;
use Illuminate\Database\QueryException;

class LanguageController extends Controller
{
    protected LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function index()
    {
        try {
            $languages = $this->languageService->getAllLanguages();

            return (new LanguageCollection($languages))
                ->additional([
                    'message' => 'Idiomas obtenidos exitosamente',
                    'status_code' => 200
                ]);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo obtener los idiomas de la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener los idiomas', $e->getMessage(), 500);
        }
    }

    public function store(StoreLanguageRequest $request)
    {
        try {
            $language = $this->languageService->createLanguage($request->validated());

            return (new LanguageResource($language))
                ->additional(['message' => 'Idioma creado exitosamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return ApiResponse::error('Error al crear el idioma', $e->getMessage(), 500);
        }
    }
}
