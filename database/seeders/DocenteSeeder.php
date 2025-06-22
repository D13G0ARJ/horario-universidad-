<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Docente;
use Illuminate\Support\Str;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder limpia la tabla de docentes y la rellena con una lista
     * consolidada y sin duplicados, extraída de los datos proporcionados.
     * También se asegura de que todos los correos electrónicos sean únicos.
     */
    public function run(): void
    {
        // Desactivar llaves foráneas para permitir el truncado
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Vaciar la tabla para evitar errores de duplicados al re-ejecutar
        Docente::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $docentes = [
            // Cédula como clave para asegurar unicidad y evitar duplicados
            '13137384' => ['nombre' => 'Jean', 'apellido' => 'Jean Simon'],
            '12414718' => ['nombre' => 'Gabriela', 'apellido' => 'Rivas'],
            '11747295' => ['nombre' => 'Elia', 'apellido' => 'Dominguez'],
            '6364051'  => ['nombre' => 'Rodolfo', 'apellido' => 'Caccamo'],
            '6521614'  => ['nombre' => 'Oswaldo', 'apellido' => 'Guedez'],
            '8680273'  => ['nombre' => 'Ornella', 'apellido' => 'Martinez'],
            '6461978'  => ['nombre' => 'Leopoldo', 'apellido' => 'Rondon'],
            '6118960'  => ['nombre' => 'Carmen', 'apellido' => 'Marrocco'],
            '5580744'  => ['nombre' => 'Tomas', 'apellido' => 'Bolivar'],
            '3967363'  => ['nombre' => 'Efrain', 'apellido' => 'Calles'],
            '4665812'  => ['nombre' => 'Alexander', 'apellido' => 'Cacique'],
            '6879690'  => ['nombre' => 'Francisco', 'apellido' => 'Velazquez'],
            '11036705' => ['nombre' => 'Jesus', 'apellido' => 'Carrasquel'],
            '9640000'  => ['nombre' => 'Luis', 'apellido' => 'Becerra'],
            '4420361'  => ['nombre' => 'Jose', 'apellido' => 'Torrealba'],
            '29676677' => ['nombre' => 'Claudia', 'apellido' => 'Martinez'],
            '3424301'  => ['nombre' => 'Miguel Angel', 'apellido' => 'Totesaut'],
            '29676197' => ['nombre' => 'Joseph', 'apellido' => 'Mora'],
            '6878447'  => ['nombre' => 'Laura', 'apellido' => 'Alvarez'],
            '4910779'  => ['nombre' => 'Ernesto', 'apellido' => 'Tamoy'],
            '6149219'  => ['nombre' => 'Angel', 'apellido' => 'Hernandez'],
            '4169444'  => ['nombre' => 'Leopoldo', 'apellido' => 'Hiller'],
            '10484687' => ['nombre' => 'Lisbeth', 'apellido' => 'Rengifo'],
            '14680057' => ['nombre' => 'Jose', 'apellido' => 'Martinez'],
            '5224049'  => ['nombre' => 'Edna', 'apellido' => 'Mendoza'],
            '5000570'  => ['nombre' => 'Wilson', 'apellido' => 'Ruiz'],
            '5523551'  => ['nombre' => 'Nestor', 'apellido' => 'Rigual'],
            '11041862' => ['nombre' => 'Juan C.', 'apellido' => 'Galluzzo'],
            '6841754'  => ['nombre' => 'Emily', 'apellido' => 'Savasta'],
            '4843063'  => ['nombre' => 'Ninoska', 'apellido' => 'Alzuru'],
            '3588303'  => ['nombre' => 'Miguel', 'apellido' => 'Perez'],
            '5033769'  => ['nombre' => 'Celeste', 'apellido' => 'Escalante'],
            '9463012'  => ['nombre' => 'Yenny', 'apellido' => 'Abreu'],
            '5134258'  => ['nombre' => 'Erasmo', 'apellido' => 'Rodriguez'],
            '21343553' => ['nombre' => 'Jonathan', 'apellido' => 'Diaz'],
        ];

        $datosParaInsertar = [];
        $emailsUtilizados = []; // Array para rastrear los correos ya generados

        foreach ($docentes as $cedula => $data) {
            $nombreCompleto = $data['nombre'] . ' ' . $data['apellido'];
            
            // --- Lógica para asegurar email único ---
            $nombreBaseParaEmail = strtolower(Str::slug($data['nombre'] . $data['apellido'], ''));
            $email = $nombreBaseParaEmail . '@gmail.com';
            
            $intentos = 0;
            while (in_array($email, $emailsUtilizados)) {
                // Si el email ya existe, se agrega un número aleatorio para hacerlo único
                $intentos++;
                $email = $nombreBaseParaEmail . mt_rand(1, 99) . '@gmail.com';
                if ($intentos > 10) { // Salvaguarda para evitar un bucle infinito
                    $email = $nombreBaseParaEmail . Str::random(5) . '@gmail.com';
                }
            }
            $emailsUtilizados[] = $email;
            // --- Fin de la lógica ---

            // Generar número de teléfono aleatorio con el prefijo 0414
            $telefono = '0414' . mt_rand(1000000, 9999999);

            $datosParaInsertar[] = [
                'cedula_doc'    => $cedula,
                'name'          => $nombreCompleto,
                'email'         => $email,
                'telefono'      => $telefono,
                'dedicacion_id' => 1, // Dedicación ID 1 para todos
                'status'        => 'activo',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Insertar todos los docentes en una sola consulta para mayor eficiencia
        if (!empty($datosParaInsertar)) {
            Docente::insert($datosParaInsertar);
        }

        $this->command->info(count($datosParaInsertar) . ' docentes han sido creados exitosamente.');
    }
}
