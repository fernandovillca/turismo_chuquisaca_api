<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Municipality extends Model
{
    protected $fillable = [
        'region_id',
        'name',
        'short_description',
        'long_description',
        'address',
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

    protected function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }

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
     * Relación con CulturalEtiquette
     * Un municipio puede tener varios códigos culturales
     */
    public function culturalEtiquettes()
    {
        return $this->hasMany(CulturalEtiquette::class);
    }

    /**
     * Relación: Un municipio tiene muchas traducciones
     */
    // public function translations(): HasMany
    // {
    //     return $this->hasMany(MunicipalityTranslation::class);
    // }

    /**
     * Relación: Obtener traducción por código de idioma
     */
    // public function translation(string $languageCode = 'es')
    // {
    //     return $this->hasOne(MunicipalityTranslation::class)
    //         ->whereHas('language', function ($query) use ($languageCode) {
    //             $query->where('code', $languageCode);
    //         });
    // }

    /**
     * Relación polimórfica: Un municipio puede tener muchas imágenes
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
