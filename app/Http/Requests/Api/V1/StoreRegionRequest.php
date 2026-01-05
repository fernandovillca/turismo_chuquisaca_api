<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class StoreRegionRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:regions,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la región es obligatorio',
            'name.unique' => 'Ya existe una región con ese nombre',
            'name.max' => 'El nombre no puede tener más de 100 caracteres',
            'description.max' => 'La descripción no puede tener más de 1000 caracteres',
            'is_active.boolean' => 'El campo is_active debe ser verdadero o falso'
        ];
    }
}
