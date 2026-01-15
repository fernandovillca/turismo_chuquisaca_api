<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MunicipalityTranslation extends Model
{
    protected $fillable = [
        'municipality_id',
        'language_id',
        'short_description',
        'long_description',
        'address'
    ];

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
