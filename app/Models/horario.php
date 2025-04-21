<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'coordinador_cedula',
        'periodo_id',
        'asignatura_id',
        'carrera_id',
        'docente_id',
        'seccion_id',
        'turno_id',
        'semestre_id',
        'fecha', // Cambiado de `dia` a `fecha`
        'hora_inicio',
        'hora_fin',
    ];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id', 'asignatura_id');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'cedula_doc');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'id_turno');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id', 'id_semestre');
    }
}