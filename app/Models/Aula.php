<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $fillable = ['nombre'];

    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }
}
