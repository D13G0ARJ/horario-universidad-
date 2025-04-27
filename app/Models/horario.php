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

    // Relación con Asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id', 'asignatura_id');
    }

    // Relación con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'cedula_doc');
    }

    // Relación con Turno
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'id_turno');
    }

    // Relación con Semestre
    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id', 'id_semestre');
    }

    // Relación con Periodo
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    }

    // Relación con Carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'carrera_id');
    }

    // Relación con Sección
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'codigo_seccion');
    }
}