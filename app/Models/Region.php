<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }

    protected function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = $value ? strtolower($value) : null;
    }

    /**
     * Relación: Una región tiene muchos municipios
     */
    public function municipalities(): HasMany
    {
        return $this->hasMany(Municipality::class);
    }
}
