<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateCommunityRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $communityId = $this->route('community');

        return [
            'municipality_id' => 'sometimes|integer|exists:municipalities,id',
            'name' => 'sometimes|string|max:100|unique:communities,name,' . $communityId,
            'short_description' => 'sometimes|nullable|string|max:200',
            'address' => 'sometimes|nullable|string|max:150',
            'is_active' => 'sometimes|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'municipality_id.exists' => 'El municipio seleccionado no existe',
            'name.unique' => 'Ya existe una comunidad con ese nombre',
            'name.max' => 'El nombre no puede tener más de 100 caracteres',
            'short_description.max' => 'La descripción corta no puede exceder los 200 caracteres',
            'address.max' => 'La dirección no puede exceder los 150 caracteres',
            'is_active.boolean' => 'El campo is_active debe ser verdadero o falso'
        ];
    }
}
