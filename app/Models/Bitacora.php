<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula',
        'accion'
    ];

   // En App\Models\Bitacora
public function user()
{
    return $this->belongsTo(User::class, 'cedula', 'cedula');
}
}
