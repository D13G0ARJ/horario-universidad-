<?php

namespace App\Listeners;

use App\Models\Bitacora;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrarLogout
{
// En RegistrarLogout.php
public function handle(Logout $event)
{
    if ($event->user) {
        $cedula = $event->user->cedula;
    } else {
        $cedula = 'Sistema'; // O valor por defecto
    }

    Bitacora::create([
        'cedula' => $cedula,
        'accion' => 'Cierre de sesión',
    ]);
}
}

