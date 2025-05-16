<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargaHoraria extends Model
{
    use HasFactory;

    // 1. Nombre de tabla explícito (coincide con migración)
    protected $table = 'carga_horarias';

    // 2. Configuración de clave primaria
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // 3. Atributos asignables masivamente
    protected $fillable = [
        'asignatura_id', 
        'tipo',
        'horas_academicas'
    ];

    // 4. Casting de tipos
    protected $casts = [
        'horas_academicas' => 'integer'
    ];

    // 5. Relación con Asignatura (mejorada)
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(
            Asignatura::class,
            'asignatura_id', // FK en carga_horarias
            'asignatura_id'  // PK en asignaturas
        )->withDefault(); // Evita null si se elimina la asignatura
    }

    // 6. Validación de tipos permitidos
    public static function tiposPermitidos(): array
    {
        return ['teorica', 'practica', 'laboratorio'];
    }

    // 7. Scope para consultas comunes
    public function scopeTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}