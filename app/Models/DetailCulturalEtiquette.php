<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailCulturalEtiquette extends Model
{
    protected $fillable = [
        'cultural_etiquette_id',
        'name_detail',
        'detail',
    ];

    /**
     * Relación con CulturalEtiquette
     */
    public function culturalEtiquette()
    {
        return $this->belongsTo(CulturalEtiquette::class);
    }
}
