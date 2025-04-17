<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    // Especificar clave primaria personalizada
    protected $primaryKey = 'id_turno';
    public $incrementing = true;
    protected $keyType = 'int';

    // Relación con Semestres (CORREGIDA)
    public function semestres()
    {
        return $this->hasMany(Semestre::class, 'turno_id', 'id_turno');
    }

    // Relación con Secciones (CORREGIDA)
    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'turno_id', 'id_turno');
    }

    // Campos asignables masivamente
    protected $fillable = [
        'nombre',
        'hora_inicio',
        'hora_fin'
    ];
}