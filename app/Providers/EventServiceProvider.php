<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\RegistrarLogin;
use App\Listeners\RegistrarLogout;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Evento de registro de usuario
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Evento de inicio de sesión
        Login::class => [
            RegistrarLogin::class
        ],
        
        // Evento de cierre de sesión
        Logout::class => [
            RegistrarLogout::class
        ]
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}