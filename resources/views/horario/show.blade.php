<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle de Horario</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; overflow: auto; }
        .content-wrapper { padding: 20px; } /* Añadir padding al content-wrapper */
        .bloque-horario {
            position: absolute; top: 0; left: 0; width: calc(100% - 8px); z-index: 10;
            padding: 3px 5px; font-size: 0.7rem; color: white; overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1); display: flex; flex-direction: column;
            justify-content: space-between; margin: 2px; border-radius: 4px;
            cursor: pointer; /* Para indicar que es clickeable si hay futuras interacciones */
        }
        .bloque-contenido {
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        .asignatura-nombre {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }
        .asignatura-details {
            font-size: 0.65rem;
            line-height: 1.2;
        }
        .table-horario th, .table-horario td {
            text-align: center;
            vertical-align: middle;
            position: relative; /* Para posicionar los bloques absolutos */
            padding: 0; /* Eliminar padding para que el bloque ocupe todo el espacio */
            height: 45px; /* Altura base para un bloque */
            /* min-width: 100px; REMOVED */
        }
        .table-horario thead th {
            background-color: #343a40;
            color: white;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .table-horario tbody th { /* Horas */
            background-color: #495057;
            color: white;
            position: sticky;
            left: 0;
            z-index: 15;
            min-width: 80px; /* Ancho para la columna de horas */
        }
        .table-horario td:first-child { /* Fix for first cell (corner) */
            z-index: 25;
        }

        /* Colores para bloques (ejemplos, puedes ajustarlos) */
        .color-0 { background-color: #e67e22; } /* Naranja */
        .color-1 { background-color: #28a745; } /* Verde éxito */
        .color-2 { background-color: #007bff; } /* Azul primario */
        .color-3 { background-color: #6f42c1; } /* Púrpura */
        .color-4 { background-color: #dc3545; } /* Rojo peligro */
        .color-5 { background-color: #17a2b8; } /* Cian información */
        .color-6 { background-color: #fd7e14; } /* Naranja oscuro */
        .color-7 { background-color: #20c997; } /* Teal */
        .color-8 { background-color: #6610f2; } /* Indigo */
        .color-9 { background-color: #e83e8c; } /* Rosa */
        .color-10 { background-color: #6c757d; } /* Gris */

        /* Estilos para la nueva sección de Datos Generales */
        .info-card .card-body {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }
        .info-item {
            font-size: 0.95rem;
            padding: 5px;
            border-radius: 5px;
            background-color: #f8f9fa; /* Light background for each item */
            border: 1px solid #e9ecef;
        }
        .info-item strong {
            display: block;
            margin-bottom: 2px;
            color: #343a40;
        }
        .info-item span {
            color: #6c757d;
        }

        /* Estilos para el apartado de Coordinador */
        .coordinador-card .card-body {
            padding: 10px 15px; /* Reduce padding */
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; /* Allow wrapping on small screens */
        }
        .coordinador-info {
            font-size: 0.9rem; /* Smaller font size */
            margin: 0; /* Remove default paragraph margin */
        }
        .coordinador-info strong {
            color: #343a40;
        }
        .coordinador-info span {
            color: #6c757d;
        }

        /* Estilos para impresión */
        @media print {
            body {
                overflow: visible !important; /* Asegura que no haya scroll y todo se imprima */
                margin: 0;
                padding: 0;
            }
            .wrapper, .content-wrapper, .content {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .main-header, .main-sidebar, .main-footer, .breadcrumb, .btn-info, hr {
                display: none !important; /* Oculta elementos no deseados en la impresión */
            }
            .card {
                border: 1px solid #dee2e6 !important; /* Asegura bordes en las cards */
                box-shadow: none !important;
                margin-bottom: 10px !important;
            }
            .card-header {
                background-color: #e9ecef !important; /* Fondo claro para cabeceras de card */
                color: #343a40 !important;
                border-bottom: 1px solid #dee2e6 !important;
            }
            .table-responsive {
                overflow-x: visible !important; /* Evita el scroll horizontal en impresión */
            }
            .table-horario {
                width: 100%;
                table-layout: fixed; /* Distribuye el ancho de las columnas de manera uniforme */
            }
            .table-horario th, .table-horario td {
                font-size: 0.65rem; /* Reduce el tamaño de fuente para que quepa más */
                padding: 1px 2px; /* Reduce el padding de las celdas */
                height: auto; /* Permite que la altura se ajuste al contenido */
                min-width: unset; /* Elimina min-width para mayor flexibilidad */
            }
            .table-horario tbody th {
                min-width: 60px; /* Ajusta el ancho de la columna de horas para impresión */
            }
            .bloque-horario {
                font-size: 0.6rem; /* Reduce aún más la fuente dentro de los bloques */
                padding: 1px 2px;
                margin: 0; /* Elimina márgenes extra */
                border-radius: 0; /* Elimina bordes redondeados si es necesario */
            }
            .asignatura-nombre {
                font-size: 0.7rem; /* Ajusta el tamaño de fuente del nombre de asignatura */
            }
            .asignatura-details {
                font-size: 0.55rem; /* Ajusta el tamaño de fuente de los detalles */
            }
            /* Asegurar que los colores de fondo se impriman */
            .color-0, .color-1, .color-2, .color-3, .color-4, .color-5, .color-6, .color-7, .color-8, .color-9, .color-10 {
                -webkit-print-color-adjust: exact; /* Para Chrome/Safari */
                print-color-adjust: exact; /* Para otros navegadores */
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0 !important;">
            <section class="content">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <h3 class="text-primary mt-4">
                                <i class="fas fa-calendar-check mr-2"></i>Detalle de Horario
                            </h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('horario.index') }}">Horarios</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Detalle</li>
                                </ol>
                            </nav>
                            <hr>
                            <button type="button" class="btn btn-info btn-sm mt-2" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Imprimir Horario
                            </button>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8"> {{-- Aumentado a md-8 para más espacio en datos generales --}}
                            <div class="card card-primary card-outline info-card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Datos Generales del Horario</h3>
                                </div>
                                <div class="card-body">
                                    <div class="info-item">
                                        <strong>Periodo:</strong>
                                        <span>{{ $horario->periodo->nombre }}</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Carrera:</strong>
                                        <span>{{ $horario->carrera->name }}</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Semestre:</strong>
                                        <span>{{ $horario->semestre->numero }}º Semestre</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Turno:</strong>
                                        <span>{{ $horario->turno->nombre }}</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Sección:</strong>
                                        <span>{{ $horario->seccion->codigo_seccion }}</span>
                                    </div>
                                    {{-- Si tienes asignatura_compartida_id y quieres mostrarla --}}
                                    @if ($horario->asignatura_compartida_id)
                                        <div class="info-item">
                                            <strong>Asignatura Compartida:</strong>
                                            <span>{{ $horario->asignatura_compartida_id }}</span> {{-- Aquí podrías mostrar el nombre de la asignatura si cargas su relación --}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"> {{-- Reducido a md-4 para el coordinador --}}
                            <div class="card card-secondary card-outline coordinador-card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-user-tie mr-2"></i>Coordinador</h3>
                                </div>
                                <div class="card-body">
                                    <p class="coordinador-info mb-0">
                                        <strong>Cédula:</strong> {{ $horario->coordinador->cedula }} -
                                        <strong>Nombre:</strong> {{ $horario->coordinador->name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-calendar-day mr-2"></i>Malla de Horario
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" style="overflow-x: auto;"> {{-- Eliminado max-height y overflow-y: auto --}}
                                        <table class="table table-bordered table-horario">
                                            <thead>
                                                <tr>
                                                    <th>Hora</th>
                                                    @foreach ($diasSemana as $dia)
                                                        <th>{{ $dia }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($horas as $hora)
                                                    <tr>
                                                        <th>{{ $hora['inicio'] }} - {{ $hora['fin'] }}</th>
                                                        @foreach ($diasSemana as $indexDia => $dia)
                                                            <td id="celda-{{ $indexDia + 1 }}-{{ str_replace(':', '', $hora['inicio']) }}"
                                                                data-dia="{{ $indexDia + 1 }}"
                                                                data-hora-inicio="{{ $hora['inicio'] }}">
                                                                </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-chalkboard-teacher mr-2"></i>Docentes y Asignaturas en este Horario
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped" id="asignaturas-docentes-table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Código Asignatura</th>
                                                <th>Nombre Asignatura</th>
                                                <th>Docente</th>
                                                <th>Teléfono Docente</th>
                                                <th>Correo Docente</th>
                                                <th>Aula</th> {{-- Nueva columna para Aula --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div></section>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const BASE_CELL_HEIGHT = 45; // Altura base de una celda en píxeles

            // Diccionario para asignar colores consistentes a las asignaturas
            const asignaturaColors = {};
            let colorIndex = 0;
            const colors = [
                'color-0', 'color-1', 'color-2', 'color-3', 'color-4',
                'color-5', 'color-6', 'color-7', 'color-8', 'color-9', 'color-10'
            ];

            // Datos de los bloques de horario pasados desde el controlador
            const bloquesData = @json($bloques);

            // Objeto para almacenar asignaturas y docentes únicos
            const asignaturasDocentesUnicos = {};

            bloquesData.forEach(bloque => {
                const diaSemana = bloque.dia_semana;
                const horaInicio = bloque.hora_inicio.substring(0, 5); // HH:MM
                const bloquesCount = bloque.bloques;
                const tipoHoras = bloque.tipo_horas;

                const asignaturaId = bloque.asignatura ? bloque.asignatura.asignatura_id : 'N/A';
                const asignaturaName = bloque.asignatura ? bloque.asignatura.name : 'Asignatura Desconocida';
                const docenteName = bloque.docente ? bloque.docente.name : 'Docente Desconocido';
                const docenteTelefono = bloque.docente ? bloque.docente.telefono : 'N/A';
                const docenteCorreo = bloque.docente ? bloque.docente.email : 'N/A';
                const docenteCedula = bloque.docente ? bloque.docente.cedula_doc : 'N/A';
                const aulaName = bloque.aula ? bloque.aula.nombre : 'N/A'; // OBTENER NOMBRE DEL AULA


                // Asignar un color a la asignatura si no tiene uno
                if (!asignaturaColors[asignaturaId]) {
                    asignaturaColors[asignaturaId] = colors[colorIndex % colors.length];
                    colorIndex++;
                }
                const colorClass = asignaturaColors[asignaturaId];

                // Calcular hora fin
                const horaFin = calcularHoraFin(horaInicio, bloquesCount);

                // Obtener la celda donde se insertará el bloque
                const cellId = `celda-${diaSemana}-${horaInicio.replace(':', '')}`;
                const targetCell = document.getElementById(cellId);

                if (targetCell) {
                    crearBloqueVisual(
                        targetCell,
                        asignaturaId,
                        asignaturaName,
                        diaSemana,
                        horaInicio,
                        horaFin,
                        bloquesCount,
                        tipoHoras,
                        docenteName,
                        aulaName, // PASAR NOMBRE DEL AULA
                        colorClass,
                        targetCell.rowIndex,
                        targetCell.cellIndex
                    );
                } else {
                    console.warn(`Celda no encontrada: ${cellId}`);
                }

                // Almacenar datos de asignaturas y docentes para la nueva tabla
                if (bloque.asignatura && bloque.docente) {
                    const key = `${asignaturaId}-${docenteCedula}`; // Clave única por asignatura y docente
                    if (!asignaturasDocentesUnicos[key]) {
                        asignaturasDocentesUnicos[key] = {
                            asignatura_codigo: asignaturaId,
                            asignatura_nombre: asignaturaName,
                            docente_nombre: docenteName,
                            docente_telefono: docenteTelefono,
                            docente_correo: docenteCorreo,
                            aula_nombre: aulaName // AÑADIR NOMBRE DEL AULA
                        };
                    }
                }
            });

            // Rellenar la tabla de asignaturas y docentes
            const tablaAsignaturasDocentesBody = $('#asignaturas-docentes-table tbody');
            Object.values(asignaturasDocentesUnicos).forEach(item => {
                const row = `
                    <tr>
                        <td>${item.asignatura_codigo}</td>
                        <td>${item.asignatura_nombre}</td>
                        <td>${item.docente_nombre}</td>
                        <td>${item.docente_telefono}</td>
                        <td>${item.docente_correo}</td>
                        <td>${item.aula_nombre}</td> {{-- Renderizar el nombre del aula --}}
                    </tr>
                `;
                tablaAsignaturasDocentesBody.append(row);
            });


            // Función para calcular la hora de fin
            function calcularHoraFin(horaInicio, bloques) {
                const [h, m] = horaInicio.split(':').map(Number);
                let totalMinutosFin = (h * 60 + m) + (bloques * 45);
                let horasFin = Math.floor(totalMinutosFin / 60) % 24; // Asegura que las horas no excedan 23
                let minutosFin = totalMinutosFin % 60;
                return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
            }

            // Función para crear y añadir el bloque visual al horario
            function crearBloqueVisual(celda, asignaturaId, asignaturaName, dia, horaInicio, horaFin, bloques, tipoHoras, docenteName, aulaName, colorClass, rowIndex, colIndex) {
                const bloque = document.createElement('div');
                bloque.classList.add('bloque-horario', colorClass);
                bloque.style.height = `${bloques * BASE_CELL_HEIGHT}px`;
                bloque.innerHTML = `
                    <div class="bloque-contenido">
                        <div class="asignatura-nombre" title="${asignaturaName}">${asignaturaName}</div>
                        <div class="asignatura-details">
                            <div title="${tipoHoras}">${tipoHoras}</div>
                            <div title="Aula">${aulaName}</div>
                        </div>
                    </div>
                `;
                celda.appendChild(bloque);
            }
        });
    </script>
</body>
</html>
