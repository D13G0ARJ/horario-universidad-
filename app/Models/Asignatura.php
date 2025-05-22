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
     * Los atributos que se pueden asignar de forma masiva.
     *
     * @var array<string>
     */
    protected $fillable = [
        'asignatura_id',
        'name'
    ];

    /**
     * Configuración personalizada de la clave primaria.
     *
     * @var string
     */
    protected $primaryKey = 'asignatura_id';

    /**
     * Define la clave de ruta para la resolución de modelos implícita.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'asignatura_id';
    }

    /**
     * Indica si la clave primaria es autoincremental.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * El tipo de dato de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Relación muchos a muchos con Docente.
     * Una asignatura puede ser impartida por varios docentes.
     *
     * @return BelongsToMany
     */
    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Docente::class,
            'asignatura_docente', // Nombre de la tabla pivote
            'asignatura_id',      // Clave foránea del modelo actual (Asignatura) en la tabla pivote
            'docente_id'          // Clave foránea del modelo relacionado (Docente) en la tabla pivote
        )->withTimestamps(false); // Desactivar timestamps si no se usan en la tabla pivote
    }

    /**
     * Relación muchos a muchos con Seccion.
     * Una asignatura puede ser parte de varias secciones.
     *
     * @return BelongsToMany
     */
    public function secciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Seccion::class,
            'asignatura_seccion', // Nombre de la tabla pivote
            'asignatura_id',      // Clave foránea del modelo actual (Asignatura) en la tabla pivote
            'seccion_id'          // Clave foránea del modelo relacionado (Seccion) en la tabla pivote
        )->withPivot([
            'carrera_id',
            'semestre_id',
            'turno_id'
        ])->withTimestamps(false); // Desactivar timestamps si no se usan en la tabla pivote
    }

    /**
     * Relación uno a muchos con CargaHoraria.
     * Una asignatura puede tener varias cargas horarias definidas (teóricas, prácticas, laboratorio).
     *
     * @return HasMany
     */
    public function cargaHoraria(): HasMany
    {
        return $this->hasMany(
            CargaHoraria::class,
            'asignatura_id', // Clave foránea en la tabla 'carga_horarias'
            'asignatura_id'  // Clave local en la tabla 'asignaturas'
        );
    }

    /**
     * Obtiene las horas disponibles agrupadas por tipo de horas (teóricas, prácticas, laboratorio).
     *
     * @return array
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
     * Obtiene la carga horaria total sumando todas las horas académicas de la asignatura.
     *
     * @return int
     */
    public function getCargaHorariaTotalAttribute(): int
    {
        return $this->cargaHoraria()->sum('horas_academicas');
    }

    /**
     * Verifica si la asignatura tiene carga horaria de un tipo específico.
     *
     * @param string $tipo El tipo de hora a verificar (teorica, practica, laboratorio).
     * @return bool
     */
    public function hasCargaHorariaTipo(string $tipo): bool
    {
        return $this->cargaHoraria()->where('tipo', $tipo)->exists();
    }
}