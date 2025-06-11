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
    {{-- Assuming 'adminlte.css' is available in the 'public/dist/css/' path --}}
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; overflow: auto; }
        .content-wrapper { padding: 20px; } /* Add padding to content-wrapper */
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
            table-layout: fixed; /* Ensures columns have the same width */
            border-collapse: separate; /* Allows border-spacing */
            border-spacing: 0 5px; /* Vertical spacing between rows */
        }
        .horario-table th, .horario-table td {
            border: 1px solid #dee2e6;
            padding: 0; /* Remove default padding to control content */
            vertical-align: top;
            position: relative; /* Required for absolute positioning of blocks */
            height: 45px; /* Base height for a 45-minute block */
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
            width: 120px; /* Adjusted width for hour range format */
        }
        .horario-container {
            overflow-x: auto; /* Allows horizontal scrolling on small screens */
        }

        /* Color classes for schedule blocks */
        .bg-color-1 { background-color: #6a0572; } /* Dark Purple */
        .bg-color-2 { background-color: #007bff; } /* Bootstrap Blue */
        .bg-color-3 { background-color: #28a745; } /* Bootstrap Green */
        .bg-color-4 { background-color: #ffc107; color: #343a40; } /* Bootstrap Yellow */
        .bg-color-5 { background-color: #dc3545; } /* Bootstrap Red */
        .bg-color-6 { background-color: #6f42c1; } /* Purple */
        .bg-color-7 { background-color: #fd7e14; } /* Orange */
        .bg-color-8 { background-color: #20c997; } /* Turquoise */
        .bg-color-9 { background-color: #e83e8c; } /* Pink */
        .bg-color-10 { background-color: #17a2b8; } /* Cyan */
        .bg-color-11 { background-color: #343a40; } /* Dark Gray */
        .bg-color-12 { background-color: #007bff; } /* Blue (repeated for more options) */
        /* Add more colors if you have many subjects */

        /* Print Styles */
        @media print {
            body, html {
                margin: 0 !important;
                padding: 0 !important;
            }
            .wrapper, .content-wrapper, .content, .container-fluid, .row, .col-12 {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .main-header, .main-sidebar, .main-footer, .breadcrumb, .alert, .btn { /* Hide navigation, alerts, and buttons */
                display: none !important;
            }
            /* New section for printing teacher name and period */
            .print-header-info {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
            }
            .print-header-info h2 {
                font-size: 1.5rem;
                margin-bottom: 5px;
            }
            .print-header-info p {
                font-size: 1rem;
                margin-bottom: 0;
            }
            /* End new section */

            .card {
                border: 1px solid #dee2e6 !important; /* Ensure borders on cards */
                box-shadow: none !important;
                margin-bottom: 10px !important;
            }
            .card-header {
                background-color: #e9ecef !important; /* Light background for card headers */
                color: #343a40 !important;
                border-bottom: 1px solid #dee2e6 !important;
            }
            .horario-container {
                overflow-x: visible !important; /* Prevent horizontal scroll in print */
            }
            .horario-table {
                width: 100%;
                table-layout: fixed; /* Distribute column width uniformly */
            }
            .horario-table th, .horario-table td {
                font-size: 0.65rem; /* Reduce font size to fit more */
                padding: 1px 2px; /* Reduce cell padding */
                height: auto; /* Allow height to adjust to content */
                min-width: unset; /* Remove min-width for more flexibility */
                border: 1px solid #dee2e6 !important; /* Ensures the lines are visible */
            }
            .horario-table td.hora-col {
                min-width: 60px; /* Adjust hour column width for printing */
            }
            .bloque-horario {
                font-size: 0.6rem; /* Further reduce font inside blocks */
                padding: 1px 2px;
                margin: 0; /* Remove extra margins */
                border-radius: 0; /* Remove rounded borders if necessary */
                background-color: #fff !important; /* White blocks when printing */
                color: #343a40 !important; /* Dark text for contrast */
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
            /* Force white background for all color blocks when printing */
            .bg-color-1, .bg-color-2, .bg-color-3, .bg-color-4, .bg-color-5,
            .bg-color-6, .bg-color-7, .bg-color-8, .bg-color-9, .bg-color-10,
            .bg-color-11, .bg-color-12 {
                background-color: #fff !important;
                color: #343a40 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                border: 1px solid #dee2e6 !important;
            }
            .asignatura-nombre {
                font-size: 0.7rem; /* Adjust subject name font size */
            }
            .asignatura-details {
                font-size: 0.55rem; /* Adjust details font size */
            }
        }
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
                    {{-- Section for action buttons, including print --}}
                    <div class="row mb-4">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <a href="{{ route('docente.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                                <i class="fas fa-arrow-left me-2"></i>Volver a la Lista de Docentes
                            </a>
                            <button type="button" class="btn btn-info rounded-pill px-4 fw-bold" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Imprimir Horario
                            </button>
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
                    {{-- Print-only header for teacher name and period --}}
                    <div class="print-header-info" style="display: none;">
                        <h2>Horario del Docente: {{ $docente->name }}</h2>
                        <p>Período: {{ $periodo->nombre }}</p>
                    </div>

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
                                        @foreach($horasDia as $horaRango) {{-- Iterate over the hour range --}}
                                            <tr>
                                                <td class="hora-col">{{ $horaRango }}</td>
                                                @php
                                                    // Extract the start hour of the range to use in the cell ID
                                                    $horaInicioCelda = explode(' - ', $horaRango)[0];
                                                @endphp
                                                @foreach($diasSemana as $diaNum => $diaNombre)
                                                    <td id="celda-{{ $diaNum }}-{{ str_replace(':', '', $horaInicioCelda) }}">
                                                        {{-- Schedule blocks will be inserted here with JavaScript --}}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- The old "Volver" button at the bottom is now moved to the top. Removed here. --}}
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const BASE_CELL_HEIGHT = 45; // Base cell height in pixels (45 minutes)

            // Predefined colors for subjects
            const colorClasses = [
                'bg-color-1', 'bg-color-2', 'bg-color-3', 'bg-color-4', 'bg-color-5',
                'bg-color-6', 'bg-color-7', 'bg-color-8', 'bg-color-9', 'bg-color-10',
                'bg-color-11', 'bg-color-12'
            ];
            let colorMap = {}; // To assign a consistent color to each subject

            const horarioData = @json($horarioData); // Schedule data passed from the controller

            // Debugging: Log the received horarioData
            console.log('Horario Data received:', horarioData);

            // Function to convert HH:MM to minutes from midnight
            function timeToMinutes(timeStr) {
                const [h, m] = timeStr.split(':').map(Number);
                return h * 60 + m;
            }

            // Function to find the start of the cell interval that contains the actual time
            function findContainingIntervalStart(actualTimeStr, intervalTimes) {
                const actualMinutes = timeToMinutes(actualTimeStr);
                let containingTime = null;

                // Extract only the start hour from the intervalTimes ranges for comparison
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

            // Get all valid time intervals from the table cells for comparison
            // Now the hora-col cells contain the range, we only need the start for the ID
            const validIntervals = Array.from(document.querySelectorAll('.horario-table td.hora-col'))
                                    .map(td => td.textContent.trim());
            console.log('Valid cell ranges in the table (Hours):', validIntervals);


            horarioData.forEach(function(horario) {
                const diaSemana = horario.dia_semana;
                const actualHoraInicioStr = horario.hora_inicio; // The actual start time of the schedule
                const bloques = horario.bloques;
                const asignaturaId = horario.asignatura_id;
                const asignaturaName = horario.asignatura_name;
                const semestreNumero = horario.semestre_numero;
                const seccionCodigo = horario.seccion_codigo;
                const aulaName = horario.aula_name;

                // Assign a color to the subject if it doesn't have one yet
                if (!colorMap[asignaturaId]) {
                    const nextColorIndex = Object.keys(colorMap).length % colorClasses.length;
                    colorMap[asignaturaId] = colorClasses[nextColorIndex];
                }
                const colorClass = colorMap[asignaturaId];

                const horaFinStr = calcularHoraFin(actualHoraInicioStr, bloques);

                // Find the start hour of the CELL where this block should go
                const baseCellTimeStr = findContainingIntervalStart(actualHoraInicioStr, validIntervals);

                if (!baseCellTimeStr) {
                    console.warn(`Could not find a base cell interval for the start time: ${actualHoraInicioStr}. This schedule will not be drawn.`);
                    return; // Skip this block if no base cell is found
                }

                // Calculate the Top offset within the cell
                const actualMinutes = timeToMinutes(actualHoraInicioStr);
                const baseMinutes = timeToMinutes(baseCellTimeStr);
                const offsetMinutes = actualMinutes - baseMinutes; // Offset minutes from the start of the cell
                const offsetTop = (offsetMinutes / 45) * BASE_CELL_HEIGHT; // Position in pixels

                // Determine the ID of the table cell where the block will be inserted
                const cellId = `celda-${diaSemana}-${baseCellTimeStr.replace(':', '')}`;
                console.log(`Attempting to place block: Day ${diaSemana}, Actual Start Time: ${actualHoraInicioStr}, Base Cell Time: ${baseCellTimeStr}, Searched Cell ID: ${cellId}, Offset Top: ${offsetTop}px`);

                const targetCell = document.getElementById(cellId);

                if (targetCell) {
                    console.log(`Cell found for ${cellId}.`);
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
                    console.warn(`Cell NOT found in the DOM for ID: ${cellId}. This may be because the calculated base time (${baseCellTimeStr}) does not exist as a cell ID in the HTML table.`);
                    console.warn(`Verify if the start time in the database (${actualHoraInicioStr}) and the HTML cell intervals (${validIntervals.join(', ')}) are consistent.`);
                }
            });

            // Function to calculate end time
            function calcularHoraFin(horaInicio, bloques) {
                const [h, m] = horaInicio.split(':').map(Number);
                let totalMinutosFin = (h * 60 + m) + (bloques * 45);
                let horasFin = Math.floor(totalMinutosFin / 60) % 24;
                let minutosFin = totalMinutosFin % 60;
                return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
            }

            // Function to create and add the visual block to the schedule
            function crearBloqueVisual(celda, asignaturaId, asignaturaName, semestreNumero, seccionCodigo, aulaName, colorClass, offsetTop, bloques) {
                const bloque = document.createElement('div');
                bloque.classList.add('bloque-horario', colorClass);
                bloque.style.height = `${bloques * BASE_CELL_HEIGHT}px`;
                bloque.style.top = `${offsetTop}px`; // Apply the offset

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
