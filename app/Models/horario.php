<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar BelongsTo

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
        'observaciones', // Comentarios adicionales
        'aula_id',       // NUEVO: Campo para la clave foránea del aula
        'asignatura_compartida_id' // Campo para asignatura compartida
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
     * Relación con Periodo
     */
    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    /**
     * Relación con Asignatura
     */
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id', 'asignatura_id');
    }

    /**
     * Relación con Carrera
     */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'carrera_id');
    }

    /**
     * Relación con Docente
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'cedula_doc');
    }

    /**
     * Relación con Turno
     */
    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'id_turno');
    }

    /**
     * Relación con Semestre
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'semestre_id', 'id_semestre');
    }

    /**
     * Relación con Sección
     */
    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'codigo_seccion');
    }

    /**
     * Relación con Usuario (Coordinador)
     */
    public function coordinador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinador_cedula', 'cedula')
            ->select('cedula', 'name');
    }

    /**
     * NUEVO: Relación con Aula
     * Un horario pertenece a un aula.
     */
    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'aula_id', 'id');
    }

    /**
     * Relación con Asignatura Compartida (si aplica)
     */
    public function asignaturaCompartida(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_compartida_id', 'asignatura_id');
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
    public function getDuracionTotalAttribute(): int
    {
        return $this->bloques * 45;
    }

    /**
     * Formatea las horas para visualización
     */
    public function getHorarioFormateadoAttribute(): string
    {
        return sprintf('%s - %s (%d bloques)',
            $this->hora_inicio->format('H:i'),
            $this->hora_fin->format('H:i'),
            $this->bloques
        );
    }

    
}
