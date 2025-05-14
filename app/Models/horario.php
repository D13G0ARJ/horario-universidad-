<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'coordinador_cedula',
        'periodo_id',
        'asignatura_id',
        'carrera_id',
        'docente_id',
        'seccion_id',
        'turno_id',
        'semestre_id',
        'dia_semana',    // Campo para días de la semana (1-6)
        'fecha',         // Fecha específica para horarios no recurrentes
        'hora_inicio',
        'hora_fin',
        'tipo_horas',    // Campo añadido: teorica/practica/laboratorio
        'bloques',       // Campo añadido: número de bloques de 45 minutos
        'activo',        // Estado del horario
        'observaciones'  // Comentarios adicionales
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dia_semana' => 'integer',
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'activo' => 'boolean',
    ];

    /**
     * Relación con Asignatura
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id', 'asignatura_id')
            ->with('cargaHoraria');
    }

    /**
     * Relación con Docente
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'cedula_doc')
            ->withDefault([
                'nombre' => 'Docente no asignado'
            ]);
    }

    /**
     * Relación con Turno
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'id_turno');
    }

    /**
     * Relación con Semestre
     */
    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id', 'id_semestre')
            ->with('carrera');
    }

    /**
     * Relación con Periodo
     */
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id')
            ->select('id', 'nombre', 'fecha_inicio', 'fecha_fin');
    }

    /**
     * Relación con Carrera
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'carrera_id')
            ->with('semestres');
    }

    /**
     * Relación con Sección
     */
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'codigo_seccion')
            ->with(['asignaturas', 'turno']);
    }

    /**
     * Relación con Usuario (Coordinador)
     */
    public function coordinador()
    {
        return $this->belongsTo(User::class, 'coordinador_cedula', 'cedula')
            ->select('cedula', 'nombre', 'apellido');
    }

    /**
     * Scope para horarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para horarios por día de la semana
     */
    public function scopePorDia($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    /**
     * Obtiene la duración total en minutos
     */
    public function getDuracionTotalAttribute()
    {
        return $this->bloques * 45;
    }

    /**
     * Formatea las horas para visualización
     */
    public function getHorarioFormateadoAttribute()
    {
        return sprintf('%s - %s (%d bloques)',
            $this->hora_inicio->format('H:i'),
            $this->hora_fin->format('H:i'),
            $this->bloques
        );
    }
}