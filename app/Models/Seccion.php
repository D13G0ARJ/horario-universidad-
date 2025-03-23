<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $fillable = ['nombre', 'aula_id'];

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}