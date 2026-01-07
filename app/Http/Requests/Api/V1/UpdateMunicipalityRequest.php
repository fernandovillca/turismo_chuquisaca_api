<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateMunicipalityRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $municipalityId = $this->route('municipality');

        return [
            'region_id' => 'sometimes|integer|exists:regions,id',
            'name' => 'sometimes|string|max:100|unique:municipalities,name,' . $municipalityId,
            'short_description' => 'sometimes|string|max:200',
            'long_description' => 'nullable|string|max:5000',
            'image' => 'sometimes|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'region_id.exists' => 'La región seleccionada no existe',
            'name.unique' => 'Ya existe un municipio con ese nombre',
            'name.max' => 'El nombre no puede tener más de 100 caracteres',
            'short_description.max' => 'La descripción corta no puede tener más de 200 caracteres',
            'long_description.max' => 'La descripción larga no puede tener más de 5000 caracteres',
            'image.image' => 'El archivo debe ser una imagen',
            'image.mimes' => 'La imagen debe ser formato: jpeg, jpg, png o webp',
            'image.max' => 'La imagen no puede pesar más de 2MB',
            'is_active.boolean' => 'El campo is_active debe ser verdadero o falso'
        ];
    }
}
