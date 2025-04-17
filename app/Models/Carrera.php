<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'carrera_id', // Código único (Ej: "ING-SIST")
        'name'        // Nombre completo de la carrera
    ];

    /**
     * Clave primaria personalizada
     */
    protected $primaryKey = 'carrera_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Relación con Secciones
     */
    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'carrera_id', 'carrera_id');
    }
}