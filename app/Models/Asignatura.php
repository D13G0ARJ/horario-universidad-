<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asignatura extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'asignatura_id',
        'name'
    ];

    /**
     * Configuración personalizada de clave primaria
     */
    protected $primaryKey = 'asignatura_id';
    public function getRouteKeyName() { return 'asignatura_id'; }
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Relación muchos a muchos con Docente (CORREGIDO)
     */
    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Docente::class,
            'asignatura_docente',
            'asignatura_id',
            'docente_id'
        )->withTimestamps(false); // Desactivar timestamps
    }

    /**
     * Relación muchos a muchos con Seccion (CORREGIDO)
     */
    public function secciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Seccion::class,
            'asignatura_seccion',
            'asignatura_id',
            'seccion_id'
        )->withPivot([
            'carrera_id',
            'semestre_id',
            'turno_id'
        ])->withTimestamps(false); // ¡Cambiado a false!
    }

    /**
     * Relación uno a muchos con CargaHoraria
     */
    public function cargaHoraria(): HasMany
    {
        return $this->hasMany(
            CargaHoraria::class,
            'asignatura_id',
            'asignatura_id'
        );
    }

    /**
     * Obtiene las horas disponibles agrupadas por tipo (NUEVO)
     */
    public function getHorasDisponiblesAttribute(): array
    {
        return $this->cargaHoraria()
            ->selectRaw('tipo, SUM(horas_academicas) as total')
            ->groupBy('tipo')
            ->get()
            ->pluck('total', 'tipo')
            ->toArray();
    }

    /**
     * Obtiene la carga horaria total (NUEVO)
     */
    public function getCargaHorariaTotalAttribute(): int
    {
        return $this->cargaHoraria()->sum('horas_academicas');
    }

    /**
     * Verifica si tiene carga horaria de un tipo específico (NUEVO)
     */
    public function tieneCargaHoraria(string $tipo): bool
    {
        return $this->cargaHoraria()->where('tipo', $tipo)->exists();
    }
}