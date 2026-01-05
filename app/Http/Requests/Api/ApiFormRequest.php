<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiFormRequest extends FormRequest
{
    /**
     * Manejo de validación fallida para APIs (retorna JSON)
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status_code' => 422,
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    /**
     * Manejo de autorización fallida
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'status_code' => 403,
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403)
        );
    }
}
