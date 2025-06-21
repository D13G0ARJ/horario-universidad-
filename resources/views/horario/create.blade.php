<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Horario</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <style>
        /* Permitir scroll globalmente */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: auto;
            /* Permitir scroll */
            display: flex;
            flex-direction: column;
        }

        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Asegurar que el wrapper sea al menos el alto de la ventana */
            overflow: visible;
            /* Permitir que el contenido desborde si es necesario */
        }

        .content-wrapper {
            flex: 1;
            overflow: visible;
            /* Permitir scroll dentro del content-wrapper si es necesario */
            padding-bottom: 60px;
        }

        .main-sidebar {
            height: 100vh;
            overflow-y: hidden;
            /* Mantener oculto el scroll sde la sidebar si no es relevante */
            position: fixed;
        }

        .content {
            margin-top: 60px;
            overflow: visible;
            /* Permitir scroll dentro del content */
        }

        .asignatura-item.dragging {
            opacity: 0.5;
            transform: scale(0.98);
        }

        .drop-zone.hover-cell {
            background-color: rgba(0, 123, 255, 0.1) !important;
            border: 2px dashed #007bff !important;
        }

        /* MODIFICADO: Bloques de horario con posicionamiento absoluto */
        .bloque-horario {
            position: absolute;
            /* Posicionamiento absoluto */
            top: 0;
            left: 0;
            width: 100%;
            /* Ocupar el ancho de la celda */
            height: auto;
            /* La altura será definida por JS */
            z-index: 10;
            /* Asegurar que esté por encima de las celdas */
            padding: 3px 5px;
            /* Reducir padding interno */
            font-size: 0.7rem;
            /* Reducir tamaño de fuente */
            color: white;
            cursor: move;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .bloque-horario .btn {
            opacity: 1;
            transition: none;
        }

        .bg-asignatura-1 {
            background: linear-gradient(135deg, #4e73df, #3a56c8);
        }

        .bg-asignatura-2 {
            background: linear-gradient(135deg, #1cc88a, #17a673);
        }

        .bg-asignatura-3 {
            background: linear-gradient(135deg, #36b9cc, #2a96a5);
        }

        .bg-asignatura-4 {
            background: linear-gradient(135deg, #f6c23e, #e0b12d);
        }

        .bg-asignatura-5 {
            background: linear-gradient(135deg, #e74a3b, #d62c1a);
        }

        .bg-asignatura-6 {
            background: linear-gradient(135deg, #858796, #6c6e7e);
        }

        .bg-asignatura-7 {
            background: linear-gradient(135deg, #5a5c69, #484a58);
        }
        /* New class for shared assignment */
        .bg-shared-asignatura {
            background: linear-gradient(135deg, #6f42c1, #5a36a5); /* Purple */
        }


        /* Scrollbars personalizados para elementos específicos */
        .card-body::-webkit-scrollbar {
            width: 8px;
        }

        .card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .card-body::-webkit-scrollbar-thumb {
            background: #b0b0b0;
            border-radius: 10px;
        }

        .card-body::-webkit-scrollbar-thumb:hover {
            background: #888;
        }

        /* MODIFICADO: Ajustes de tabla para scroll y altura de celda reducida */
        #horarioTable thead th,
        #horarioTable tbody td,
        #horarioTable tbody th {
            vertical-align: top;
            padding: 4px;
            /* Padding reducido */
            height: 40px;
            /* Altura base de la celda reducida, coincidir con BASE_CELL_HEIGHT en JS */
            box-sizing: border-box;
            position: relative;
            /* Necesario para el posicionamiento absoluto de .bloque-horario */
        }

        #horarioTable tbody {
            display: block;
            overflow-y: auto;
            /* Permitir scroll en el cuerpo de la tabla */
        }

        #horarioTable thead,
        #horarioTable tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .time-slot {
            background-color: #f8f9fa;
            position: sticky;
            left: 0;
            z-index: 1;
            width: 120px; /* Ancho ajustado para el formato de rango de horas */
        }

        #asignaturasContainer {
            flex-grow: 1;
            overflow-y: auto;
            /* Permitir scroll en el contenedor de asignaturas */
        }

        .card-body.p-0>.table-responsive {
            flex-grow: 1;
            overflow-y: auto;
            /* Permitir scroll en el contenedor de la tabla */
        }

        .row.g-3 {
            height: auto;
        }

        .col-md-3 .card,
        .col-md-9 .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .col-md-3 .card-body,
        .col-md-9 .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        /* Estilo para el contenido del bloque de asignatura */
        .bloque-contenido {
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centrar verticalmente */
            align-items: center; /* Centrar horizontalmente */
            height: 100%;
            overflow: hidden;
            text-align: center; /* Asegurar texto centrado */
        }

        .bloque-contenido .asignatura-nombre {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.75rem;
            /* Ajustar si es necesario */
        }

        .bloque-contenido .asignatura-details {
            font-size: 0.65rem;
            /* Detalles más pequeños */
            line-height: 1.2;
        }

        .bloque-contenido .delete-btn {
            position: absolute; /* Posicionar el botón de eliminar */
            top: 2px;
            right: 2px;
            width: 18px !important;
            height: 18px !important;
            font-size: 0.6rem !important;
            padding: 0;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.7);
            color: #dc3545;
            border: none;
            cursor: pointer;
        }
        .bloque-contenido .delete-btn:hover {
            background-color: rgba(255, 255, 255, 0.9);
            color: #c82333;
        }

        /* Clase para celdas ocultas por rowspan (no usada con el nuevo enfoque, pero se mantiene por si acaso) */
        .hidden-cell {
            /* display: none; */
            /* Ya no ocultamos celdas */
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex flex-column align-items-center justify-content-center" style="min-height: 120px; padding:10px;">
        <div class="row mb-4 w-100 justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <div class="d-flex align-items-center justify-content-center gap-3 bg-white shadow rounded-4 px-4 py-4 border border-2 border-primary-subtle" style="width:100%;">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold" style="width:180px;">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <h1 class="h3 text-primary mb-0 fw-bold d-flex align-items-center justify-content-center w-100" style="letter-spacing:0.5px;">
                        <i class="fas fa-calendar-plus me-2" style="font-size:2rem;"></i>Crear Nuevo Horario
                    </h1>
                </div>
                
            </div>
        </div>

        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white py-3">
                <h2 class="h5 mb-0">
                    <i class="fas fa-calendar-alt mr-2"></i>Configuración del Horario
                </h2>
            </div>

            <form action="{{ route('horario.store') }}" method="POST" id="horarioForm">
                @csrf
                <input type="hidden" name="horario_data" id="horarioData">

                <div class="card-body p-4">
                    <div class="row g-3 mb-4 bg-light p-3 rounded">
                        {{-- Filtros: Periodo, Carrera, Turno, Semestre, Sección --}}
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="periodo_id" class="form-label fw-bold">Periodo</label>
                            <select id="periodo_id" name="periodo_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach ($periodos as $periodo)
                                    <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="carrera_id" class="form-label fw-bold">Carrera</label>
                            <select id="carrera_id" name="carrera_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}">{{ $carrera->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="turno_id" class="form-label fw-bold">Turno</label>
                            <select id="turno_id" name="turno_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach ($turnos as $turno)
                                    <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="semestre_id" class="form-label fw-bold">Semestre</label>
                            <select id="semestre_id" name="semestre_id" class="form-select form-select-lg" required
                                disabled>
                                <option value="">Seleccione turno</option>
                            </select>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="seccion_id" class="form-label fw-bold">Sección</label>
                            <select id="seccion_id" name="seccion_id" class="form-select form-select-lg" required
                                disabled>
                                <option value="">Complete filtros</option>
                            </select>
                        </div>

                        {{-- ELIMINADO: Campo de selección de Coordinador --}}
                        {{-- <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="coordinador_cedula" class="form-label fw-bold">Coordinador</label>
                            <select id="coordinador_cedula" name="coordinador_cedula" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach ($coordinadores as $coordinador)
                                    <option value="{{ $coordinador->cedula }}">{{ $coordinador->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary btn-lg w-100 py-2" id="buscarHorarios">
                                <i class="fas fa-search me-2"></i> Buscar
                            </button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3 d-flex flex-column">
                            <div class="card flex-grow-1 border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Asignaturas Disponibles</h5>
                                </div>
                                <div class="card-body p-2 overflow-auto" id="asignaturasContainer">
                                    <div class="list-group" id="listaAsignaturas">
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle me-2"></i>Seleccione filtros y haga clic en
                                            Buscar
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Botón y etiqueta para Asignatura Compartida --}}
                            <button type="button" class="btn btn-info btn-sm mt-3" id="btnAsignaturaCompartida" disabled>
                                <i class="fas fa-share-alt me-2"></i> Asignatura Compartida
                            </button>
                            <span id="shared_asignatura_label" class="badge bg-info ml-2 mt-2" style="display:none;"></span>
                        </div>

                        <div class="col-md-9 d-flex flex-column">
                            <div class="card flex-grow-1 border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Horario - Lunes a Sábado
                                    </h5>
                                </div>
                                <div class="card-body p-0 d-flex flex-column">
                                    <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                                        <table class="table table-bordered table-hover mb-0" id="horarioTable">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="text-center time-slot">Hora</th> {{-- Added time-slot class here --}}
                                                    <th class="text-center" style="width: 15%">Lunes</th>
                                                    <th class="text-center" style="width: 15%">Martes</th>
                                                    <th class="text-center" style="width: 15%">Miércoles</th>
                                                    <th class="text-center" style="width: 15%">Jueves</th>
                                                    <th class="text-center" style="width: 15%">Viernes</th>
                                                    <th class="text-center" style="width: 15%">Sábado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="horarioBody">
                                                <tr>
                                                    <td colspan="7" class="text-center py-5 text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>Complete los filtros y
                                                        haga clic en Buscar
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light p-3 text-end">
                    <button type="submit" class="btn btn-primary btn-lg px-4" id="guardarHorario" disabled>
                        <i class="fas fa-save me-2"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Asignatura Compartida --}}
    <div class="modal fade" id="modalAsignaturaCompartida" tabindex="-1" role="dialog"
        aria-labelledby="modalAsignaturaCompartidaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalAsignaturaCompartidaLabel">Seleccionar Asignatura Compartida</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Filtros para la modal de Asignatura Compartida --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="periodo_id_shared" class="form-label">Periodo</label>
                            <select class="form-control" id="periodo_id_shared">
                                <option value="">Seleccione...</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="carrera_id_shared" class="form-label">Carrera</label>
                            <select class="form-control" id="carrera_id_shared">
                                <option value="">Seleccione...</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}">{{ $carrera->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="turno_id_shared" class="form-label">Turno</label>
                            <select class="form-control" id="turno_id_shared">
                                <option value="">Seleccione...</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="semestre_id_shared" class="form-label">Semestre</label>
                            <select class="form-control" id="semestre_id_shared" disabled>
                                <option value="">Seleccione turno</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="seccion_id_shared" class="form-label">Sección</label>
                            <select class="form-control" id="seccion_id_shared" disabled>
                                <option value="">Complete filtros</option>
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary" id="buscarAsignaturasModalBtn">Buscar Asignaturas</button>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="asignatura_compartida_select">Asignatura a Compartir</label>
                        <select class="form-control" id="asignatura_compartida_select" disabled>
                            <option value="">Seleccione una Asignatura</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-info" id="btnGuardarAsignaturaCompartida">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Configurar Bloque de Horario --}}
    <div class="modal fade" id="horarioBlockModal" tabindex="-1" aria-labelledby="horarioBlockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="horarioBlockModalLabel"><i class="fas fa-cogs mr-2"></i>Configurar Bloque de Horario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="horarioBlockForm">
                        <input type="hidden" id="modalAsignaturaId">
                        <input type="hidden" id="modalDocenteId">
                        <input type="hidden" id="modalDiaSemana">
                        <input type="hidden" id="modalHoraInicio">
                        <input type="hidden" id="modalColorClass">
                        {{-- Campo oculto para almacenar la carga horaria completa de la asignatura --}}
                        <input type="hidden" id="modalCargaHorariaData">

                        <div class="mb-3">
                            <label for="modalAsignaturaNombre" class="form-label">Asignatura:</label>
                            <input type="text" class="form-control" id="modalAsignaturaNombre" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="modalDocenteNombre" class="form-label">Docente:</label>
                            <input type="text" class="form-control" id="modalDocenteNombre" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="modalTipoHoras" class="form-label">Tipo de Horas:</label>
                            <select class="form-select" id="modalTipoHoras">
                                <option value="">Seleccione tipo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="modalBloques" class="form-label">Cantidad de Bloques (45 min c/u):</label>
                            <select class="form-select" id="modalBloques">
                                {{-- Opciones se cargarán dinámicamente --}}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="modalAula" class="form-label">Aula:</label>
                            <select class="form-select" id="modalAula" required>
                                <option value="">Seleccione un Aula</option>
                                {{-- Las opciones de aula se cargarán dinámicamente con JavaScript --}}
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle mr-2"></i>Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveBlockBtn"><i class="fas fa-check-circle mr-2"></i>Guardar Bloque</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global variables
            const BASE_CELL_HEIGHT = 40; // Base cell height in px, must match CSS

            const periodoSelect = document.getElementById('periodo_id');
            const turnoSelect = document.getElementById('turno_id');
            const semestreSelect = document.getElementById('semestre_id');
            const carreraSelect = document.getElementById('carrera_id');
            const seccionSelect = document.getElementById('seccion_id');
            const buscarBtn = document.getElementById('buscarHorarios');
            const listaAsignaturas = document.getElementById('listaAsignaturas');
            const horarioBody = document.getElementById('horarioBody');
            const guardarBtn = document.getElementById('guardarHorario');

            // NEW ELEMENTS FOR SHARED ASSIGNMENT
            const btnAsignaturaCompartida = document.getElementById('btnAsignaturaCompartida');
            const modalAsignaturaCompartida = new bootstrap.Modal(document.getElementById('modalAsignaturaCompartida'));
            const asignaturaCompartidaSelect = document.getElementById('asignatura_compartida_select');
            const btnGuardarAsignaturaCompartida = document.getElementById('btnGuardarAsignaturaCompartida');
            const asignaturaCompartidaIdHidden = document.createElement('input'); // Hidden input for shared assignment ID
            asignaturaCompartidaIdHidden.type = 'hidden';
            asignaturaCompartidaIdHidden.id = 'asignatura_compartida_id';
            asignaturaCompartidaIdHidden.name = 'asignatura_compartida_id';
            document.getElementById('horarioForm').appendChild(asignaturaCompartidaIdHidden); // Append to form
            const sharedAsignaturaLabel = document.getElementById('shared_asignatura_label'); // Visual label for shared assignment

            // NEW ELEMENTS FOR SHARED ASSIGNMENT MODAL FILTERS
            const periodoSelectShared = document.getElementById('periodo_id_shared');
            const carreraSelectShared = document.getElementById('carrera_id_shared');
            const turnoSelectShared = document.getElementById('turno_id_shared');
            const semestreSelectShared = document.getElementById('semestre_id_shared');
            const seccionSelectShared = document.getElementById('seccion_id_shared');
            const buscarAsignaturasModalBtn = document.getElementById('buscarAsignaturasModalBtn');

            // Modal elements for block configuration
            const horarioBlockModal = new bootstrap.Modal(document.getElementById('horarioBlockModal'));
            const modalAsignaturaId = document.getElementById('modalAsignaturaId');
            const modalAsignaturaNombre = document.getElementById('modalAsignaturaNombre');
            const modalDocenteId = document.getElementById('modalDocenteId');
            const modalDocenteNombre = document.getElementById('modalDocenteNombre');
            const modalDiaSemana = document.getElementById('modalDiaSemana');
            const modalHoraInicio = document.getElementById('modalHoraInicio');
            const modalTipoHoras = document.getElementById('modalTipoHoras');
            const modalBloques = document.getElementById('modalBloques'); // Now a select
            const modalAula = document.getElementById('modalAula'); // New aula select
            const saveBlockBtn = document.getElementById('saveBlockBtn');
            const modalColorClass = document.getElementById('modalColorClass'); // Get the new hidden input
            // NUEVO: Campo oculto para almacenar la carga horaria completa de la asignatura en la modal
            const modalCargaHorariaData = document.getElementById('modalCargaHorariaData');


            // Object to track assigned hours per subject and type
            let assignedHoursPerSubject = {}; // Example: { "asignaturaId_teorica": 2, "asignaturaId_practica": 1 }

            // Global variable to track occupied cells (rowIndex, colIndex)
            let scheduleGrid = []; // scheduleGrid[rowIndex][colIndex] = { blockElementRef } or null

            // Array to store configured blocks for submission
            const bloquesParaGuardar = [];


            // 1. Load semesters based on selected shift
            turnoSelect.addEventListener('change', function() {
                const turnoId = this.value;
                console.log('Turno seleccionado:', turnoId); // Debugging log
                semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                semestreSelect.disabled = true;
                seccionSelect.innerHTML = '<option value="">Complete filtros</option>';
                seccionSelect.disabled = true;
                
                // Reset shared assignment related elements
                btnAsignaturaCompartida.disabled = true; 
                asignaturaCompartidaIdHidden.value = ''; 
                sharedAsignaturaLabel.style.display = 'none'; 
                sharedAsignaturaLabel.textContent = '';


                if (!turnoId) return;

                fetch(`{{ url('/horario/api/semestres-por-turno/') }}/${turnoId}`)
                    .then(response => {
                        console.log('Respuesta de semestres-por-turno:', response); // Debugging log
                        if (!response.ok) throw new Error(`Error al cargar semestres: ${response.statusText}`);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos de semestres recibidos:', data); // Debugging log
                        semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                        data.forEach(semestre => {
                            const option = new Option(`${semestre.numero}º Semestre`, semestre.id_semestre);
                            semestreSelect.add(option);
                        });
                        semestreSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error cargando semestres:', error);
                        semestreSelect.innerHTML = '<option value="">Error al cargar</option>';
                        Swal.fire('Error', `No se pudieron cargar los semestres: ${error.message}`, 'error'); // More specific error
                    });
            });

            // 2. Function to load sections
            async function cargarSecciones() {
                const carreraId = carreraSelect.value;
                const semestreId = semestreSelect.value;
                const turnoId = turnoSelect.value;

                console.log('Cargando secciones con:', { carreraId, semestreId, turnoId }); // Debugging log

                seccionSelect.innerHTML = '<option value="">Cargando...</option>';
                seccionSelect.disabled = true;

                // Reset shared assignment related elements
                btnAsignaturaCompartida.disabled = true; 
                asignaturaCompartidaIdHidden.value = ''; 
                sharedAsignaturaLabel.style.display = 'none'; 
                sharedAsignaturaLabel.textContent = '';

                if (!carreraId || !semestreId || !turnoId) {
                    seccionSelect.innerHTML = '<option value="">Complete filtros</option>';
                    return;
                }
                try {
                    const url = new URL(`{{ url('/horario/obtener-secciones') }}`);
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);
                    const response = await fetch(url);
                    console.log('Respuesta de obtener-secciones:', response); // Debugging log
                    if (!response.ok) throw new Error(`Error getting sections: ${response.statusText}`);
                    const data = await response.json();
                    console.log('Datos de secciones recibidos:', data); // Debugging log
                    seccionSelect.innerHTML = '<option value="">Seleccione sección</option>';
                    if (data.length > 0) {
                        data.forEach(s => {
                            const value = s.codigo_seccion || s.id;
                            const text = s.codigo_seccion || s.text;
                            seccionSelect.add(new Option(text, value));
                        });
                    } else {
                        seccionSelect.innerHTML = '<option value="">No hay secciones</option>';
                    }
                    seccionSelect.disabled = false;
                } catch (error) {
                    console.error('Error cargando secciones:', error);
                    Swal.fire('Error', `No se pudieron cargar las secciones: ${error.message}`, 'error'); // More specific error
                }
            }

            carreraSelect.addEventListener('change', cargarSecciones);
            semestreSelect.addEventListener('change', cargarSecciones);

            // 4. Main function when clicking Search
            buscarBtn.addEventListener('click', async function() {
                const periodoId = periodoSelect.value;
                const carreraId = carreraSelect.value;
                const turnoId = turnoSelect.value;
                const semestreId = semestreSelect.value;
                const seccionId = seccionSelect.value;

                console.log('Buscando horarios con:', { periodoId, carreraId, turnoId, semestreId, seccionId }); // Debugging log

                if (!periodoId || !carreraId || !turnoId || !semestreId || !seccionId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los filtros.', 'warning');
                    return;
                }

                // Reset assigned hours counter and schedule grid
                assignedHoursPerSubject = {};
                scheduleGrid = []; // Clear the grid on new search
                bloquesParaGuardar.length = 0; // Clear blocks for saving
                horarioBody.innerHTML = ''; // Clear existing blocks from table
                generarHorario(); // Regenerate the table with time slots

                listaAsignaturas.innerHTML =
                    `<div class="text-center py-4"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
                
                // Reset shared assignment related elements
                btnAsignaturaCompartida.disabled = true; 
                asignaturaCompartidaIdHidden.value = ''; 
                sharedAsignaturaLabel.style.display = 'none'; 
                sharedAsignaturaLabel.textContent = '';


                try {
                    const url = new URL(`{{ url('/horario/asignaturas') }}`);
                    url.searchParams.append('seccion_id', seccionId);
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);
                    url.searchParams.append('periodo_id', periodoId);

                    const response = await fetch(url);
                    console.log('Respuesta de asignaturas:', response); // Debugging log
                    if (!response.ok) throw new Error(`Error getting subjects: ${response.statusText}`);
                    const asignaturas = await response.json();
                    console.log('Datos de asignaturas recibidos:', asignaturas); // Debugging log

                    if (asignaturas.length > 0) {
                        listaAsignaturas.innerHTML = asignaturas.map((asignatura, index) => {
                            const colorClass = `bg-asignatura-${(index % 7) + 1}`;
                            // Display clearer hour load
                            let cargaHorariaText = 'No definida';
                            if (asignatura.carga_horaria && asignatura.carga_horaria.length > 0) {
                                cargaHorariaText = asignatura.carga_horaria.map(c => `${c.tipo.substring(0,1)}:${c.horas_academicas}b`).join(', '); // Example: T:4b, P:2b
                            }
                            
                            return `
                                <div class="list-group-item asignatura-item ${colorClass} text-white mb-2 py-2 px-3" 
                                     draggable="true" 
                                     data-asignatura-id="${asignatura.asignatura_id}"
                                     data-asignatura-name="${asignatura.name}"
                                     data-docentes='${JSON.stringify(asignatura.docentes)}'
                                     data-carga-horaria='${JSON.stringify(asignatura.carga_horaria)}'>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span style="font-size: 0.8rem;">${asignatura.name} <small>(${cargaHorariaText})</small></span>
                                        <i class="fas fa-arrows-alt ms-2"></i>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        configurarDragAndDrop();
                        // Enable the shared assignment button once assignments are loaded
                        btnAsignaturaCompartida.disabled = false; 
                    } else {
                        listaAsignaturas.innerHTML =
                            `<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle me-2"></i>No hay asignaturas.</div>`;
                    }
                } catch (error) {
                    console.error('Error en la búsqueda de asignaturas:', error); // More specific error log
                    Swal.fire('Error', `Ocurrió un error al cargar: ${error.message}`, 'error');
                    listaAsignaturas.innerHTML =
                        `<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error al cargar.</div>`;
                    horarioBody.innerHTML =
                        `<tr><td colspan="7" class="text-center py-5 text-muted">Error.</td></tr>`;
                }
            });

            // 5. Configure drag and drop
            function configurarDragAndDrop() {
                document.querySelectorAll('.asignatura-item').forEach(item => {
                    item.addEventListener('dragstart', function(e) {
                        e.dataTransfer.setData('text/plain', JSON.stringify({
                            asignatura_id: this.dataset.asignaturaId,
                            name: this.dataset.asignaturaName,
                            docentes: JSON.parse(this.dataset.docentes),
                            cargaHoraria: JSON.parse(this.dataset.cargaHoraria), // Array of objects {tipo, horas_academicas}
                            colorClass: Array.from(this.classList).find(cls => cls.startsWith(
                                'bg-asignatura-') || cls === 'bg-shared-asignatura') // Include new class
                        }));
                        this.classList.add('dragging');
                    });
                    item.addEventListener('dragend', function() {
                        this.classList.remove('dragging');
                    });
                });
            }

            // Function to convert time string to row index
            function getTimeRowIndex(timeStr) {
                const startHourTable = 7;
                const startMinutesTable = startHourTable * 60; // 420 minutes (7:00 AM)

                const [h, m] = timeStr.split(':').map(Number);
                const currentMinutes = h * 60 + m;

                // Calculate the row index based on 45-minute intervals from the start of the table
                return Math.floor((currentMinutes - startMinutesTable) / 45);
            }

            // 6. Generate schedule structure
            function generarHorario() {
                horarioBody.innerHTML = '';
                const startHour = 7;
                const endHour = 21; // The schedule ends at 21:00, so the last block can end here.

                let currentMinutes = startHour * 60; // Convert start hour to minutes from midnight (e.g., 7 * 60 = 420)
                const endMinutes = endHour * 60; // Convert end hour to minutes from midnight (e.g., 21 * 60 = 1260)
                const interval = 45; // 45 minutes interval

                let rowIndex = 0;
                while (currentMinutes < endMinutes) {
                    const hoursStart = Math.floor(currentMinutes / 60);
                    const minutesStart = currentMinutes % 60;
                    const horaInicioFormato = `${String(hoursStart).padStart(2, '0')}:${String(minutesStart).padStart(2, '0')}`;

                    const nextMinutes = currentMinutes + interval;
                    const hoursEnd = Math.floor(nextMinutes / 60);
                    const minutesEnd = nextMinutes % 60;
                    const horaFinFormato = `${String(hoursEnd).padStart(2, '0')}:${String(minutesEnd).padStart(2, '0')}`;
                    
                    // Format the time range for display
                    const horaRangoDisplay = `${horaInicioFormato} - ${horaFinFormato}`;

                    let fila = document.createElement('tr');
                    fila.innerHTML = `
                        <th class="time-slot" data-time-start="${horaInicioFormato}" data-time-end="${horaFinFormato}" data-row-index="${rowIndex}">${horaRangoDisplay}</th>
                        ${[1,2,3,4,5,6].map(dia => `<td class="drop-zone" data-hora="${horaInicioFormato}" data-dia="${dia}" data-row-index="${rowIndex}" data-col-index="${dia}"></td>`).join('')}
                    `;
                    horarioBody.appendChild(fila);
                    rowIndex++;

                    currentMinutes += interval; // Add 45 minutes for the next slot
                }

                // Initialize scheduleGrid with the actual number of rows generated.
                scheduleGrid = Array(rowIndex).fill(null).map(() => Array(7).fill(null)); // 7 days (0 unused, 1-6 for days)

                configurarCeldasHorario();
            }

            // 7. Configure schedule cells
            function configurarCeldasHorario() {
                document.querySelectorAll('.drop-zone').forEach(celda => {
                    celda.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.classList.add('hover-cell');
                    });
                    celda.addEventListener('dragleave', function() {
                        this.classList.remove('hover-cell');
                    });
                    celda.addEventListener('drop', handleDrop);
                });
            }

            // 8. Handle drop with hour validation
            async function handleDrop(e) {
                e.preventDefault();
                this.classList.remove('hover-cell');

                const transferData = e.dataTransfer.getData('text/plain');

                if (!transferData) {
                    Swal.fire('Error', 'No se pudo obtener la información de la asignatura arrastrada.', 'error');
                    return;
                }

                let data;
                try {
                    data = JSON.parse(transferData);
                } catch (error) {
                    Swal.fire('Error', 'Error al procesar la información de la asignatura.', 'error');
                    return;
                }

                const {
                    asignatura_id,
                    name: asignaturaName,
                    docentes: docentesData,
                    cargaHoraria: cargaHorariaData,
                    colorClass
                } = data;

                const dia = this.dataset.dia;
                const horaInicio = this.dataset.hora; // This is the start of the cell's interval
                const inicioRowIndex = parseInt(this.dataset.rowIndex);
                const diaInt = parseInt(dia);

                // Check if the selected cells are available in scheduleGrid
                if (scheduleGrid[inicioRowIndex][diaInt] !== null) {
                    Swal.fire('Celda Ocupada', 'Esta celda ya está ocupada. Por favor, selecciona otra.', 'warning');
                    return;
                }

                // Reset modal select options
                modalTipoHoras.innerHTML = '<option value="">Seleccione tipo</option>';
                modalBloques.innerHTML = ''; // Clear blocks options initially
                modalBloques.disabled = true; // Disable until a type is selected

                let tieneHorasDisponibles = false;
                if (cargaHorariaData && cargaHorariaData.length > 0) {
                    cargaHorariaData.forEach(carga => {
                        const subjectTypeKey = `${asignatura_id}_${carga.tipo}`;
                        const horasYaAsignadas = assignedHoursPerSubject[subjectTypeKey] || 0;
                        const horasMaximasParaTipo = parseInt(carga.horas_academicas);
                        const horasRestantesParaTipo = horasMaximasParaTipo - horasYaAsignadas;

                        let optionText = `${carga.tipo} (${horasRestantesParaTipo} bloques rest.)`;
                        let option = new Option(optionText, carga.tipo);
                        if (horasRestantesParaTipo <= 0) {
                            option.disabled = true;
                            optionText = `${carga.tipo} (Límite alcanzado)`;
                            option = new Option(optionText, carga.tipo); // Recreate with disabled text
                            option.disabled = true;
                        } else {
                            tieneHorasDisponibles = true;
                        }
                        modalTipoHoras.add(option);
                    });
                } 

                if (!tieneHorasDisponibles && cargaHorariaData && cargaHorariaData.length > 0) {
                    Swal.fire('Límite Alcanzado',
                        `No hay horas disponibles para la asignatura '${asignaturaName}'. Ya ha asignado todos los bloques permitidos.`,
                        'info');
                    return; // Prevent modal from opening
                } else if (!cargaHorariaData || cargaHorariaData.length === 0) {
                     Swal.fire('Advertencia',
                        `La asignatura '${asignaturaName}' no tiene carga horaria definida. No se puede agregar.`,
                        'warning');
                    return; // Prevent modal from opening if no load is defined
                }

                // Populate modal fields before showing
                modalAsignaturaId.value = asignatura_id;
                modalAsignaturaNombre.value = asignaturaName;
                modalDiaSemana.value = dia;
                modalHoraInicio.value = horaInicio;
                modalColorClass.value = colorClass; // Store colorClass in modal hidden input
                // Almacenar la carga horaria completa en un campo oculto de la modal
                modalCargaHorariaData.value = JSON.stringify(cargaHorariaData);


                // Populate Docente
                modalDocenteId.value = ''; // Reset
                modalDocenteNombre.value = 'Seleccione un docente...'; // Reset display
                if (docentesData && docentesData.length > 0) {
                    modalDocenteId.value = docentesData[0].cedula_doc;
                    modalDocenteNombre.value = docentesData[0].name;
                }

                // Load Aulas
                await cargarAulas(); // Ensure aulas are loaded before showing modal
                
                // Trigger change event to populate modalBloques based on initial modalTipoHoras selection
                modalTipoHoras.dispatchEvent(new Event('change'));

                horarioBlockModal.show(); // This is the line that shows the modal
            }

            // NUEVO: Event listener para modalTipoHoras para actualizar modalBloques
            modalTipoHoras.addEventListener('change', function() {
                const selectedTipo = this.value;
                const asignaturaId = modalAsignaturaId.value;
                const cargaHorariaCompleta = JSON.parse(modalCargaHorariaData.value || '[]');

                modalBloques.innerHTML = ''; // Clear existing options
                modalBloques.disabled = true;

                if (!selectedTipo) return;

                const cargaParaTipo = cargaHorariaCompleta.find(c => c.tipo === selectedTipo);

                if (cargaParaTipo) {
                    const horasMaximas = parseInt(cargaParaTipo.horas_academicas);
                    const horasYaAsignadas = assignedHoursPerSubject[`${asignaturaId}_${selectedTipo}`] || 0;
                    const horasRestantes = horasMaximas - horasYaAsignadas;

                    if (horasRestantes > 0) {
                        for (let i = 1; i <= horasRestantes && i <= 6; i++) { // Max 6 blocks as per validation
                            modalBloques.add(new Option(i, i));
                        }
                        modalBloques.disabled = false;
                    } else {
                        modalBloques.innerHTML = '<option value="">No hay bloques disponibles</option>';
                    }
                } else {
                    // This case should ideally not happen if cargaHorariaData is correctly populated and validated
                    modalBloques.innerHTML = '<option value="">Carga horaria no definida para este tipo</option>';
                }
            });


            // 9. Create the visual block in the table
            function crearBloqueVisual(targetCell, asignaturaId, asignaturaName, dia, horaInicio, horaFin, bloques,
                tipoHoras, aulaId, aulaName, colorClass, rowIndex, colIndex) { // Simplified parameters
                const bloque = document.createElement('div');
                bloque.classList.add('bloque-horario', colorClass);

                // Set the height of the block based on the number of slots it covers
                bloque.style.height = `${bloques * BASE_CELL_HEIGHT}px`;

                // MODIFICADO: Contenido del bloque visual simplificado
                bloque.innerHTML = `
                    <div class="bloque-contenido">
                        <span class="asignatura-nombre" title="${asignaturaName}">${asignaturaName}</span>
                        <span class="asignatura-details">${tipoHoras}</span>
                        <span class="asignatura-details">${aulaName}</span>
                        <button class="btn btn-sm btn-light p-0 delete-btn" title="Eliminar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

                // Save all necessary data in the element, including docenteId
                Object.assign(bloque.dataset, {
                    asignaturaId,
                    asignaturaName,
                    dia,
                    horaInicio,
                    horaFin,
                    bloques,
                    tipoHoras,
                    aulaId, // Store aula ID
                    docenteId: modalDocenteId.value, // Get the actual selected docente ID from the modal
                    periodoId: periodoSelect.value,
                    carreraId: carreraSelect.value,
                    semestreId: semestreSelect.value,
                    turnoId: turnoSelect.value,
                    seccionId: seccionSelect.value,
                    rowIndex: rowIndex, // Store row index
                    colIndex: colIndex // Store col index
                });

                bloque.querySelector('.delete-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    eliminarBloque(bloque);
                });

                // Append the block to the target cell
                targetCell.appendChild(bloque);

                // Mark the cells as occupied in scheduleGrid
                for (let i = 0; i < bloques; i++) {
                    scheduleGrid[rowIndex + i][colIndex] = { blockElement: true }; // Mark as occupied
                }

                actualizarBotonGuardar();
            }

            // 10. Calculate end time
            function calcularHoraFinSimple(horaInicioStr, bloques) {
                const [h, m] = horaInicioStr.split(':').map(Number);
                let totalMinutosFin = (h * 60 + m) + (bloques * 45);
                let horasFin = Math.floor(totalMinutosFin / 60) % 24;
                let minutosFin = totalMinutosFin % 60;
                return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
            }

            // 11. This function is no longer needed for rowspan logic
            // function actualizarTablaHorario(dia, horaInicio, bloques) { }

            // 12. Convert day number to text
            function convertirDiaNumeroATexto(diaNumero) {
                const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                return dias[parseInt(diaNumero)];
            }

            // 13. Delete block and restore cells
            function eliminarBloque(bloqueElement) {
                const {
                    asignaturaId,
                    tipoHoras,
                    dia,
                    horaInicio,
                    bloques: numBloquesStr,
                    rowIndex: rowIndexStr,
                    colIndex: colIndexStr
                } = bloqueElement.dataset;
                const numBloques = parseInt(numBloquesStr);
                const rowIndex = parseInt(rowIndexStr);
                const colIndex = parseInt(colIndexStr);

                // Update assigned hours counter
                const subjectTypeKey = `${asignaturaId}_${tipoHoras}`;
                if (assignedHoursPerSubject[subjectTypeKey]) {
                    assignedHoursPerSubject[subjectTypeKey] -= numBloques;
                    if (assignedHoursPerSubject[subjectTypeKey] <= 0) {
                        delete assignedHoursPerSubject[subjectTypeKey];
                    }
                }

                // Remove the block element from the DOM
                bloqueElement.remove();

                // Clear the scheduleGrid entries for the cells this block occupied
                for (let i = 0; i < numBloques; i++) {
                    if (rowIndex + i < scheduleGrid.length) { // Ensure index is within bounds
                        scheduleGrid[rowIndex + i][colIndex] = null; // Mark as empty
                    }
                }

                // Remove from bloquesParaGuardar array
                const indexToRemove = bloquesParaGuardar.findIndex(b =>
                    b.asignatura_id === asignaturaId &&
                    b.dia_semana === parseInt(dia) &&
                    b.hora_inicio === horaInicio &&
                    b.tipo_horas === tipoHoras // Add tipo_horas for more precise match
                    // Consider adding more unique identifiers if needed, e.g., aula_id
                );
                if (indexToRemove !== -1) {
                    bloquesParaGuardar.splice(indexToRemove, 1);
                }


                actualizarBotonGuardar();
            }

            // 14. Update save button
            function actualizarBotonGuardar() {
                guardarBtn.disabled = bloquesParaGuardar.length === 0;
            }

            // --- Logic for Horario Block Modal ---
            saveBlockBtn.addEventListener('click', function() {
                const asignaturaId = modalAsignaturaId.value;
                const asignaturaName = modalAsignaturaNombre.value;
                const docenteId = modalDocenteId.value;
                const diaSemana = modalDiaSemana.value;
                const horaInicio = modalHoraInicio.value;
                const tipoHoras = modalTipoHoras.value;
                const bloques = parseInt(modalBloques.value);
                const aulaId = modalAula.value;
                const aulaName = modalAula.options[modalAula.selectedIndex].text;
                const colorClass = modalColorClass.value;
                const cargaHorariaCompleta = JSON.parse(modalCargaHorariaData.value || '[]');

                // --- VALIDACIÓN FRONTEND: Carga Horaria ---
                const cargaParaTipo = cargaHorariaCompleta.find(c => c.tipo === tipoHoras);
                if (!cargaParaTipo) {
                     Swal.fire('Error', 'Tipo de horas no válido para esta asignatura.', 'error');
                     return;
                }
                const horasMaximas = parseInt(cargaParaTipo.horas_academicas);
                const horasYaAsignadas = assignedHoursPerSubject[`${asignaturaId}_${tipoHoras}`] || 0;
                
                if ((horasYaAsignadas + bloques) > horasMaximas) {
                    Swal.fire('Límite Excedido', `No puedes agregar ${bloques} bloques de tipo '${tipoHoras}'. Solo quedan ${horasMaximas - horasYaAsignadas} disponibles de ${horasMaximas}.`, 'warning');
                    return;
                }
                // --- FIN VALIDACIÓN FRONTEND: Carga Horaria ---


                if (!asignaturaId || !docenteId || !diaSemana || !horaInicio || !tipoHoras || isNaN(bloques) || bloques < 1 || !aulaId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los campos requeridos (Asignatura, Docente, Tipo de Horas, Bloques y Aula).', 'warning');
                    return;
                }

                const horaFin = calcularHoraFinSimple(horaInicio, bloques);

                // Check for cell availability again, considering the selected number of blocks
                const inicioRowIndex = getTimeRowIndex(horaInicio);
                const diaInt = parseInt(diaSemana);
                for (let i = 0; i < bloques; i++) {
                    const currentRowIndex = inicioRowIndex + i;
                    if (currentRowIndex >= scheduleGrid.length || scheduleGrid[currentRowIndex][diaInt] !== null) {
                        Swal.fire('Celdas Ocupadas', 'Algunas de las celdas seleccionadas ya están ocupadas o no están disponibles para la cantidad de bloques elegida.', 'warning');
                        return;
                    }
                }

                // Update assigned hours counter
                const subjectTypeKey = `${asignaturaId}_${tipoHoras}`;
                assignedHoursPerSubject[subjectTypeKey] = (assignedHoursPerSubject[subjectTypeKey] || 0) + bloques;

                // Add to array for saving
                bloquesParaGuardar.push({
                    asignatura_id: asignaturaId,
                    docente_id: docenteId,
                    dia_semana: parseInt(diaSemana),
                    hora_inicio: horaInicio,
                    hora_fin: horaFin,
                    tipo_horas: tipoHoras,
                    bloques: bloques,
                    aula_id: aulaId,
                });

                // Add visual block to the schedule
                const targetCell = document.querySelector(`[data-dia="${diaSemana}"][data-hora="${horaInicio}"]`);
                if (targetCell) {
                    crearBloqueVisual(targetCell, asignaturaId, asignaturaName, diaSemana, horaInicio, horaFin, bloques, tipoHoras, aulaId, aulaName, colorClass, inicioRowIndex, diaInt);
                }

                // Mark cells as occupied in scheduleGrid
                for (let i = 0; i < bloques; i++) {
                    scheduleGrid[inicioRowIndex + i][diaInt] = { blockElement: true }; // Mark as occupied
                }

                horarioBlockModal.hide();
                Swal.fire('¡Bloque Añadido!', 'El bloque se ha añadido al horario.', 'success');
                actualizarBotonGuardar();
            });

            // Function to load aulas into the modal select
            async function cargarAulas() {
                modalAula.innerHTML = '<option value="">Cargando Aulas...</option>';
                modalAula.disabled = true;

                try {
                    const response = await fetch('/api/aulas'); // Ensure this route exists in your Laravel
                    console.log('Respuesta de /api/aulas:', response); // Debugging log
                    if (!response.ok) throw new Error(`Error al cargar aulas: ${response.statusText}`);
                    const aulas = await response.json();
                    console.log('Datos de aulas recibidos:', aulas); // Debugging log

                    modalAula.innerHTML = '<option value="">Seleccione un Aula</option>';
                    if (aulas.length > 0) {
                        aulas.forEach(aula => {
                            modalAula.add(new Option(aula.nombre, aula.id));
                        });
                        modalAula.disabled = false;
                    } else {
                        modalAula.innerHTML = '<option value="">No hay aulas disponibles</option>';
                    }
                } catch (error) {
                    console.error('Error cargando aulas:', error);
                    Swal.fire('Error', `No se pudieron cargar las aulas: ${error.message}`, 'error'); // More specific error
                }
            }


            // 15. Submit form
            document.getElementById('horarioForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                if (bloquesParaGuardar.length === 0) {
                    Swal.fire('Advertencia', 'No hay bloques de horario para guardar.', 'warning');
                    return;
                }

                const periodoId = periodoSelect.value;
                const turnoId = turnoSelect.value;
                const carreraId = carreraSelect.value;
                const semestreId = semestreSelect.value;
                const seccionId = seccionSelect.value;
                const asignaturaCompartidaId = asignaturaCompartidaIdHidden.value; // Get shared assignment ID

                if (!periodoId || !carreraId || !turnoId || !semestreId || !seccionId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los filtros antes de guardar el horario.',
                        'warning');
                    return;
                }
                
                const formData = {
                    periodo_id: periodoId,
                    turno_id: turnoId,
                    carrera_id: carreraId,
                    semestre_id: semestreId,
                    seccion_id: seccionId,
                    bloques_horario: bloquesParaGuardar, // Send the collected blocks
                    asignatura_compartida_id: asignaturaCompartidaId // Include shared assignment ID
                };

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(formData)
                    });

                    const responseText = await response.text();
                    if (!response.ok) {
                        let errorMessage = 'Error al guardar.';
                        try {
                            const errorData = JSON.parse(responseText);
                            errorMessage = errorData.message || errorData.error || errorMessage;
                            if (errorData.errors) { // Si hay errores de validación de Laravel
                                let validationErrors = Object.values(errorData.errors).flat().join('<br>');
                                errorMessage += '<br>' + validationErrors;
                            }
                        } catch (parseError) {
                            errorMessage = responseText.substring(0, 200); // Avoid very long messages
                        }
                        throw new Error(errorMessage);
                    }
                    const data = JSON.parse(responseText);
                    if (data.success) {
                        Swal.fire('Éxito', data.message, 'success').then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo guardar.', 'error');
                    }
                } catch (error) {
                    console.error('Error sending:', error);
                    Swal.fire('Error', `Ocurrió un error: ${error.message}`, 'error');
                }
            });

            // NEW LOGIC FOR SHARED ASSIGNMENT
            btnAsignaturaCompartida.addEventListener('click', function() {
                modalAsignaturaCompartida.show();
                // When modal opens, reset its filters
                periodoSelectShared.value = '';
                carreraSelectShared.value = '';
                turnoSelectShared.value = '';
                semestreSelectShared.innerHTML = '<option value="">Seleccione turno</option>';
                semestreSelectShared.disabled = true;
                seccionSelectShared.innerHTML = '<option value="">Complete filtros</option>';
                seccionSelectShared.disabled = true;
                asignaturaCompartidaSelect.innerHTML = '<option value="">Seleccione una Asignatura</option>';
                asignaturaCompartidaSelect.disabled = true;
            });

            btnGuardarAsignaturaCompartida.addEventListener('click', function() {
                const selectedAsignaturaCompartidaId = asignaturaCompartidaSelect.value;
                const selectedAsignaturaCompartidaText = asignaturaCompartidaSelect.options[asignaturaCompartidaSelect.selectedIndex].text;

                if (selectedAsignaturaCompartidaId) {
                    // Check if this assignment is already in the main list (either original or previously added shared)
                    const existingAsignaturaItem = document.querySelector(`#listaAsignaturas [data-asignatura-id="${selectedAsignaturaCompartidaId}"]`);
                    if (existingAsignaturaItem) {
                        Swal.fire('Advertencia', 'Esta asignatura ya está en la lista de asignaturas disponibles.', 'warning');
                        return;
                    }

                    // Retrieve data from the selected option in the modal
                    const selectedOption = asignaturaCompartidaSelect.options[asignaturaCompartidaSelect.selectedIndex];
                    const docentesData = JSON.parse(selectedOption.dataset.docentes || '[]');
                    const cargaHorariaData = JSON.parse(selectedOption.dataset.cargaHoraria || '[]');

                    // Create a new draggable item for the left panel
                    const newAsignaturaItem = document.createElement('div');
                    newAsignaturaItem.classList.add('list-group-item', 'asignatura-item', 'bg-shared-asignatura', 'text-white', 'mb-2', 'py-2', 'px-3');
                    newAsignaturaItem.draggable = true;
                    newAsignaturaItem.dataset.asignaturaId = selectedAsignaturaCompartidaId;
                    newAsignaturaItem.dataset.asignaturaName = selectedAsignaturaCompartidaText;
                    newAsignaturaItem.dataset.docentes = JSON.stringify(docentesData);
                    newAsignaturaItem.dataset.cargaHoraria = JSON.stringify(cargaHorariaData);
                    newAsignaturaItem.dataset.colorClass = 'bg-shared-asignatura'; // Store the class for consistency

                    let cargaHorariaText = 'No definida';
                    if (cargaHorariaData && cargaHorariaData.length > 0) {
                        cargaHorariaText = cargaHorariaData.map(c => `${c.tipo.substring(0,1)}:${c.horas_academicas}b`).join(', ');
                    }

                    newAsignaturaItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size: 0.8rem;">${selectedAsignaturaCompartidaText} <small>(${cargaHorariaText})</small></span>
                            <i class="fas fa-arrows-alt ms-2"></i>
                        </div>
                    `;

                    listaAsignaturas.appendChild(newAsignaturaItem);
                    configurarDragAndDrop(); // Make the new item draggable

                    asignaturaCompartidaIdHidden.value = selectedAsignaturaCompartidaId;
                    sharedAsignaturaLabel.textContent = `Compartida: ${selectedAsignaturaCompartidaText}`;
                    sharedAsignaturaLabel.style.display = 'inline-block';
                    Swal.fire('Asignatura Compartida', 'Asignatura seleccionada y añadida a la lista.', 'success');
                    modalAsignaturaCompartida.hide();
                } else {
                    Swal.fire('Advertencia', 'Por favor, seleccione una asignatura para compartir.', 'warning');
                }
            });

            // Reset shared assignment selection when modal is hidden
            document.getElementById('modalAsignaturaCompartida').addEventListener('hidden.bs.modal', function () {
                asignaturaCompartidaSelect.value = '';
            });

            // Functions for shared assignment modal filters
            turnoSelectShared.addEventListener('change', function() {
                const turnoId = this.value;
                semestreSelectShared.innerHTML = '<option value="">Seleccione...</option>';
                semestreSelectShared.disabled = true;
                seccionSelectShared.innerHTML = '<option value="">Complete filtros</option>';
                seccionSelectShared.disabled = true;
                asignaturaCompartidaSelect.innerHTML = '<option value="">Seleccione una Asignatura</option>';
                asignaturaCompartidaSelect.disabled = true;
                if (turnoId) {
                    cargarSemestresShared(turnoId);
                }
            });

            carreraSelectShared.addEventListener('change', function() {
                seccionSelectShared.innerHTML = '<option value="">Complete filtros</option>';
                seccionSelectShared.disabled = true;
                asignaturaCompartidaSelect.innerHTML = '<option value="">Seleccione una Asignatura</option>';
                asignaturaCompartidaSelect.disabled = true;
                if (carreraSelectShared.value && semestreSelectShared.value && turnoSelectShared.value) {
                    cargarSeccionesShared();
                }
            });

            semestreSelectShared.addEventListener('change', function() {
                seccionSelectShared.innerHTML = '<option value="">Complete filtros</option>';
                seccionSelectShared.disabled = true;
                asignaturaCompartidaSelect.innerHTML = '<option value="">Seleccione una Asignatura</option>';
                asignaturaCompartidaSelect.disabled = true;
                if (carreraSelectShared.value && semestreSelectShared.value && turnoSelectShared.value) {
                    cargarSeccionesShared();
                }
            });

            // Trigger assignment loading when "Buscar Asignaturas" button is clicked in the modal
            buscarAsignaturasModalBtn.addEventListener('click', cargarAsignaturasParaModalCompartida);


            async function cargarSemestresShared(turnoId) {
                try {
                    const response = await fetch(`{{ url('/horario/api/semestres-por-turno/') }}/${turnoId}`);
                    if (!response.ok) throw new Error('Error al cargar semestres para modal');
                    const data = await response.json();

                    semestreSelectShared.innerHTML = '<option value="">Seleccione...</option>';
                    if (data.length > 0) {
                        data.forEach(semestre => {
                            const option = new Option(`${semestre.numero}º Semestre`, semestre.id_semestre);
                            semestreSelectShared.add(option);
                        });
                        semestreSelectShared.disabled = false;
                    } else {
                        semestreSelectShared.innerHTML = '<option value="">No hay semestres</option>';
                    }
                } catch (error) {
                    console.error('Error cargando semestres para modal:', error);
                    Swal.fire('Error', 'No se pudieron cargar los semestres para la selección compartida.', 'error');
                }
            }

            async function cargarSeccionesShared() {
                const carreraId = carreraSelectShared.value;
                const semestreId = semestreSelectShared.value;
                const turnoId = turnoSelectShared.value;

                seccionSelectShared.innerHTML = '<option value="">Cargando...</option>';
                seccionSelectShared.disabled = true;
                asignaturaCompartidaSelect.innerHTML = '<option value="">Seleccione una Asignatura</option>';
                asignaturaCompartidaSelect.disabled = true;


                if (!carreraId || !semestreId || !turnoId) {
                    seccionSelectShared.innerHTML = '<option value="">Complete filtros</option>';
                    return;
                }
                try {
                    const url = new URL(`{{ url('/horario/obtener-secciones') }}`);
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);
                    const response = await fetch(url);
                    if (!response.ok) throw new Error(`Error getting sections for modal: ${response.status}`);
                    const data = await response.json();
                    seccionSelectShared.innerHTML = '<option value="">Seleccione sección</option>';
                    if (data.length > 0) {
                        data.forEach(s => {
                            const value = s.codigo_seccion || s.id;
                            const text = s.codigo_seccion || s.text;
                            seccionSelectShared.add(new Option(text, value));
                        });
                    } else {
                        seccionSelectShared.innerHTML = '<option value="">No hay secciones</option>';
                    }
                    seccionSelectShared.disabled = false;
                } catch (error) {
                    console.error('Error cargando secciones para modal:', error);
                    Swal.fire('Error', 'No se pudieron cargar las secciones para la selección compartida.', 'error');
                }
            }

            async function cargarAsignaturasParaModalCompartida() {
                const periodoId = periodoSelectShared.value;
                const carreraId = carreraSelectShared.value;
                const semestreId = semestreSelectShared.value;
                const turnoId = turnoSelectShared.value;
                const seccionId = seccionSelectShared.value;

                if (!periodoId || !carreraId || !semestreId || !turnoId || !seccionId) {
                    asignaturaCompartidaSelect.innerHTML = '<option value="">Complete todos los filtros</option>';
                    asignaturaCompartidaSelect.disabled = true;
                    return;
                }
                
                asignaturaCompartidaSelect.innerHTML = '<option value="">Cargando...</option>';
                asignaturaCompartidaSelect.disabled = true;

                try {
                    const url = new URL(`{{ url('/horario/asignaturas') }}`);
                    url.searchParams.append('seccion_id', seccionId);
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);
                    url.searchParams.append('periodo_id', periodoId); 

                    const response = await fetch(url);
                    if (!response.ok) throw new Error(`Error getting subjects for modal: ${response.status}`);
                    const asignaturas = await response.json();

                    asignaturaCompartidaSelect.innerHTML = '<option value="">Seleccione una Asignatura</option>';
                    if (asignaturas.length > 0) {
                        asignaturas.forEach(asignatura => {
                            const option = new Option(asignatura.name, asignatura.asignatura_id);
                            // Store docentes and carga_horaria in the option's dataset
                            option.dataset.docentes = JSON.stringify(asignatura.docentes);
                            option.dataset.cargaHoraria = JSON.stringify(asignatura.carga_horaria);
                            asignaturaCompartidaSelect.add(option);
                        });
                        asignaturaCompartidaSelect.disabled = false;
                    } else {
                        asignaturaCompartidaSelect.innerHTML = '<option value="">No hay asignaturas disponibles</option>';
                    }
                } catch (error) {
                    console.error('Error loading shared assignments for modal:', error);
                    Swal.fire('Error', 'No se pudieron cargar las asignaturas para la selección compartida.', 'error');
                }
            }
        });
    </script>
</body>

</html>
