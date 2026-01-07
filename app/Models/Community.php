<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Community extends Model
{
    protected $fillable = [
        'municipality_id',
        'name',
        'short_description',
        'address',
        'is_active',
    ];

    protected $casts = [
        'municipality_id' => 'integer',
        'is_active' => 'boolean',
    ];

    protected function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }

    protected function setShortDescriptionAttribute($value): void
    {
        $this->attributes['short_description'] =  $value ? strtolower($value) : null;
    }

    /**
     * Relación: Una comunidad pertenece a un municipio
     */
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
}
