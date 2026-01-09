<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class StoreCommunityRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'municipality_id' => 'required|integer|exists:municipalities,id',
            'name' => 'required|string|max:100|unique:communities,name',
            'short_description' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:150',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'municipality_id.required' => 'El municipio es obligatorio',
            'municipality_id.exists' => 'El municipio seleccionado no existe',
            'name.required' => 'El nombre de la comunidad es obligatorio',
            'name.unique' => 'Ya existe una comunidad con ese nombre',
            'short_description.max' => 'La descripción corta no puede exceder los 200 caracteres',
            'address.max' => 'La dirección no puede exceder los 150 caracteres',
            'is_active.boolean' => 'El campo is_active debe ser verdadero o falso'
        ];
    }
}
