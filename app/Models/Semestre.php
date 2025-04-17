<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_semestre';  // Coincide con la migración
    public $incrementing = true;           // Para auto-increment
    protected $keyType = 'int';            // Tipo de clave

    // Relación con Turno (pertenece a un turno)
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'id_turno');
    }

    // Relación con Secciones (tiene muchas secciones)
    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'semestre_id', 'id_semestre');
    }

    // Campos asignables masivamente
    protected $fillable = [
        'numero',
        'turno_id'
    ];
}