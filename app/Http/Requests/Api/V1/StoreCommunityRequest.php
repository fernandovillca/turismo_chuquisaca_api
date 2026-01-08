<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:communities,name',
            'short_description' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:150',
            'is_active' => 'boolean'
        ];
    }
}
