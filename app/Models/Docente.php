<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    // Tabla asociada al modelo
    protected $table = 'docentes';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    /**
     * Relación con las asignaturas (muchos a muchos).
     */
    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'asignatura_docente', 'docente_id', 'asignatura_id');
    }
}
