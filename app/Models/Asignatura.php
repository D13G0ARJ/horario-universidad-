<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Relación muchos a muchos con Docente
     */
    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Docente::class,
            'asignatura_docente',
            'asignatura_id',  // FK en pivot para Asignatura
            'docente_id'     // FK en pivot para Docente
        );
    }

    /**
     * Relación muchos a muchos con Seccion (CORREGIDO)
     */
    public function secciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Seccion::class,
            'asignatura_seccion',
            'asignatura_id',   // FK en pivot para Asignatura
            'seccion_id'        // FK en pivot para Seccion (debe coincidir con codigo_seccion en tabla secciones)
        )->withPivot([
            'carrera_id',
            'semestre_id',
            'turno_id'
        ]);
    }
}