<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MensajeSecreto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msg:secreto {--pista}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Muestra un mensaje oculto para la auditoría del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Mensaje principal cifrado (simulado)
        $mensajeCifrado = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        
        // Mensaje real (solo visible si se resuelve)
        $mensajeReal = "El código de acceso es: LARAVEL-SECRETO-2024";

        if ($this->option('pista')) {
            $this->line("\n🔍 <fg=yellow>PISTA:</>");
            $this->line("Compara este hash con el mensaje real usando:");
            $this->info("Hash::check('frase secreta', '\$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')");
            return;
        }

        $this->line("\n🔐 <fg=red>Mensaje oculto hash:</>");
        $this->error("Hash cifrado: " . $mensajeCifrado);

        // Verificación oculta (solo para demostración)
        if (Hash::check($mensajeReal, $mensajeCifrado)) {
            $this->line("<fg=green>✔ El hash coincide con el mensaje oculto</> (solo visible en código)");
        }
    }
}