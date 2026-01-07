<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class StoreMunicipalityRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'region_id' => 'required|integer|exists:regions,id',
            'name' => 'required|string|max:100|unique:municipalities,name',
            'short_description' => 'required|string|max:200',
            'long_description' => 'nullable|string|max:5000',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'region_id.required' => 'La región es obligatoria',
            'region_id.exists' => 'La región seleccionada no existe',
            'name.required' => 'El nombre del municipio es obligatorio',
            'name.unique' => 'Ya existe un municipio con ese nombre',
            'short_description.required' => 'La descripción corta es obligatoria',
            'image.required' => 'La imagen es obligatoria',
            'image.image' => 'El archivo debe ser una imagen',
            'image.mimes' => 'La imagen debe ser formato: jpeg, jpg, png o webp',
            'image.max' => 'La imagen no puede pesar más de 2MB',
            'is_active.boolean' => 'El campo is_active debe ser verdadero o falso'
        ];
    }
}
