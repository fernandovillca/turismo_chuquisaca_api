<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateLanguageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $languageId = $this->route('id');

        return [
            'code' => 'sometimes|string|size:2|unique:languages,code,' . $languageId . '|regex:/^[a-z]{2}$/',
            'name' => 'sometimes|string|max:50',
            'translate_automatically' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.size' => 'El código del idioma debe tener exactamente 2 caracteres',
            'code.unique' => 'Ya existe un idioma con ese código',
            'code.regex' => 'El código debe contener solo letras minúsculas (ISO 639-1)',
            'name.max' => 'El nombre no puede tener más de 50 caracteres',
            'translate_automatically.required' => 'El campo translate_automatically es obligatorio',
            'translate_automatically.boolean' => 'El campo translate_automatically debe ser verdadero o falso',
        ];
    }
}
