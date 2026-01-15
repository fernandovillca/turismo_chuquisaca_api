<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipality extends Model
{
    protected $fillable = [
        'region_id',
        'latitud',
        'longitud',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'region_id' => 'integer',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    /**
     * Relación: Un municipio pertenece a una región
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Relación: Un municipio tiene muchas comunidades
     */
    public function communities()
    {
        return $this->hasMany(Community::class);
    }

    /**
     * Relación: Un municipio tiene muchas traducciones
     */
    public function translations(): HasMany
    {
        return $this->hasMany(MunicipalityTranslation::class);
    }

    /**
     * Relación: Obtener traducción por código de idioma
     */
    public function translation(string $languageCode = 'es')
    {
        return $this->hasOne(MunicipalityTranslation::class)
            ->whereHas('language', function ($query) use ($languageCode) {
                $query->where('code', $languageCode);
            });
    }
}
