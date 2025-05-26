<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle de Horario</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <style>
        /* Estilos similares a create.blade.php pero ajustados para visualización */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: auto;
        }

        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1;
            overflow: visible;
            padding-bottom: 60px;
        }

        .bloque-horario {
            position: absolute;
            top: 0;
            left: 0;
            width: calc(100% - 8px);
            height: auto;
            z-index: 10;
            padding: 3px 5px;
            font-size: 0.7rem;
            color: white;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin: 2px;
            border-radius: 4px;
        }

        /* Colores para asignaturas (deben coincidir con create) */
        .bg-asignatura-1 { background: linear-gradient(135deg, #4e73df, #3a56c8); }
        .bg-asignatura-2 { background: linear-gradient(135deg, #1cc88a, #17a673); }
        .bg-asignatura-3 { background: linear-gradient(135deg, #36b9cc, #2a96a5); }
        .bg-asignatura-4 { background: linear-gradient(135deg, #f6c23e, #e0b12d); }
        .bg-asignatura-5 { background: linear-gradient(135deg, #e74a3b, #d62c1a); }
        .bg-asignatura-6 { background: linear-gradient(135deg, #858796, #6c6e7e); }
        .bg-asignatura-7 { background: linear-gradient(135deg, #5a5c69, #484a58); }

        /* Ajustes de tabla */
        #horarioTable thead th, #horarioTable tbody td, #horarioTable tbody th {
            vertical-align: top;
            padding: 4px;
            height: 40px;
            box-sizing: border-box;
            position: relative;
        }

        #horarioTable tbody {
            display: block;
            overflow-y: auto;
        }

        #horarioTable thead, #horarioTable tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .time-slot {
            background-color: #f8f9fa;
            position: sticky;
            left: 0;
            z-index: 1;
            width: 80px;
        }

        .bloque-contenido {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            overflow: hidden;
        }

        .bloque-contenido .asignatura-nombre {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.75rem;
        }

        .bloque-contenido .asignatura-details {
            font-size: 0.65rem;
            line-height: 1.2;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 text-primary">
                    <i class="fas fa-calendar-alt mr-2"></i>Detalle de Horario
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('horario.index') }}">Horarios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalle</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Horario: {{ $horario->seccion->codigo_seccion ?? 'N/A' }} - 
                        {{ $horario->periodo->nombre ?? 'N/A' }}
                    </h2>
                    <a href="{{ route('horario.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <p><strong>Carrera:</strong> {{ $horario->carrera->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Semestre:</strong> {{ $horario->semestre->numero ?? 'N/A' }}°</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Turno:</strong> {{ $horario->turno->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Coordinador:</strong> {{ $horario->coordinador->name ?? 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="horarioTable">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center time-slot">Hora</th>
                                <th class="text-center">Lunes</th>
                                <th class="text-center">Martes</th>
                                <th class="text-center">Miércoles</th>
                                <th class="text-center">Jueves</th>
                                <th class="text-center">Viernes</th>
                                <th class="text-center">Sábado</th>
                            </tr>
                        </thead>
                        <tbody id="horarioBody">
                            @foreach($horas as $hora)
                                <tr>
                                    <th class="time-slot">{{ $hora['inicio'] }}</th>
                                    @for($dia = 1; $dia <= 6; $dia++)
                                        <td data-hora="{{ $hora['inicio'] }}" data-dia="{{ $dia }}" style="height: 40px; position: relative;"></td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const BASE_CELL_HEIGHT = 40; // Debe coincidir con el CSS
            
            // Procesar los bloques del horario y colocarlos en la tabla
            @foreach($bloques as $bloque)
                {
                    const dia = {{ $bloque->dia_semana }};
                    const horaInicio = '{{ $bloque->hora_inicio }}';
                    const bloques = {{ $bloque->bloques }};
                    const horaFin = calcularHoraFin(horaInicio, bloques);
                    
                    // Encontrar la fila correspondiente a la hora de inicio
                    const filas = document.querySelectorAll('#horarioBody tr');
                    let filaInicio = null;
                    let rowIndex = -1;
                    
                    filas.forEach((fila, index) => {
                        const horaFila = fila.querySelector('.time-slot').textContent.trim();
                        if (horaFila === horaInicio) {
                            filaInicio = fila;
                            rowIndex = index;
                        }
                    });
                    
                    if (filaInicio) {
                        const celda = filaInicio.querySelector(`td[data-dia="${dia}"]`);
                        if (celda) {
                            crearBloqueVisual(
                                celda,
                                '{{ $bloque->asignatura->asignatura_id }}',
                                '{{ $bloque->asignatura->name }}',
                                dia,
                                horaInicio,
                                horaFin,
                                bloques,
                                '{{ $bloque->tipo_horas }}',
                                '{{ $bloque->docente->cedula_doc ?? "" }}',
                                'bg-asignatura-{{ ($loop->index % 7) + 1 }}',
                                rowIndex,
                                dia
                            );
                        }
                    }
                }
            @endforeach
            
            function calcularHoraFin(horaInicio, bloques) {
                const [h, m] = horaInicio.split(':').map(Number);
                let totalMinutosFin = (h * 60 + m) + (bloques * 45);
                let horasFin = Math.floor(totalMinutosFin / 60) % 24; 
                let minutosFin = totalMinutosFin % 60;
                return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
            }
            
            function crearBloqueVisual(celda, asignaturaId, asignaturaName, dia, horaInicio, horaFin, bloques, tipoHoras, docenteId, colorClass, rowIndex, colIndex) {
                const bloque = document.createElement('div');
                bloque.classList.add('bloque-horario', colorClass);
                bloque.style.height = `${bloques * BASE_CELL_HEIGHT}px`;
                
                // Obtener nombre corto del docente
                const docenteName = '{{ $bloque->docente->name ?? "N/A" }}'.split(' ')[0];
                
                bloque.innerHTML = `
                    <div class="bloque-contenido">
                        <div class="asignatura-nombre" title="${asignaturaName}">${asignaturaName}</div>
                        <div class="asignatura-details">
                            <div title="${tipoHoras} - ${bloques} bloques">${tipoHoras} (${bloques}b)</div>
                            <div title="Docente: {{ $bloque->docente->name ?? 'N/A' }}">Doc: ${docenteName}</div>
                            <div>${horaInicio} - ${horaFin}</div>
                        </div>
                    </div>
                `;
                
                celda.appendChild(bloque);
            }
        });
    </script>
</body>
</html>