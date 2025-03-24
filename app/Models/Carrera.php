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
        'code', // Agregar 'code' aquí
        'name',
    ];

    /**
     * Indica que 'code' es la clave primaria.
     *
     * @var string
     */
    protected $primaryKey = 'code';

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
}
