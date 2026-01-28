<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class StoreImageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'La imagen es obligatoria',
            'image.image' => 'El archivo debe ser una imagen válida',
            'image.mimes' => 'Solo se permiten imágenes jpg, jpeg, png o webp',
            'image.max' => 'La imagen no debe superar los 2MB',
            'alt_text.string' => 'El texto alternativo debe ser una cadena de texto',
            'alt_text.max' => 'El texto alternativo no debe superar los 255 caracteres',
        ];
    }
}
