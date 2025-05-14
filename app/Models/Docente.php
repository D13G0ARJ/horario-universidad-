<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Docente extends Model
{
    use HasFactory;

    /**
     * La clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'cedula_doc';

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
     * Atributos asignables masivamente.
     *
     * @var array<string>
     */
    protected $fillable = [
        'cedula_doc',
        'name',
        'email',
        'telefono',
        'docente_id',
        'dedicacion_id'
    ];

    /**
     * Relación muchos a muchos con Asignatura.
     */
    public function asignaturas(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'asignatura_docente', // Tabla pivot
            'docente_id',         // FK de docente en pivot
            'asignatura_id'       // FK de asignatura en pivot
            )->withTimestamps(false);
    }

    public function dedicacion(): BelongsTo
    {
        return $this->belongsTo(Dedicacion::class, 'dedicacion_id', 'dedicacion_id');
    }
}