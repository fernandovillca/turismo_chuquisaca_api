<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CulturalEtiquette;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CulturalEtiquetteController extends Controller
{
    public function index($municipality_id)
    {
        try {
            // Verificar que el municipio existe
            $municipality = Municipality::find($municipality_id);

            if (!$municipality) {
                return response()->json([
                    'success' => false,
                    'message' => 'Municipio no encontrado'
                ], 404);
            }

            // Obtener todos los códigos culturales con sus detalles
            $culturalEtiquettes = CulturalEtiquette::where('municipality_id', $municipality_id)
                ->with('details') // Eager loading de los detalles
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'municipality' => [
                        'id' => $municipality->id,
                        'name' => $municipality->name
                    ],
                    'cultural_etiquettes' => $culturalEtiquettes
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los códigos culturales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $municipality_id)
    {
        try {
            // Verificar que el municipio existe
            $municipality = Municipality::find($municipality_id);

            if (!$municipality) {
                return response()->json([
                    'success' => false,
                    'message' => 'Municipio no encontrado'
                ], 404);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'details' => 'required|array|min:1',
                'details.*.name_detail' => 'required|string|max:255',
                'details.*.detail' => 'required|string'
            ], [
                'title.required' => 'El título es obligatorio',
                'details.required' => 'Debe proporcionar al menos un detalle',
                'details.*.name_detail.required' => 'El nombre del detalle es obligatorio',
                'details.*.detail.required' => 'La descripción del detalle es obligatoria'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Usar transacción para asegurar que todo se guarde correctamente
            DB::beginTransaction();

            // Crear el código cultural
            $culturalEtiquette = CulturalEtiquette::create([
                'municipality_id' => $municipality_id,
                'title' => $request->title
            ]);

            // Crear los detalles
            foreach ($request->details as $detail) {
                $culturalEtiquette->details()->create([
                    'name_detail' => $detail['name_detail'],
                    'detail' => $detail['detail']
                ]);
            }

            DB::commit();

            // Cargar los detalles para la respuesta
            $culturalEtiquette->load('details');

            return response()->json([
                'success' => true,
                'message' => 'Código cultural creado exitosamente',
                'data' => $culturalEtiquette
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el código cultural',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $culturalEtiquette = CulturalEtiquette::with('details')->find($id);

            if (!$culturalEtiquette) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código cultural no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $culturalEtiquette
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el código cultural',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $culturalEtiquette = CulturalEtiquette::find($id);

            if (!$culturalEtiquette) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código cultural no encontrado'
                ], 404);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'details' => 'required|array|min:1',
                'details.*.name_detail' => 'required|string|max:255',
                'details.*.detail' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Actualizar el título
            $culturalEtiquette->update([
                'title' => $request->title
            ]);

            // Obtener IDs de detalles que vienen en la petición
            $detailIds = collect($request->details)
                ->pluck('id')
                ->filter()
                ->toArray();

            // Eliminar detalles que no están en la petición
            $culturalEtiquette->details()
                ->whereNotIn('id', $detailIds)
                ->delete();

            // Actualizar o crear detalles
            foreach ($request->details as $detail) {
                if (isset($detail['id'])) {
                    // Actualizar detalle existente
                    $culturalEtiquette->details()
                        ->where('id', $detail['id'])
                        ->update([
                            'name_detail' => $detail['name_detail'],
                            'detail' => $detail['detail']
                        ]);
                } else {
                    // Crear nuevo detalle
                    $culturalEtiquette->details()->create([
                        'name_detail' => $detail['name_detail'],
                        'detail' => $detail['detail']
                    ]);
                }
            }

            DB::commit();

            // Recargar los detalles
            $culturalEtiquette->load('details');

            return response()->json([
                'success' => true,
                'message' => 'Código cultural actualizado exitosamente',
                'data' => $culturalEtiquette
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el código cultural',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $culturalEtiquette = CulturalEtiquette::find($id);

            if (!$culturalEtiquette) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código cultural no encontrado'
                ], 404);
            }

            $culturalEtiquette->delete();

            return response()->json([
                'success' => true,
                'message' => 'Código cultural eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el código cultural',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
