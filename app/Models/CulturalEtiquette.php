<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulturalEtiquette extends Model
{
    protected $fillable = [
        'municipality_id',
        'title'
    ];

    /**
     * Relación con Municipality
     */
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Relación con DetailCulturalEtiquette
     */
    public function details()
    {
        return $this->hasMany(DetailCulturalEtiquette::class);
    }
}
