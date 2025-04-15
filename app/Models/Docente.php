<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    // Configuración de clave primaria
    protected $primaryKey = 'cedula_doc';
    public $incrementing = false;
    protected $keyType = 'string';

    // Campos asignables
    protected $fillable = [
        'cedula_doc',
        'name',
        'email',
        'telefono' // Cambiado de 'phone' a 'telefono'
    ];
}