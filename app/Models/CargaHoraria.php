<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaHoraria extends Model
{
    use HasFactory;

    protected $fillable = [
        'asignatura_id',
        'tipo',
        'horas_academicas'
    ];

    protected $casts = [
        'horas_academicas' => 'integer'
    ];

    public function asignatura()
    {
        return $this->belongsTo(
            Asignatura::class,
            'asignatura_id',
            'asignatura_id'
        );
    }
}