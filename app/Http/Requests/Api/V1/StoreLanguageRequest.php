<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class StoreLanguageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|size:2|unique:languages,code|regex:/^[a-z]{2}$/',
            'name' => 'required|string|max:50',
            'translate_automatically' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'El código del idioma es obligatorio',
            'code.size' => 'El código del idioma debe tener exactamente 2 caracteres',
            'code.unique' => 'Ya existe un idioma con ese código',
            'code.regex' => 'El código debe contener solo letras minúsculas (ISO 639-1)',
            'name.required' => 'El nombre del idioma es obligatorio',
            'name.max' => 'El nombre no puede tener más de 50 caracteres',
            'translate_automatically.boolean.required' => 'El campo translate_automatically es obligatorio',
            'translate_automatically.boolean' => 'El campo translate_automatically debe ser verdadero o falso',
        ];
    }
}
