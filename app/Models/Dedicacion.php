<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dedicacion extends Model
{
    protected $table = 'dedicaciones';

    protected $fillable = ['dedicacion'];

    public function docentes(): HasMany
    {
        return $this->hasMany(Docente::class);
    }
}
