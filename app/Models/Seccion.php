<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $table = 'secciones';
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

    // Relaciones corregidas
    public function aula() {
        return $this->belongsTo(Aula::class, 'aula_id', 'id');
    }

    public function carrera() {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'carrera_id');
    }

    public function turno() {
        return $this->belongsTo(Turno::class, 'turno_id', 'id_turno');
    }

    public function semestre() {
        return $this->belongsTo(Semestre::class, 'semestre_id', 'id_semestre');
    }

    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'asignatura_seccion', 'seccion_id', 'asignatura_id')
                    ->withPivot(['carrera_id', 'semestre_id']);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'seccion_id', 'codigo_seccion');
    }
}