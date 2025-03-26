<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respaldo extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = ['user_id', 'file_path', 'created_at', 'updated_at'];

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Formatear la fecha de creación
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }

    // Formatear la hora de creación
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i:s');
    }
}
