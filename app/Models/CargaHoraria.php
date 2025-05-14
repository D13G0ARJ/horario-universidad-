<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaHoraria extends Model
{
    use HasFactory;

    // Define explícitamente el nombre de la tabla
    protected $table = 'carga_horarias';

    // Asegura que Laravel use la clave primaria correcta
    protected $primaryKey = 'id';

    protected $fillable = [
        'asignatura_id',  // Asegúrate que coincida con el nombre en la migración
        'tipo',
        'horas_academicas'
    ];

    protected $casts = [
        'horas_academicas' => 'integer'
    ];

    // Relación correctamente definida
    public function asignatura()
    {
        return $this->belongsTo(
            Asignatura::class,
            'asignatura_id', // Foreign key en carga_horarias
            'asignatura_id'  // Primary key en asignaturas
        );
    }
}