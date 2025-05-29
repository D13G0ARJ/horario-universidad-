<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Asegúrate de incluir HasFactory si lo usas

class Seccion extends Model
{
    use HasFactory; // Si vas a usar factories para este modelo

    /**
     * El nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'secciones';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'codigo_seccion';

    /**
     * Indica si la clave primaria no es autoincremental.
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
     * Los atributos que se pueden asignar de forma masiva.
     *
     * @var array<string>
     */
    protected $fillable = [
        'codigo_seccion',
        'carrera_id',
        'turno_id',
        'semestre_id'
    ];

    /**
     * Define la clave de ruta para la resolución de modelos implícita.
     * Esto permite usar el 'codigo_seccion' en las rutas en lugar del ID por defecto.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'codigo_seccion';
    }

    /**
     * Relación con el modelo Carrera.
     * Una sección pertenece a una carrera.
     *
     * @return BelongsTo
     */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(
            Carrera::class,
            'carrera_id',  // Clave foránea en la tabla 'secciones'
            'carrera_id'   // Clave local en la tabla 'carreras'
        );
    }

    /**
     * Relación con el modelo Turno.
     * Una sección pertenece a un turno.
     *
     * @return BelongsTo
     */
    public function turno(): BelongsTo
    {
        return $this->belongsTo(
            Turno::class,
            'turno_id',    // Clave foránea en la tabla 'secciones'
            'id_turno'     // Clave local en la tabla 'turnos'
        );
    }

    /**
     * Relación con el modelo Semestre.
     * Una sección pertenece a un semestre.
     *
     * @return BelongsTo
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(
            Semestre::class,
            'semestre_id', // Clave foránea en la tabla 'secciones'
            'id_semestre'  // Clave local en la tabla 'semestres'
        );
    }

    /**
     * Relación muchos a muchos con Asignatura.
     * Una sección tiene muchas asignaturas y una asignatura puede estar en varias secciones.
     *
     * @return BelongsToMany
     */
    public function asignaturas(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'asignatura_seccion', // Nombre de la tabla pivote
            'seccion_id',         // Clave foránea del modelo actual (Seccion) en la tabla pivote
            'asignatura_id'       // Clave foránea del modelo relacionado (Asignatura) en la tabla pivote
        )->withPivot([
            'carrera_id',  // Incluimos las columnas adicionales de la tabla pivote
            'semestre_id',
            'turno_id'
        ])->withTimestamps(false); // Desactivar timestamps si no los usas en la tabla pivote
    }

    /**
     * Relación uno a muchos con Horario.
     * Una sección puede tener múltiples horarios asociados.
     *
     * @return HasMany
     */
    public function horarios(): HasMany
    {
        return $this->hasMany(
            Horario::class,
            'seccion_id',    // Clave foránea en la tabla 'horarios'
            'codigo_seccion' // Clave local en la tabla 'secciones'
        );
    }
}