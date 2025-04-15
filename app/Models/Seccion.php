<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $primaryKey = 'codigo_seccion';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo_seccion',
        'aula_id',
        'carrera_id',
        'turno_id',
        'semestre_id'
    ];

    // Relaciones
    public function aula() {
        return $this->belongsTo(Aula::class);
    }

    public function carrera() {
        return $this->belongsTo(Carrera::class);
    }

    public function turno() {
        return $this->belongsTo(Turno::class);
    }

    public function semestre() {
        return $this->belongsTo(Semestre::class);
    }
}