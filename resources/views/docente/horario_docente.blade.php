<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Horario del Docente - {{ $docente->name }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Asumiendo que 'adminlte.css' está disponible en la ruta 'public/dist/css/' --}}
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; overflow: auto; }
        .content-wrapper { padding: 20px; } /* Añadir padding al content-wrapper */
        .bloque-horario {
            position: absolute; top: 0; left: 0; width: calc(100% - 8px); z-index: 10;
            padding: 3px 5px; font-size: 0.7rem; color: white; overflow: hidden;
            border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: flex; flex-direction: column; justify-content: center;
            text-align: center;
            transition: all 0.2s ease-in-out;
        }
        .bloque-horario:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .bloque-contenido {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }
        .asignatura-nombre {
            font-weight: bold;
            line-height: 1.1;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .asignatura-details {
            font-size: 0.65rem;
            line-height: 1;
            opacity: 0.9;
        }
        .horario-table {
            width: 100%;
            table-layout: fixed; /* Asegura que las columnas tengan el mismo ancho */
            border-collapse: separate; /* Permite border-spacing */
            border-spacing: 0 5px; /* Espacio vertical entre filas */
        }
        .horario-table th, .horario-table td {
            border: 1px solid #dee2e6;
            padding: 0; /* Eliminar padding por defecto para controlar el contenido */
            vertical-align: top;
            position: relative; /* Necesario para posicionar los bloques absolutos */
            height: 45px; /* Altura base para un bloque de 45 minutos */
        }
        .horario-table th {
            background-color: #f8f9fa;
            text-align: center;
            padding: 8px;
            font-weight: 600;
        }
        .horario-table td.hora-col {
            background-color: #f8f9fa;
            text-align: center;
            font-weight: 600;
            padding: 8px;
            width: 120px; /* Ancho ajustado para el formato de rango de horas */
        }
        .horario-container {
            overflow-x: auto; /* Permite desplazamiento horizontal en pantallas pequeñas */
        }

        /* Clases de colores para los bloques de horario */
        .bg-color-1 { background-color: #6a0572; } /* Morado oscuro */
        .bg-color-2 { background-color: #007bff; } /* Azul Bootstrap */
        .bg-color-3 { background-color: #28a745; } /* Verde Bootstrap */
        .bg-color-4 { background-color: #ffc107; color: #343a40; } /* Amarillo Bootstrap */
        .bg-color-5 { background-color: #dc3545; } /* Rojo Bootstrap */
        .bg-color-6 { background-color: #6f42c1; } /* Púrpura */
        .bg-color-7 { background-color: #fd7e14; } /* Naranja */
        .bg-color-8 { background-color: #20c997; } /* Turquesa */
        .bg-color-9 { background-color: #e83e8c; } /* Rosa */
        .bg-color-10 { background-color: #17a2b8; } /* Cian */
        .bg-color-11 { background-color: #343a40; } /* Gris oscuro */
        .bg-color-12 { background-color: #007bff; } /* Azul (repetido para más opciones) */
        /* Añade más colores si tienes muchas asignaturas */
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper" style="min-height: 100vh;">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Horario del Docente: {{ $docente->name }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('docente.index') }}">Docentes</a></li>
                                <li class="breadcrumb-item active">Horario</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                Horario consolidado para el período: <strong>{{ $periodo->nombre }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalle del Horario</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="horario-container">
                                <table class="table table-bordered text-center horario-table">
                                    <thead>
                                        <tr>
                                            <th class="hora-col">Hora</th>
                                            @foreach($diasSemana as $diaNum => $diaNombre)
                                                <th>{{ $diaNombre }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($horasDia as $horaRango) {{-- Iterar sobre el rango de horas --}}
                                            <tr>
                                                <td class="hora-col">{{ $horaRango }}</td>
                                                @php
                                                    // Extraer la hora de inicio del rango para usarla en el ID de la celda
                                                    $horaInicioCelda = explode(' - ', $horaRango)[0];
                                                @endphp
                                                @foreach($diasSemana as $diaNum => $diaNombre)
                                                    <td id="celda-{{ $diaNum }}-{{ str_replace(':', '', $horaInicioCelda) }}">
                                                        {{-- Los bloques de horario se insertarán aquí con JavaScript --}}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center mt-3">
                            <a href="{{ route('docente.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Volver a la Lista de Docentes
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const BASE_CELL_HEIGHT = 45; // Altura base de una celda en píxeles (45 minutos)

            // Colores predefinidos para las asignaturas
            const colorClasses = [
                'bg-color-1', 'bg-color-2', 'bg-color-3', 'bg-color-4', 'bg-color-5',
                'bg-color-6', 'bg-color-7', 'bg-color-8', 'bg-color-9', 'bg-color-10',
                'bg-color-11', 'bg-color-12'
            ];
            let colorMap = {}; // Para asignar un color consistente a cada asignatura

            const horarioData = @json($horarioData); // Datos del horario pasados desde el controlador

            // Debugging: Log the received horarioData
            console.log('Horario Data recibida:', horarioData);

            // Función para convertir HH:MM a minutos desde la medianoche
            function timeToMinutes(timeStr) {
                const [h, m] = timeStr.split(':').map(Number);
                return h * 60 + m;
            }

            // Función para encontrar el inicio del intervalo de celda que contiene la hora real
            function findContainingIntervalStart(actualTimeStr, intervalTimes) {
                const actualMinutes = timeToMinutes(actualTimeStr);
                let containingTime = null;

                // Extraer solo la hora de inicio de los rangos de intervalTimes para la comparación
                const intervalStartTimes = intervalTimes.map(range => range.split(' - ')[0]);

                // Sort intervalStartTimes to ensure we find the latest possible starting interval
                // that is less than or equal to the actual time.
                intervalStartTimes.sort((a, b) => timeToMinutes(a) - timeToMinutes(b));

                for (let i = 0; i < intervalStartTimes.length; i++) {
                    const intervalMinutes = timeToMinutes(intervalStartTimes[i]);
                    if (actualMinutes >= intervalMinutes) {
                        containingTime = intervalStartTimes[i];
                    } else {
                        break;
                    }
                }
                return containingTime;
            }

            // Obtener todos los intervalos de tiempo válidos de las celdas de la tabla para comparación
            // Ahora las celdas de hora-col contienen el rango, necesitamos solo el inicio para el ID
            const validIntervals = Array.from(document.querySelectorAll('.horario-table td.hora-col'))
                                    .map(td => td.textContent.trim());
            console.log('Rangos de celdas válidos en la tabla (Horas):', validIntervals);


            horarioData.forEach(function(horario) {
                const diaSemana = horario.dia_semana;
                const actualHoraInicioStr = horario.hora_inicio; // La hora de inicio real del horario
                const bloques = horario.bloques;
                const asignaturaId = horario.asignatura_id;
                const asignaturaName = horario.asignatura_name;
                const semestreNumero = horario.semestre_numero;
                const seccionCodigo = horario.seccion_codigo;
                const aulaName = horario.aula_name;

                // Asignar un color a la asignatura si no tiene uno ya
                if (!colorMap[asignaturaId]) {
                    const nextColorIndex = Object.keys(colorMap).length % colorClasses.length;
                    colorMap[asignaturaId] = colorClasses[nextColorIndex];
                }
                const colorClass = colorMap[asignaturaId];

                const horaFinStr = calcularHoraFin(actualHoraInicioStr, bloques);

                // Encontrar la hora de inicio de la CELDA en la que debe ir este bloque
                const baseCellTimeStr = findContainingIntervalStart(actualHoraInicioStr, validIntervals);

                if (!baseCellTimeStr) {
                    console.warn(`No se pudo encontrar un intervalo de celda base para la hora de inicio: ${actualHoraInicioStr}. Este horario no se dibujará.`);
                    return; // Saltar este bloque si no se encuentra una celda base
                }

                // Calcular el offset Top dentro de la celda
                const actualMinutes = timeToMinutes(actualHoraInicioStr);
                const baseMinutes = timeToMinutes(baseCellTimeStr);
                const offsetMinutes = actualMinutes - baseMinutes; // Minutos de desfase desde el inicio de la celda
                const offsetTop = (offsetMinutes / 45) * BASE_CELL_HEIGHT; // Posición en píxeles

                // Determinar la ID de la celda de la tabla donde se insertará el bloque
                const cellId = `celda-${diaSemana}-${baseCellTimeStr.replace(':', '')}`;
                console.log(`Intentando colocar bloque: Dia ${diaSemana}, Hora Inicio Real: ${actualHoraInicioStr}, Hora Celda Base: ${baseCellTimeStr}, ID Celda Buscada: ${cellId}, Offset Top: ${offsetTop}px`);

                const targetCell = document.getElementById(cellId);

                if (targetCell) {
                    console.log(`Celda encontrada para ${cellId}.`);
                    crearBloqueVisual(
                        targetCell,
                        asignaturaId,
                        asignaturaName,
                        semestreNumero,
                        seccionCodigo,
                        aulaName,
                        colorClass,
                        offsetTop,
                        bloques
                    );
                } else {
                    console.warn(`Celda NO encontrada en el DOM para el ID: ${cellId}. Esto puede deberse a que la hora base calculada (${baseCellTimeStr}) no existe como ID de celda en la tabla HTML.`);
                    console.warn(`Verifique si la hora de inicio en la base de datos (${actualHoraInicioStr}) y los intervalos de las celdas HTML (${validIntervals.join(', ')}) son consistentes.`);
                }
            });

            // Función para calcular la hora de fin
            function calcularHoraFin(horaInicio, bloques) {
                const [h, m] = horaInicio.split(':').map(Number);
                let totalMinutosFin = (h * 60 + m) + (bloques * 45);
                let horasFin = Math.floor(totalMinutosFin / 60) % 24;
                let minutosFin = totalMinutosFin % 60;
                return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
            }

            // Función para crear y añadir el bloque visual al horario
            function crearBloqueVisual(celda, asignaturaId, asignaturaName, semestreNumero, seccionCodigo, aulaName, colorClass, offsetTop, bloques) {
                const bloque = document.createElement('div');
                bloque.classList.add('bloque-horario', colorClass);
                bloque.style.height = `${bloques * BASE_CELL_HEIGHT}px`;
                bloque.style.top = `${offsetTop}px`; // Aplicar el offset

                bloque.innerHTML = `
                    <div class="bloque-contenido">
                        <div class="asignatura-nombre" title="${asignaturaName}">${asignaturaName}</div>
                        <div class="asignatura-details">
                            <div title="Semestre">Semestre: ${semestreNumero}</div>
                            <div title="Sección">Sección: ${seccionCodigo}</div>
                            <div title="Aula">Aula: ${aulaName}</div>
                        </div>
                    </div>
                `;
                celda.appendChild(bloque);
            }
        });
    </script>
</body>
</html>
