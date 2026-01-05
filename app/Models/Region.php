<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Mutator para guardar el nombre en minúsculas
    protected function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }

    // Mutator para guardar la descripción en minúsculas
    protected function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = $value ? strtolower($value) : null;
    }
}
