<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dedicacion extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'dedicaciones'; // Especifica el nombre correcto de la tabla

    /**
     * La clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'dedicacion_id'; // Especifica la clave primaria

    /**
     * Indica que la clave primaria es autoincremental.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * El tipo de dato de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Relación con el modelo Docente.
     *
     * @return HasMany
     */
    public function docentes(): HasMany
    {
        return $this->hasMany(Docente::class, 'dedicacion_id', 'dedicacion_id');
    }
}