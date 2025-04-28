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
        'asignatura_id', // Nombre correcto del campo (coincide con migración)
        'name',
    ];

    /**
     * La clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'asignatura_id';

    /**
     * Indica que la clave primaria no es autoincremental.
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
     */
    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Docente::class,
            'asignatura_docente', // Tabla pivot
            'asignatura_id',      // FK de asignatura en pivot
            'docente_id'          // FK de docente en pivot
        );
    }

    /**
     * Relación uno a muchos con Seccion.
     */
    public function secciones()
    {
        return $this->belongsToMany(Seccion::class, 'asignatura_seccion', 'asignatura_id', 'seccion_id')
                    ->withPivot(['carrera_id', 'semestre_id', 'turno_id']);
    }
}