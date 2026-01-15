<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MunicipalityTranslation extends Model
{
    protected $fillable = [
        'municipality_id',
        'language_id',
        'name',
        'short_description',
        'long_description',
        'address'
    ];

    protected function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * Relación: Una traducción pertenece a un municipio
     */
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Relación: Una traducción pertenece a un idioma
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
