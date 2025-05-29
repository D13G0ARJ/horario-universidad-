<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargaHoraria extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     * Por convención, Laravel inferirá 'carga_horarias' si el modelo es CargaHoraria,
     * pero es buena práctica especificarlo para mayor claridad.
     *
     * @var string
     */
    protected $table = 'carga_horarias';

    /**
     * La clave primaria asociada a la tabla.
     * Por defecto, Laravel asume 'id' y que es autoincremental.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indica si la clave primaria es autoincremental.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * El tipo de dato de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Los atributos que se pueden asignar de forma masiva.
     *
     * @var array<string>
     */
    protected $fillable = [
        'asignatura_id',
        'tipo',
        'horas_academicas'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'horas_academicas' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el modelo Asignatura.
     * Una carga horaria pertenece a una asignatura.
     *
     * @return BelongsTo
     */
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(
            Asignatura::class,
            'asignatura_id', // Clave foránea en la tabla 'carga_horarias'
            'asignatura_id'  // Clave local en la tabla 'asignaturas'
        )->withDefault(); // Esto permite que si la asignatura no existe, no devuelva null y use valores por defecto
    }

    /**
     * Obtiene una lista de los tipos de carga horaria permitidos.
     * Útil para validaciones o enumeraciones en el frontend.
     *
     * @return array<string>
     */
    public static function tiposPermitidos(): array
    {
        return ['teorica', 'practica', 'laboratorio'];
    }

    /**
     * Scope para filtrar cargas horarias por tipo.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $tipo El tipo de hora (ej. 'teorica', 'practica').
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para filtrar cargas horarias por asignatura.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $asignaturaId El ID de la asignatura.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorAsignatura($query, string $asignaturaId)
    {
        return $query->where('asignatura_id', $asignaturaId);
    }

    /**
     * Scope para filtrar cargas horarias por un rango de horas académicas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $minHoras El mínimo de horas académicas.
     * @param  int|null  $maxHoras El máximo de horas académicas.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRangoHorasAcademicas($query, ?int $minHoras = null, ?int $maxHoras = null)
    {
        if (!is_null($minHoras)) {
            $query->where('horas_academicas', '>=', $minHoras);
        }
        if (!is_null($maxHoras)) {
            $query->where('horas_academicas', '<=', $maxHoras);
        }
        return $query;
    }
}