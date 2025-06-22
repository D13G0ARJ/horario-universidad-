<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Asignatura;
use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\Seccion;
use Illuminate\Support\Str;

class AsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder creará las asignaturas, su carga horaria, y las asignará
     * a los docentes y secciones correspondientes según los datos proporcionados.
     */
    public function run(): void
    {
        // --- LIMPIEZA INICIAL ---
        $this->command->info('Limpiando tablas de asignaturas y relaciones...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Asignatura::truncate();
        CargaHoraria::truncate();
        DB::table('asignatura_docente')->truncate();
        DB::table('asignatura_seccion')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Tablas limpiadas exitosamente.');

        // --- DEFINICIÓN DE DATOS EXTRAÍDOS DE TODAS LAS IMÁGENES ---
        $asignaciones = [
            // Semestre 1 (Sección D1 y D2)
            ['nombre' => 'EDUCACION AMBIENTAL', 'seccion' => '01S-2614-D1', 'cedula_docente' => '6118960'],
            ['nombre' => 'HOMBRE, SOCIEDAD, CIENCIA Y TEC', 'seccion' => '01S-2614-D1', 'cedula_docente' => '3588303'],
            ['nombre' => 'INGLES I', 'seccion' => '01S-2614-D1', 'cedula_docente' => '5224049'],
            ['nombre' => 'DIBUJO', 'seccion' => '01S-2614-D1', 'cedula_docente' => '5033769'],
            ['nombre' => 'MATEMATICA I', 'seccion' => '01S-2614-D1', 'cedula_docente' => '9463012'],
            ['nombre' => 'GEOMETRIA ANALITICA', 'seccion' => '01S-2614-D1', 'cedula_docente' => '5033769'],
            ['nombre' => 'SEMINARIO I', 'seccion' => '01S-2614-D1', 'cedula_docente' => '3588303'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION I', 'seccion' => '01S-2614-D1', 'cedula_docente' => '4420361'],
            ['nombre' => 'EDUCACION AMBIENTAL', 'seccion' => '01S-2614-D2', 'cedula_docente' => '5134258'],
            ['nombre' => 'HOMBRE, SOCIEDAD, CIENCIA Y TEC', 'seccion' => '01S-2614-D2', 'cedula_docente' => '5134258'],
            ['nombre' => 'INGLES I', 'seccion' => '01S-2614-D2', 'cedula_docente' => '5224049'],
            ['nombre' => 'DIBUJO', 'seccion' => '01S-2614-D2', 'cedula_docente' => '4169444'],
            ['nombre' => 'MATEMATICA I', 'seccion' => '01S-2614-D2', 'cedula_docente' => '21343553'],
            ['nombre' => 'GEOMETRIA ANALITICA', 'seccion' => '01S-2614-D2', 'cedula_docente' => '4169444'],
            ['nombre' => 'SEMINARIO I', 'seccion' => '01S-2614-D2', 'cedula_docente' => '6841754'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION I', 'seccion' => '01S-2614-D2', 'cedula_docente' => '4420361'],

            // Semestre 2
            ['nombre' => 'INGLES II', 'seccion' => '02S-2614-D1', 'cedula_docente' => '5224049'],
            ['nombre' => 'QUIMICA GENERAL', 'seccion' => '02S-2614-D1', 'cedula_docente' => '5000570'],
            ['nombre' => 'MATEMATICA II', 'seccion' => '02S-2614-D1', 'cedula_docente' => '5523551'],
            ['nombre' => 'ALGEBRA LINEAL', 'seccion' => '02S-2614-D1', 'cedula_docente' => '11041862'],
            ['nombre' => 'SEMINARIO II', 'seccion' => '02S-2614-D1', 'cedula_docente' => '6841754'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION II', 'seccion' => '02S-2614-D1', 'cedula_docente' => '3424301'],
            ['nombre' => 'TRADICION CULTURAL Y FOLKLORE', 'seccion' => '02S-2614-D1', 'cedula_docente' => '4843063'],
            ['nombre' => 'FISICA I', 'seccion' => '02S-2614-D1', 'cedula_docente' => '13137384'],

            // Semestre 3
            ['nombre' => 'FISICA II', 'seccion' => '03S-2614-D1', 'cedula_docente' => '6149219'],
            ['nombre' => 'MATEMATICA III', 'seccion' => '03S-2614-D1', 'cedula_docente' => '4169444'],
            ['nombre' => 'PROBABILIDADES Y ESTADISTICA', 'seccion' => '03S-2614-D1', 'cedula_docente' => '9640000'],
            ['nombre' => 'PROGRAMACION', 'seccion' => '03S-2614-D1', 'cedula_docente' => '6879690'],
            ['nombre' => 'SISTEMAS ADMINISTRATIVOS', 'seccion' => '03S-2614-D1', 'cedula_docente' => '10484687'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION III', 'seccion' => '03S-2614-D1', 'cedula_docente' => '14680057'],
            
            // Semestre 4
            ['nombre' => 'TEORIA DE LOS SISTEMAS', 'seccion' => '04S-2614-D1', 'cedula_docente' => '29676197'],
            ['nombre' => 'CALCULO NUMERICO', 'seccion' => '04S-2614-D1', 'cedula_docente' => '6879690'],
            ['nombre' => 'LOGICA MATEMATICA', 'seccion' => '04S-2614-D1', 'cedula_docente' => '6878447'],
            ['nombre' => 'LENGUAJES DE PROGRAMACION I', 'seccion' => '04S-2614-D1', 'cedula_docente' => '6879690'],
            ['nombre' => 'PROCESAMIENTO DE DATOS', 'seccion' => '04S-2614-D1', 'cedula_docente' => '11036705'],
            ['nombre' => 'SISTEMAS DE PRODUCCION', 'seccion' => '04S-2614-D1', 'cedula_docente' => '4910779'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION IV', 'seccion' => '04S-2614-D1', 'cedula_docente' => '4420361'],
            ['nombre' => 'ACO. 14020 EDUCACION FISICA Y DEPORTES', 'seccion' => '04S-2614-D1', 'cedula_docente' => '6461978'],

            // Semestre 5
            ['nombre' => 'TEORIA DE GRAFOS', 'seccion' => '05S-2614-D1', 'cedula_docente' => '5580744'],
            ['nombre' => 'LENGUAJES DE PROGRAMACION II', 'seccion' => '05S-2614-D1', 'cedula_docente' => '6364051'],
            ['nombre' => 'INVESTIGACION DE OPERACIONES', 'seccion' => '05S-2614-D1', 'cedula_docente' => '13137384'],
            ['nombre' => 'CIRCUITOS LOGICOS', 'seccion' => '05S-2614-D1', 'cedula_docente' => '11036705'],
            ['nombre' => 'ANALISIS DE SISTEMAS', 'seccion' => '05S-2614-D1', 'cedula_docente' => '13137384'],
            ['nombre' => 'BASES DE DATOS', 'seccion' => '05S-2614-D1', 'cedula_docente' => '29676677'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION V', 'seccion' => '05S-2614-D1', 'cedula_docente' => '3424301'],
            ['nombre' => 'CATEDRA BOLIVARIANA I', 'seccion' => '05S-2614-D1', 'cedula_docente' => '6118960'],

            // Semestre 6
            ['nombre' => 'OPTIMIZACION NO LINEAL', 'seccion' => '06S-2614-D1', 'cedula_docente' => '6879690'],
            ['nombre' => 'LENGUAJES DE PROGRAMACION III', 'seccion' => '06S-2614-D1', 'cedula_docente' => '11036705'],
            ['nombre' => 'PROCESOS ESTOCASTICOS', 'seccion' => '06S-2614-D1', 'cedula_docente' => '9640000'],
            ['nombre' => 'ARQUITECTURA DEL COMPUTADOR', 'seccion' => '06S-2614-D1', 'cedula_docente' => '6521614'],
            ['nombre' => 'DISEÑO DE SISTEMAS', 'seccion' => '06S-2614-D1', 'cedula_docente' => '12414718'],
            ['nombre' => 'SISTEMAS OPERATIVOS', 'seccion' => '06S-2614-D1', 'cedula_docente' => '9640000'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION VI', 'seccion' => '06S-2614-D1', 'cedula_docente' => '4420361'],
            ['nombre' => 'CATEDRA BOLIVARIANA II', 'seccion' => '06S-2614-D1', 'cedula_docente' => '11747295'],
            ['nombre' => 'ACO III CULTURA Y COMUNICACION', 'seccion' => '06S-2614-D1', 'cedula_docente' => '6118960'],

            // Semestre 7
            ['nombre' => 'IMPLANTACION DE SISTEMAS', 'seccion' => '07S-2614-D1', 'cedula_docente' => '12414718'],
            ['nombre' => 'METODOLOGIA DE LA INVESTIGACION', 'seccion' => '07S-2614-D1', 'cedula_docente' => '6118960'],
            ['nombre' => 'SIMULACION Y MODELOS', 'seccion' => '07S-2614-D1', 'cedula_docente' => '5580744'],
            ['nombre' => 'REDES', 'seccion' => '07S-2614-D1', 'cedula_docente' => '6364051'],
            ['nombre' => 'GERENCIA DE LA INFORMATICA', 'seccion' => '07S-2614-D1', 'cedula_docente' => '3967363'],
            ['nombre' => 'ELECTIVA TECNICA INTELIGENCIA ARTIFICIAL', 'seccion' => '07S-2614-D1', 'cedula_docente' => '6364051'],
            ['nombre' => 'ELECTIVA NO TECNICA PRINCIPIOS DE GERENCIA', 'seccion' => '07S-2614-D1', 'cedula_docente' => '6118960'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION VII', 'seccion' => '07S-2614-D1', 'cedula_docente' => '4665812'],
            
            // Semestre 8
            ['nombre' => 'TEORIA DE DECISIONES', 'seccion' => '08S-2614-D1', 'cedula_docente' => '13137384'],
            ['nombre' => 'AUDITORIA DE SISTEMAS', 'seccion' => '08S-2614-D1', 'cedula_docente' => '12414718'],
            ['nombre' => 'MARCO LEGAL PARA EL EJERCICIO DE LA INGENIERIA', 'seccion' => '08S-2614-D1', 'cedula_docente' => '11747295'],
            ['nombre' => 'TELEPROCESOS', 'seccion' => '08S-2614-D1', 'cedula_docente' => '6364051'],
            ['nombre' => 'ELECTIVA TECNICA (PLATAFORMA CLIENTE - SERVI)', 'seccion' => '08S-2614-D1', 'cedula_docente' => '6521614'],
            ['nombre' => 'ELECTIVA NO TECNICA (PLANIF Y EVAL PROYECTOS)', 'seccion' => '08S-2614-D1', 'cedula_docente' => '8680273'],
            ['nombre' => 'DEFENSA INTEGRAL DE LA NACION VIII', 'seccion' => '08S-2614-D1', 'cedula_docente' => null],
            ['nombre' => 'ACTIVIDAD COMPLEMENTARIA DEPORTE Y RECREA', 'seccion' => '08S-2614-D1', 'cedula_docente' => '6461978'],
        ];

        $asignaturasUnicas = [];
        $cargasHorariasParaInsertar = [];
        $asignaturaDocenteParaInsertar = [];
        $asignaturaSeccionParaInsertar = [];

        foreach ($asignaciones as $asignacion) {
            $nombreAsignatura = trim($asignacion['nombre']);
            
            // --- CORRECCIÓN: Generar un código único y corto para la asignatura ---
            $codigoAsignatura = null;
            do {
                // Tomar las 3 primeras letras del nombre, limpiar caracteres no alfabéticos
                $iniciales = strtoupper(preg_replace('/[^a-zA-Z]/', '', $nombreAsignatura));
                $baseCodigo = substr($iniciales, 0, 3);
                // Añadir 2 números aleatorios
                $codigoAsignatura = $baseCodigo . mt_rand(10, 99);
            } while (isset($asignaturasUnicas[$codigoAsignatura])); // Repetir si el código ya existe
            // --- FIN DE LA CORRECCIÓN ---

            // --- 1. Preparar Asignatura (solo si no ha sido procesada) ---
            if (!isset($asignaturasUnicas[$codigoAsignatura])) {
                $asignaturasUnicas[$codigoAsignatura] = [
                    'asignatura_id' => $codigoAsignatura,
                    'name' => $nombreAsignatura,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // --- 2. Preparar Carga Horaria para la nueva asignatura ---
                $cargasHorariasParaInsertar[] = ['asignatura_id' => $codigoAsignatura, 'tipo' => 'teorica', 'horas_academicas' => 3, 'created_at' => now(), 'updated_at' => now()];
                $cargasHorariasParaInsertar[] = ['asignatura_id' => $codigoAsignatura, 'tipo' => 'practica', 'horas_academicas' => 3, 'created_at' => now(), 'updated_at' => now()];
            }
            
            // --- 3. Preparar Relación Asignatura-Docente (clave única para evitar duplicados) ---
            if (!empty($asignacion['cedula_docente'])) {
                $docente = Docente::where('cedula_doc', $asignacion['cedula_docente'])->first();
                if ($docente) {
                    $key = $codigoAsignatura . '-' . $asignacion['cedula_docente'];
                    if (!isset($asignaturaDocenteParaInsertar[$key])) {
                        $asignaturaDocenteParaInsertar[$key] = [
                            'asignatura_id' => $codigoAsignatura,
                            'docente_id' => $asignacion['cedula_docente'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                } else {
                    $this->command->warn("Advertencia: Docente con cédula {$asignacion['cedula_docente']} no encontrado. No se asignará a '{$nombreAsignatura}'.");
                }
            }

            // --- 4. Preparar Relación Asignatura-Sección (clave única para evitar duplicados) ---
            $seccion = Seccion::find($asignacion['seccion']);
            if ($seccion) {
                $key = $codigoAsignatura . '-' . $seccion->codigo_seccion;
                 if (!isset($asignaturaSeccionParaInsertar[$key])) {
                    $asignaturaSeccionParaInsertar[$key] = [
                        'asignatura_id' => $codigoAsignatura,
                        'seccion_id' => $seccion->codigo_seccion,
                        'carrera_id' => $seccion->carrera_id,
                        'semestre_id' => $seccion->semestre_id,
                        'turno_id' => $seccion->turno_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            } else {
                $this->command->warn("Advertencia: Sección con código {$asignacion['seccion']} no encontrada. No se asignará '{$nombreAsignatura}'.");
            }
        }
        
        // --- INSERCIÓN EN BASE DE DATOS ---
        
        if (!empty($asignaturasUnicas)) {
            Asignatura::insert(array_values($asignaturasUnicas));
            $this->command->info(count($asignaturasUnicas) . ' asignaturas únicas han sido creadas.');
        }

        if (!empty($cargasHorariasParaInsertar)) {
            CargaHoraria::insert($cargasHorariasParaInsertar);
            $this->command->info(count($cargasHorariasParaInsertar) . ' registros de carga horaria han sido creados.');
        }

        if (!empty($asignaturaDocenteParaInsertar)) {
            DB::table('asignatura_docente')->insert(array_values($asignaturaDocenteParaInsertar));
            $this->command->info(count($asignaturaDocenteParaInsertar) . ' asignaciones a docentes han sido creadas.');
        }

        if (!empty($asignaturaSeccionParaInsertar)) {
            DB::table('asignatura_seccion')->insert(array_values($asignaturaSeccionParaInsertar));
            $this->command->info(count($asignaturaSeccionParaInsertar) . " asignaciones a secciones han sido creadas.");
        }

        $this->command->info('AsignaturaSeeder ha finalizado exitosamente.');
    }
}
