<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Municipality extends Model
{
    protected $fillable = [
        'region_id',
        'name',
        'short_description',
        'long_description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'region_id' => 'integer'
    ];

    protected function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }

    protected function setShortDescriptionAttribute($value): void
    {
        $this->attributes['short_description'] = strtolower($value);
    }

    protected function setLongDescriptionAttribute($value): void
    {
        $this->attributes['long_description'] = $value ? strtolower($value) : null;
    }

    /**
     * Relación: Un municipio pertenece a una región
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
