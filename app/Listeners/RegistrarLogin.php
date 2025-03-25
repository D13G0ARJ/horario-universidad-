<?php

namespace App\Listeners;

use App\Models\Bitacora;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrarLogin
{
    public function handle(Login $event)
    {
        Bitacora::create([
            'cedula' => $event->user->cedula,
            'accion' => 'Inicio de sesión',
        ]);
    }
}