<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateMunicipalityTranslationRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Agregar el language_code desde la ruta a los datos validables
        $this->merge([
            'language_code' => $this->route('languageCode'),
        ]);
    }

    public function rules(): array
    {
        return [
            'language_code' => [
                'required',
                'string',
                Rule::exists('languages', 'code')->where(function ($query) {
                    $query->where('is_active', true);
                }),
            ],
            'short_description' => 'required|string|max:200',
            'long_description' => 'nullable|string|max:5000',
            'address' => 'required|string|max:150',
        ];
    }

    public function messages(): array
    {
        return [
            'language_code.required' => 'El código de idioma es obligatorio',
            'language_code.exists' => 'El idioma seleccionado no existe o no está activo',
            'short_description.required' => 'La descripción corta es obligatoria',
            'short_description.max' => 'La descripción corta no puede tener más de 200 caracteres',
            'long_description.max' => 'La descripción larga no puede tener más de 5000 caracteres',
            'address.required' => 'La dirección es obligatoria',
            'address.max' => 'La dirección no puede tener más de 150 caracteres',
        ];
    }
}
