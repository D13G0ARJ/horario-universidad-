<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Horario</title> {{-- Changed title to "Editar Horario" --}}

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
            /* Mantener oculto el scroll de la sidebar si no es relevante */
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

        .asignatura-draggable {
            cursor: grab;
            user-select: none;
        }

        .bloque-droppable {
            min-width: 120px;
            min-height: 50px;
            background: #f8f9fa;
            border: 1px dashed #adb5bd;
            vertical-align: middle;
            position: relative;
        }

        .bloque-asignado {
            background: #e2edfa;
            border-radius: 5px;
            padding: 4px 8px;
            margin: 2px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-quitar-bloque {
            margin-left: 8px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row mb-4" style="margin-top: 10px;">
            <div class="col-12">
                <div class="card shadow rounded-4 px-4 border border-2 border-primary-subtle">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3" style="padding: 10px;">
                            <a href="{{ route('horario.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold mt-3 mt-md-0" style="width:180px;">
                                <i class="fas fa-arrow-left me-2"></i>Volver
                            </a>
                            <h1 class="h3 text-primary text-center w-100 mb-0">
                                <i class="fas fa-calendar-plus mr-2"></i>Editar Horario
                            </h1>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white py-3">
                <h2 class="h5 mb-0">
                    <i class="fas fa-calendar-alt mr-2"></i>Edición del Horario
                </h2>
            </div>

            <form action="{{ route('horario.update', $horario->id) }}" method="POST" id="horarioForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="horario_data" id="horarioData">

                <div class="card-body p-4">
                    <div class="row g-3 mb-4 bg-light p-3 rounded">
                        {{-- Filtros: Periodo, Carrera, Turno, Semestre, Sección --}}
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="periodo_id" class="form-label fw-bold">Periodo</label>
                            <select id="periodo_id" name="periodo_id" class="form-select form-select-lg" required disabled>
                                <option value="">Seleccione...</option>
                                @foreach ($periodos as $periodo)
                                    <option value="{{ $periodo->id }}" @if($horario->periodo_id == $periodo->id) selected @endif>{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="carrera_id" class="form-label fw-bold">Carrera</label>
                            <select id="carrera_id" name="carrera_id" class="form-select form-select-lg" required disabled>
                                <option value="">Seleccione...</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}" @if($horario->carrera_id == $carrera->carrera_id) selected @endif>{{ $carrera->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="turno_id" class="form-label fw-bold">Turno</label>
                            <select id="turno_id" name="turno_id" class="form-select form-select-lg" required disabled>
                                <option value="">Seleccione...</option>
                                @foreach ($turnos as $turno)
                                    <option value="{{ $turno->id_turno }}" @if($horario->turno_id == $turno->id_turno) selected @endif>{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="semestre_id" class="form-label fw-bold">Semestre</label>
                            <select id="semestre_id" name="semestre_id" class="form-select form-select-lg" required disabled>
                                <option value="">Seleccione...</option>
                                @foreach ($semestres as $semestre)
                                    <option value="{{ $semestre->id_semestre }}" @if($horario->semestre_id == $semestre->id_semestre) selected @endif>{{ $semestre->numero }}º</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="seccion_id" class="form-label fw-bold">Sección</label>
                            <select id="seccion_id" name="seccion_id" class="form-select form-select-lg" required disabled>
                                <option value="">Seleccione...</option>
                                @foreach ($secciones as $seccion)
                                    <option value="{{ $seccion->codigo_seccion }}" @if($horario->seccion_id == $seccion->codigo_seccion) selected @endif>{{ $seccion->codigo_seccion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary btn-lg w-100 py-2" id="buscarHorarios" disabled>
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
                            <button type="button" class="btn btn-info btn-sm mt-3" id="btnAsignaturaCompartida"> {{-- Removed disabled attribute to allow dynamic enabling --}}
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
                    <button type="submit" class="btn btn-primary btn-lg px-4" id="guardarHorario">
                        <i class="fas fa-save me-2"></i> Guardar Cambios
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"> {{-- Changed data-dismiss to data-bs-dismiss and added btn-close-white --}}
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button> {{-- Changed data-dismiss to data-bs-dismiss --}}
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
                        <input type="hidden" id="modalColorClass"> {{-- Nuevo campo oculto para colorClass --}}
                        {{-- Hidden field to store full assignment load data --}}
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
                                {{-- Las opciones se cargarán dinámicamente --}}
                            </select>
                        </div>

                        {{-- NUEVO CAMPO AULA --}}
                        <div class="mb-3">
                            <label for="modalAula" class="form-label">Aula:</label>
                            <select class="form-select" id="modalAula" required>
                                <option value="">Seleccione un Aula</option>
                                {{-- Las opciones de aula se cargarán dinámicamente con JavaScript --}}
                            </select>
                        </div>

                        {{-- ELIMINADO: Apartado de Observaciones --}}
                        {{-- <div class="mb-3">
                            <label for="modalObservaciones" class="form-label">Observaciones (Opcional):</label>
                            <textarea class="form-control" id="modalObservaciones" rows="2"></textarea>
                        </div> --}}
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
            const BASE_CELL_HEIGHT = 40; // Altura base de la celda en px, debe coincidir con el CSS

            const periodoSelect = document.getElementById('periodo_id');
            const turnoSelect = document.getElementById('turno_id');
            const semestreSelect = document.getElementById('semestre_id');
            const carreraSelect = document.getElementById('carrera_id');
            const seccionSelect = document.getElementById('seccion_id');
            const buscarBtn = document.getElementById('buscarHorarios');
            const listaAsignaturas = document.getElementById('listaAsignaturas');
            const horarioBody = document.getElementById('horarioBody');
            const guardarBtn = document.getElementById('guardarHorario');

            // NUEVOS ELEMENTOS PARA ASIGNATURA COMPARTIDA
            const btnAsignaturaCompartida = document.getElementById('btnAsignaturaCompartida');
            const modalAsignaturaCompartida = new bootstrap.Modal(document.getElementById('modalAsignaturaCompartida'));
            const asignaturaCompartidaSelect = document.getElementById('asignatura_compartida_select');
            const btnGuardarAsignaturaCompartida = document.getElementById('btnGuardarAsignaturaCompartida');
            const asignaturaCompartidaIdHidden = document.createElement('input'); // Input oculto para el ID de la asignatura compartida
            asignaturaCompartidaIdHidden.type = 'hidden';
            asignaturaCompartidaIdHidden.id = 'asignatura_compartida_id';
            asignaturaCompartidaIdHidden.name = 'asignatura_compartida_id';
            document.getElementById('horarioForm').appendChild(asignaturaCompartidaIdHidden); // Añadir al formulario
            const sharedAsignaturaLabel = document.getElementById('shared_asignatura_label'); // Etiqueta visual para la asignatura compartida

            // NUEVOS ELEMENTOS PARA LOS FILTROS DE LA MODAL DE ASIGNATURA COMPARTIDA
            const periodoSelectShared = document.getElementById('periodo_id_shared');
            const carreraSelectShared = document.getElementById('carrera_id_shared');
            const turnoSelectShared = document.getElementById('turno_id_shared');
            const semestreSelectShared = document.getElementById('semestre_id_shared');
            const seccionSelectShared = document.getElementById('seccion_id_shared');
            const buscarAsignaturasModalBtn = document.getElementById('buscarAsignaturasModalBtn');

            // Elementos de la modal para la configuración del bloque
            const horarioBlockModal = new bootstrap.Modal(document.getElementById('horarioBlockModal'));
            const modalAsignaturaId = document.getElementById('modalAsignaturaId');
            const modalAsignaturaNombre = document.getElementById('modalAsignaturaNombre');
            const modalDocenteId = document.getElementById('modalDocenteId');
            const modalDocenteNombre = document.getElementById('modalDocenteNombre');
            const modalDiaSemana = document.getElementById('modalDiaSemana');
            const modalHoraInicio = document.getElementById('modalHoraInicio');
            const modalTipoHoras = document.getElementById('modalTipoHoras');
            const modalBloques = document.getElementById('modalBloques'); // Ahora un select
            const modalAula = document.getElementById('modalAula'); // Nuevo select de aula
            const saveBlockBtn = document.getElementById('saveBlockBtn');
            const modalColorClass = document.getElementById('modalColorClass'); // Obtener el nuevo input oculto
            // NUEVO: Campo oculto para almacenar los datos completos de carga horaria en la modal
            const modalCargaHorariaData = document.getElementById('modalCargaHorariaData');


            // Objeto para rastrear las horas asignadas por asignatura y tipo
            let assignedHoursPerSubject = {}; // Ejemplo: { "asignaturaId_teorica": 2, "asignaturaId_practica": 1 }

            // Variable global para rastrear las celdas ocupadas (rowIndex, colIndex)
            let scheduleGrid = []; // scheduleGrid[rowIndex][dayIndex] = { blockElementRef } o null

            // Array para almacenar los bloques configurados para el envío
            const bloquesParaGuardar = [];


            // 1. Cargar semestres según el turno seleccionado
            turnoSelect.addEventListener('change', function() {
                const turnoId = this.value;
                console.log('Turno seleccionado:', turnoId); // Log de depuración
                semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                semestreSelect.disabled = true;
                seccionSelect.innerHTML = '<option value="">Complete los filtros</option>';
                seccionSelect.disabled = true;
                
                // Reiniciar elementos relacionados con la asignatura compartida
                btnAsignaturaCompartida.disabled = true; 
                asignaturaCompartidaIdHidden.value = ''; 
                sharedAsignaturaLabel.style.display = 'none'; 
                sharedAsignaturaLabel.textContent = '';


                if (!turnoId) return;

                fetch(`{{ url('/horario/api/semestres-por-turno/') }}/${turnoId}`)
                    .then(response => {
                        console.log('Respuesta de semestres-por-turno:', response); // Log de depuración
                        if (!response.ok) throw new Error(`Error al cargar semestres: ${response.statusText}`);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos de semestres recibidos:', data); // Log de depuración
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
                        Swal.fire('Error', `No se pudieron cargar los semestres: ${error.message}`, 'error'); // Error más específico
                    });
            });

            // 2. Función para cargar secciones
            async function cargarSecciones() {
                const carreraId = carreraSelect.value;
                const semestreId = semestreSelect.value;
                const turnoId = turnoSelect.value;

                console.log('Cargando secciones con:', { carreraId, semestreId, turnoId }); // Log de depuración

                seccionSelect.innerHTML = '<option value="">Cargando...</option>';
                seccionSelect.disabled = true;

                // Reiniciar elementos relacionados con la asignatura compartida
                btnAsignaturaCompartida.disabled = true; 
                asignaturaCompartidaIdHidden.value = ''; 
                sharedAsignaturaLabel.style.display = 'none'; 
                sharedAsignaturaLabel.textContent = '';

                if (!carreraId || !semestreId || !turnoId) {
                    seccionSelect.innerHTML = '<option value="">Complete los filtros</option>';
                    return;
                }
                try {
                    const url = new URL(`{{ url('/horario/obtener-secciones') }}`);
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);
                    const response = await fetch(url);
                    console.log('Respuesta de obtener-secciones:', response); // Log de depuración
                    if (!response.ok) throw new Error(`Error al obtener secciones: ${response.statusText}`);
                    const data = await response.json();
                    console.log('Datos de secciones recibidos:', data); // Log de depuración
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
                    Swal.fire('Error', `No se pudieron cargar las secciones: ${error.message}`, 'error'); // Error más específico
                }
            }

            carreraSelect.addEventListener('change', cargarSecciones);
            semestreSelect.addEventListener('change', cargarSecciones);

            // 4. Función principal al hacer clic en Buscar (Este botón está deshabilitado en la vista de edición, por lo que no lo activará directamente)
            buscarBtn.addEventListener('click', async function() {
                const periodoId = periodoSelect.value;
                const carreraId = carreraSelect.value;
                const turnoId = turnoSelect.value;
                const semestreId = semestreSelect.value;
                const seccionId = seccionSelect.value;

                console.log('Buscando horarios con:', { periodoId, carreraId, turnoId, semestreId, seccionId }); // Log de depuración

                if (!periodoId || !carreraId || !turnoId || !semestreId || !seccionId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los filtros.', 'warning');
                    return;
                }

                // Reiniciar contador de horas asignadas y cuadrícula de horarios
                assignedHoursPerSubject = {};
                scheduleGrid = []; // Borrar la cuadrícula en una nueva búsqueda
                bloquesParaGuardar.length = 0; // Borrar bloques para guardar
                horarioBody.innerHTML = ''; // Borrar bloques existentes de la tabla
                
                listaAsignaturas.innerHTML =
                    `<div class="text-center py-4"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
                horarioBody.innerHTML =
                    `<tr><td colspan="7" class="text-center py-5"><div class="spinner-border text-primary"></div><p>Preparando...</p></td></tr>`;
                
                // Reiniciar elementos relacionados con la asignatura compartida
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
                    console.log('Respuesta de asignaturas:', response); // Log de depuración
                    if (!response.ok) throw new Error(`Error al obtener asignaturas: ${response.statusText}`);
                    const asignaturas = await response.json();
                    console.log('Datos de asignaturas recibidos:', asignaturas); // Log de depuración

                    if (asignaturas.length > 0) {
                        listaAsignaturas.innerHTML = asignaturas.map((asignatura, index) => {
                            const colorClass = `bg-asignatura-${(index % 7) + 1}`;
                            // Mostrar carga horaria más clara
                            let cargaHorariaText = 'No definida';
                            if (asignatura.carga_horaria && asignatura.carga_horaria.length > 0) {
                                cargaHorariaText = asignatura.carga_horaria.map(c => `${c.tipo.substring(0,1)}:${c.horas_academicas}b`).join(', '); // Ejemplo: T:4b, P:2b
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
                        // Habilitar el botón de asignatura compartida una vez que las asignaturas se cargan
                        btnAsignaturaCompartida.disabled = false; 
                    } else {
                        listaAsignaturas.innerHTML =
                            `<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle me-2"></i>No hay asignaturas.</div>`;
                    }
                    generarHorario();
                } catch (error) {
                    console.error('Error en la búsqueda de asignaturas:', error); // Log de errores más específico
                    Swal.fire('Error', `Ocurrió un error al cargar: ${error.message}`, 'error');
                    listaAsignaturas.innerHTML =
                        `<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error al cargar.</div>`;
                    horarioBody.innerHTML =
                        `<tr><td colspan="7" class="text-center py-5 text-muted">Error.</td></tr>`;
                }
            });

            // 5. Configurar arrastrar y soltar
            function configurarDragAndDrop() {
                document.querySelectorAll('.asignatura-item').forEach(item => {
                    item.addEventListener('dragstart', function(e) {
                        e.dataTransfer.setData('text/plain', JSON.stringify({
                            asignatura_id: this.dataset.asignaturaId,
                            name: this.dataset.asignaturaName,
                            docentes: JSON.parse(this.dataset.docentes),
                            cargaHoraria: JSON.parse(this.dataset.cargaHoraria), // Array de objetos {tipo, horas_academicas}
                            colorClass: Array.from(this.classList).find(cls => cls.startsWith(
                                'bg-asignatura-') || cls === 'bg-shared-asignatura') // Incluir nueva clase
                        }));
                        this.classList.add('dragging');
                    });
                    item.addEventListener('dragend', function() {
                        this.classList.remove('dragging');
                    });
                });
            }

            // Función para convertir la cadena de tiempo en índice de fila
            function getTimeRowIndex(timeStr) {
                const startHourTable = 7;
                const startMinutesTable = startHourTable * 60; // 420 minutos (7:00 AM)

                const [h, m] = timeStr.split(':').map(Number);
                const currentMinutes = h * 60 + m;

                // Calcular el índice de la fila basado en intervalos de 45 minutos desde el inicio de la tabla
                return Math.floor((currentMinutes - startMinutesTable) / 45);
            }

            // 6. Generar estructura del horario
            function generarHorario() {
                horarioBody.innerHTML = '';
                const startHour = 7;
                const endHour = 21; // El horario termina a las 21:00, por lo que el último bloque puede terminar aquí.

                let currentMinutes = startHour * 60; // Convertir hora de inicio a minutos desde la medianoche (ej. 7 * 60 = 420)
                const endMinutes = endHour * 60; // Convertir hora de fin a minutos desde la medianoche (ej. 21 * 60 = 1260)
                const interval = 45; // Intervalo de 45 minutos

                let rowIndex = 0;
                while (currentMinutes < endMinutes + interval) // Condición de bucle ajustada
                {
                    const hoursStart = Math.floor(currentMinutes / 60);
                    const minutesStart = currentMinutes % 60;
                    const horaInicioFormato = `${String(hoursStart).padStart(2, '0')}:${String(minutesStart).padStart(2, '0')}`;

                    const nextMinutes = currentMinutes + interval;
                    const hoursEnd = Math.floor(nextMinutes / 60);
                    const minutesEnd = nextMinutes % 60;
                    const horaFinFormato = `${String(hoursEnd).padStart(2, '0')}:${String(minutesEnd).padStart(2, '0')}`;
                    
                    // Formatear el rango de tiempo para mostrar
                    const horaRangoDisplay = `${horaInicioFormato} - ${horaFinFormato}`;

                    let fila = document.createElement('tr');
                    fila.innerHTML = `
                        <th class="time-slot" data-time-start="${horaInicioFormato}" data-time-end="${horaFinFormato}" data-row-index="${rowIndex}">${horaRangoDisplay}</th>
                        ${[1,2,3,4,5,6].map(dia => `<td class="drop-zone" data-hora="${horaInicioFormato}" data-dia="${dia}" data-row-index="${rowIndex}" data-col-index="${dia}"></td>`).join('')}
                    `;
                    horarioBody.appendChild(fila);
                    rowIndex++;

                    currentMinutes += interval; // Añadir 45 minutos para el siguiente espacio
                }

                // Inicializar scheduleGrid con el número real de filas generadas.
                scheduleGrid = Array(rowIndex).fill(null).map(() => Array(7).fill(null)); // 7 días (0 sin usar, 1-6 para los días)

                configurarCeldasHorario();
            }

            // 7. Configurar celdas del horario
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

            // 8. Manejar el soltar con validación de horas
            async function handleDrop(e) {
                e.preventDefault();
                this.classList.remove('hover-cell');

                const transferData = e.dataTransfer.getData('text/plain');
                console.log('Datos transferidos:', transferData); // Registrar datos transferidos

                if (!transferData) {
                    console.error('No se transfirieron datos durante el evento de soltar.');
                    Swal.fire('Error', 'No se pudo obtener la información de la asignatura arrastrada.', 'error');
                    return;
                }

                let data;
                try {
                    data = JSON.parse(transferData);
                } catch (error) {
                    console.error('Error al analizar los datos JSON del evento de arrastre:', error);
                    Swal.fire('Error', 'Error al procesar la información de la asignatura.', 'error');
                    return;
                }

                console.log('Datos analizados (handleDrop):', data); // Registrar datos analizados

                const {
                    asignatura_id,
                    name: asignaturaName,
                    docentes: docentesData,
                    cargaHoraria: cargaHorariaData,
                    colorClass
                } = data;

                const dia = this.dataset.dia;
                const horaInicio = this.dataset.hora;
                const inicioRowIndex = parseInt(this.dataset.rowIndex);
                const diaInt = parseInt(dia);

                // Comprobar si las celdas seleccionadas están disponibles en scheduleGrid
                if (scheduleGrid[inicioRowIndex][diaInt] !== null) {
                    Swal.fire('Celda Ocupada', 'Esta celda ya está ocupada. Por favor, selecciona otra.', 'warning');
                    return;
                }

                // Reiniciar las opciones de selección de la modal
                modalTipoHoras.innerHTML = '<option value="">Seleccione tipo</option>';
                modalBloques.innerHTML = ''; // Borrar las opciones de bloques inicialmente
                modalBloques.disabled = true; // Deshabilitar hasta que se seleccione un tipo

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
                            option = new Option(optionText, carga.tipo); // Volver a crear con texto deshabilitado
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
                    return; // Evitar que se abra la modal
                } else if (!cargaHorariaData || cargaHorariaData.length === 0) {
                     Swal.fire('Advertencia',
                        `La asignatura '${asignaturaName}' no tiene carga horaria definida. No se puede añadir.`,
                        'warning');
                    return; // Evitar que se abra la modal si no hay carga definida
                }


                // Rellenar campos de la modal antes de mostrar
                modalAsignaturaId.value = asignatura_id;
                modalAsignaturaNombre.value = asignaturaName;
                modalDiaSemana.value = dia;
                modalHoraInicio.value = horaInicio;
                modalColorClass.value = colorClass; // Almacenar colorClass en el input oculto de la modal
                // Almacenar la carga horaria completa de la asignatura en un campo oculto de la modal
                modalCargaHorariaData.value = JSON.stringify(cargaHorariaData);


                // Rellenar Docente
                modalDocenteId.value = ''; // Resetear
                modalDocenteNombre.value = 'Seleccione un docente...'; // Resetear visualización
                if (docentesData && docentesData.length > 0) {
                    modalDocenteId.value = docentesData[0].cedula_doc;
                    modalDocenteNombre.value = docentesData[0].name;
                }

                // Cargar Aulas
                await cargarAulas(); // Asegurarse de que las aulas se carguen antes de mostrar la modal
                
                // Disparar el evento de cambio para rellenar modalBloques según la selección inicial de modalTipoHoras
                modalTipoHoras.dispatchEvent(new Event('change'));


                horarioBlockModal.show(); // Esta es la línea que muestra la modal
            }

            // NUEVO: Listener de eventos para modalTipoHoras para actualizar modalBloques
            modalTipoHoras.addEventListener('change', function() {
                const selectedTipo = this.value;
                const asignaturaId = modalAsignaturaId.value;
                const cargaHorariaCompleta = JSON.parse(modalCargaHorariaData.value || '[]');

                modalBloques.innerHTML = ''; // Borrar opciones existentes
                modalBloques.disabled = true;

                if (!selectedTipo) return;

                const cargaParaTipo = cargaHorariaCompleta.find(c => c.tipo === selectedTipo);

                if (cargaParaTipo) {
                    const horasMaximas = parseInt(cargaParaTipo.horas_academicas);
                    const horasYaAsignadas = assignedHoursPerSubject[`${asignaturaId}_${selectedTipo}`] || 0;
                    const horasRestantes = horasMaximas - horasYaAsignadas;

                    if (horasRestantes > 0) {
                        for (let i = 1; i <= horasRestantes && i <= 6; i++) { // Máx 6 bloques según validación
                            modalBloques.add(new Option(i, i));
                        }
                        modalBloques.disabled = false;
                    } else {
                        modalBloques.innerHTML = '<option value="">No hay bloques disponibles</option>';
                    }
                } else {
                    // Este caso idealmente no debería ocurrir si cargaHorariaData se rellena y valida correctamente
                    modalBloques.innerHTML = '<option value="">Carga horaria no definida para este tipo</option>';
                }
            });


            // 9. Crear el bloque visual en la tabla
            function crearBloqueVisual(targetCell, asignaturaId, asignaturaName, dia, horaInicio, horaFin, bloques,
                tipoHoras, aulaId, aulaName, colorClass, rowIndex, colIndex, existingBlockId = null) { // Added existingBlockId
                const bloque = document.createElement('div');
                bloque.classList.add('bloque-horario', colorClass);

                // Establecer la altura del bloque en función del número de espacios que cubre
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

                // Guardar todos los datos necesarios en el elemento, incluyendo docenteId
                Object.assign(bloque.dataset, {
                    asignaturaId,
                    asignaturaName,
                    dia,
                    horaInicio,
                    horaFin,
                    bloques,
                    tipoHoras,
                    aulaId, // Almacenar ID de aula
                    docenteId: modalDocenteId.value, // Obtener el ID del docente realmente seleccionado de la modal
                    periodoId: periodoSelect.value,
                    carreraId: carreraSelect.value,
                    semestreId: semestreSelect.value,
                    turnoId: turnoSelect.value,
                    seccionId: seccionSelect.value,
                    rowIndex: rowIndex, // Almacenar índice de fila
                    colIndex: colIndex, // Almacenar índice de columna
                    blockId: existingBlockId // Almacenar ID de bloque existente si está disponible
                });

                bloque.querySelector('.delete-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    eliminarBloque(bloque);
                });

                // Añadir el bloque a la celda de destino
                targetCell.appendChild(bloque);

                // Marcar las celdas como ocupadas en scheduleGrid
                for (let i = 0; i < bloques; i++) {
                    scheduleGrid[rowIndex + i][colIndex] = { blockElement: true, ref: bloque }; // Almacenar referencia
                }

                actualizarBotonGuardar();
            }

            // 10. Calcular hora de finalización
            function calcularHoraFinSimple(horaInicioStr, bloques) {
                const [h, m] = horaInicioStr.split(':').map(Number);
                let totalMinutosFin = (h * 60 + m) + (bloques * 45);
                let horasFin = Math.floor(totalMinutosFin / 60) % 24;
                let minutosFin = totalMinutosFin % 60;
                return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
            }

            // 11. Esta función ya no es necesaria para la lógica de rowspan
            // function actualizarTablaHorario(dia, horaInicio, bloques) { }

            // 12. Convertir número de día a texto
            function convertirDiaNumeroATexto(diaNumero) {
                const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                return dias[parseInt(diaNumero)];
            }

            // 13. Eliminar bloque y restaurar celdas
            function eliminarBloque(bloqueElement) {
                const {
                    asignaturaId,
                    tipoHoras,
                    dia,
                    horaInicio,
                    bloques: numBloquesStr,
                    rowIndex: rowIndexStr,
                    colIndex: colIndexStr,
                    blockId // Obtener el ID del bloque
                } = bloqueElement.dataset;
                const numBloques = parseInt(numBloquesStr);
                const rowIndex = parseInt(rowIndexStr);
                const colIndex = parseInt(colIndexStr);

                // Actualizar contador de horas asignadas
                const subjectTypeKey = `${asignaturaId}_${tipoHoras}`;
                if (assignedHoursPerSubject[subjectTypeKey]) {
                    assignedHoursPerSubject[subjectTypeKey] -= numBloques;
                    if (assignedHoursPerSubject[subjectTypeKey] <= 0) {
                        delete assignedHoursPerSubject[subjectTypeKey];
                    }
                }

                // Eliminar el elemento del bloque del DOM
                bloqueElement.remove();

                // Limpiar las entradas de scheduleGrid para las celdas que ocupaba este bloque
                for (let i = 0; i < numBloques; i++) {
                    if (rowIndex + i < scheduleGrid.length) { // Asegurarse de que el índice esté dentro de los límites
                        scheduleGrid[rowIndex + i][colIndex] = null; // Marcar como vacío
                    }
                }

                // Eliminar del array bloquesParaGuardar
                const indexToRemove = bloquesParaGuardar.findIndex(b =>
                    (blockId && b.id === blockId) || // Si tiene un ID, coincidir por ID
                    (b.asignatura_id === asignaturaId && b.dia_semana === parseInt(dia) && b.hora_inicio === horaInicio)
                );
                if (indexToRemove !== -1) {
                    bloquesParaGuardar.splice(indexToRemove, 1);
                }


                actualizarBotonGuardar();
            }

            // 14. Actualizar botón de guardar
            function actualizarBotonGuardar() {
                guardarBtn.disabled = bloquesParaGuardar.length === 0;
            }

            // --- Lógica para la Modal del Bloque de Horario ---
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
                     Swal.fire('Error', 'Tipo de hora inválido para esta asignatura.', 'error');
                     return;
                }
                const horasMaximas = parseInt(cargaParaTipo.horas_academicas);
                const horasYaAsignadas = assignedHoursPerSubject[`${asignaturaId}_${tipoHoras}`] || 0;
                
                if ((horasYaAsignadas + bloques) > horasMaximas) {
                    Swal.fire('Límite Excedido', `No puedes añadir ${bloques} bloques de tipo '${tipoHoras}'. Solo quedan ${horasMaximas - horasYaAsignadas} disponibles de ${horasMaximas}.`, 'warning');
                    return;
                }
                // --- FIN VALIDACIÓN FRONTEND: Carga Horaria ---

                if (!asignaturaId || !docenteId || !diaSemana || !horaInicio || !tipoHoras || isNaN(bloques) || bloques < 1 || !aulaId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los campos requeridos (Asignatura, Docente, Tipo de Horas, Bloques y Aula).', 'warning');
                    return;
                }

                const horaFin = calcularHoraFinSimple(horaInicio, bloques);

                // Comprobar la disponibilidad de las celdas de nuevo, considerando el número de bloques seleccionado
                const inicioRowIndex = getTimeRowIndex(horaInicio);
                const diaInt = parseInt(diaSemana);
                for (let i = 0; i < bloques; i++) {
                    const currentRowIndex = inicioRowIndex + i;
                    if (currentRowIndex >= scheduleGrid.length || scheduleGrid[currentRowIndex][diaInt] !== null) {
                        Swal.fire('Celdas Ocupadas', 'Algunas de las celdas seleccionadas ya están ocupadas o no están disponibles para la cantidad de bloques elegida.', 'warning');
                        return;
                    }
                }

                // Actualizar contador de horas asignadas
                const subjectTypeKey = `${asignaturaId}_${tipoHoras}`;
                assignedHoursPerSubject[subjectTypeKey] = (assignedHoursPerSubject[subjectTypeKey] || 0) + bloques;

                // Añadir al array para guardar
                let bloqueId = null;
                // Buscar si ya existe un bloque en la misma posición (para edición).
                // Esta lógica puede necesitar ajustes si permites mover bloques existentes.
                // Por ahora, asume que es un bloque nuevo o uno existente si se pasa el ID.
                // La función principal de actualización en el controlador maneja los bloques existentes frente a los nuevos.
                // Aquí, simplemente lo estamos agregando a un array temporal para el envío.
                // Si esto es para editar y se está arrastrando un bloque existente, su ID debería conservarse.
                // Este `edit.blade.php` maneja la precarga de bloques existentes, y cualquier nuevo arrastre es nuevo.
                // Así que no es necesario buscar el ID existente aquí, solo agregarlo al array como nuevo.
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

                // Añadir bloque visual al horario
                const targetCell = document.querySelector(`[data-dia="${diaSemana}"][data-hora="${horaInicio}"]`);
                if (targetCell) {
                    crearBloqueVisual(targetCell, asignaturaId, asignaturaName, diaSemana, horaInicio, horaFin, bloques, tipoHoras, aulaId, aulaName, colorClass, inicioRowIndex, diaInt, bloqueId); // Pasar blockId
                }

                // Marcar celdas como ocupadas en scheduleGrid
                for (let i = 0; i < bloques; i++) {
                    scheduleGrid[inicioRowIndex + i][diaInt] = { blockElement: true }; // Marcar como ocupado
                }

                horarioBlockModal.hide();
                Swal.fire('¡Bloque Añadido!', 'El bloque se ha añadido al horario.', 'success');
                actualizarBotonGuardar();
            });

            // Función para cargar aulas en el select de la modal
            async function cargarAulas() {
                modalAula.innerHTML = '<option value="">Cargando Aulas...</option>';
                modalAula.disabled = true;

                try {
                    const response = await fetch('/api/aulas'); // Asegúrate de que esta ruta exista en tu Laravel
                    console.log('Respuesta de /api/aulas:', response); // Log de depuración
                    if (!response.ok) throw new Error(`Error al cargar aulas: ${response.statusText}`);
                    const aulas = await response.json();
                    console.log('Datos de aulas recibidos:', aulas); // Log de depuración

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
                    Swal.fire('Error', `No se pudieron cargar las aulas: ${error.message}`, 'error'); // Error más específico
                }
            }


            // 15. Enviar formulario
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
                const asignaturaCompartidaId = asignaturaCompartidaIdHidden.value;

                if (!periodoId || !carreraId || !turnoId || !semestreId || !seccionId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los filtros antes de guardar el horario.', 'warning');
                    return;
                }
                
                const formData = {
                    _method: 'PUT', // Para que Laravel lo reconozca como update
                    periodo_id: periodoId,
                    turno_id: turnoId,
                    carrera_id: carreraId,
                    semestre_id: semestreId,
                    seccion_id: seccionId,
                    bloques: bloquesParaGuardar, // Nombre correcto para el backend
                    asignatura_compartida_id: asignaturaCompartidaId
                };

                try {
                    const response = await fetch(this.action, {
                        method: 'POST', // Laravel espera POST + _method=PUT
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(formData)
                    });

                    const responseText = await response.text();
                    let data;
                    try {
                        data = JSON.parse(responseText);
                    } catch (err) {
                        throw new Error('Respuesta inesperada del servidor: ' + responseText);
                    }
                    if (!response.ok || !data.success) {
                        let errorMessage = data.message || data.error || 'Error al guardar.';
                        if (data.errors) {
                            let validationErrors = Object.values(data.errors).flat().join('<br>');
                            errorMessage += '<br>' + validationErrors;
                        }
                        Swal.fire('Error', errorMessage, 'error');
                        return;
                    }
                    Swal.fire('Éxito', data.message, 'success').then(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    });
                } catch (error) {
                    console.error('Error enviando:', error);
                    Swal.fire('Error', `Ocurrió un error: ${error.message}`, 'error');
                }
            });

            // NUEVA LÓGICA PARA ASIGNATURA COMPARTIDA
            btnAsignaturaCompartida.addEventListener('click', function() {
                modalAsignaturaCompartida.show();
                // Cuando se abre la modal, reiniciar sus filtros
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
                    // Comprobar si esta asignatura ya está en la lista principal (ya sea original o compartida previamente añadida)
                    const existingAsignaturaItem = document.querySelector(`#listaAsignaturas [data-asignatura-id="${selectedAsignaturaCompartidaId}"]`);
                    if (existingAsignaturaItem) {
                        Swal.fire('Advertencia', 'Esta asignatura ya está en la lista de asignaturas disponibles.', 'warning');
                        return;
                    }

                    // Recuperar datos de la opción seleccionada en la modal
                    const selectedOption = asignaturaCompartidaSelect.options[asignaturaCompartidaSelect.selectedIndex];
                    const docentesData = JSON.parse(selectedOption.dataset.docentes || '[]');
                    const cargaHorariaData = JSON.parse(selectedOption.dataset.cargaHoraria || '[]');

                    // Crear un nuevo elemento arrastrable para el panel izquierdo
                    const newAsignaturaItem = document.createElement('div');
                    newAsignaturaItem.classList.add('list-group-item', 'asignatura-item', 'bg-shared-asignatura', 'text-white', 'mb-2', 'py-2', 'px-3');
                    newAsignaturaItem.draggable = true;
                    newAsignaturaItem.dataset.asignaturaId = selectedAsignaturaCompartidaId;
                    newAsignaturaItem.dataset.asignaturaName = selectedAsignaturaCompartidaText;
                    newAsignaturaItem.dataset.docentes = JSON.stringify(docentesData);
                    newAsignaturaItem.dataset.cargaHoraria = JSON.stringify(cargaHorariaData);
                    newAsignaturaItem.dataset.colorClass = 'bg-shared-asignatura'; // Almacenar la clase para consistencia

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
                    configurarDragAndDrop(); // Hacer que el nuevo elemento sea arrastrable

                    asignaturaCompartidaIdHidden.value = selectedAsignaturaCompartidaId;
                    sharedAsignaturaLabel.textContent = `Compartida: ${selectedAsignaturaCompartidaText}`;
                    sharedAsignaturaLabel.style.display = 'inline-block';
                    Swal.fire('Asignatura Compartida', 'Asignatura seleccionada y añadida a la lista.', 'success');
                    modalAsignaturaCompartida.hide();
                } else {
                    Swal.fire('Advertencia', 'Por favor, seleccione una asignatura para compartir.', 'warning');
                }
            });

            // Restablecer la selección de asignatura compartida cuando la modal está oculta
            document.getElementById('modalAsignaturaCompartida').addEventListener('hidden.bs.modal', function () {
                asignaturaCompartidaSelect.value = '';
            });

            // Funciones para los filtros de la modal de asignatura compartida
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

            // Disparar la carga de asignaturas cuando se hace clic en el botón "Buscar Asignaturas" de la modal
            buscarAsignaturasModalBtn.addEventListener('click', cargarAsignaturasParaModalCompartida);


            async function cargarSemestresShared(turnoId) {
                try {
                    const response = await fetch(`{{ url('/horario/api/semestres-por-turno/') }}/${turnoId}`);
                    if (!response.ok) throw new Error('Error al cargar semestres para la modal');
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
                    console.error('Error cargando semestres para la modal:', error);
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
                    if (!response.ok) throw new Error(`Error al obtener secciones para la modal: ${response.status}`);
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
                    console.error('Error cargando secciones para la modal:', error);
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
                    if (!response.ok) throw new Error(`Error al obtener asignaturas para la modal: ${response.status}`);
                    const asignaturas = await response.json();

                    asignaturaCompartidaSelect.innerHTML = '<option value="">Seleccione una Asignatura</option>';
                    if (asignaturas.length > 0) {
                        asignaturas.forEach(asignatura => {
                            const option = new Option(asignatura.name, asignatura.asignatura_id);
                            // Almacenar docentes y carga_horaria en el dataset de la opción
                            option.dataset.docentes = JSON.stringify(asignatura.docentes);
                            option.dataset.cargaHoraria = JSON.stringify(asignatura.carga_horaria);
                            asignaturaCompartidaSelect.add(option);
                        });
                        asignaturaCompartidaSelect.disabled = false;
                    } else {
                        asignaturaCompartidaSelect.innerHTML = '<option value="">No hay asignaturas disponibles</option>';
                    }
                } catch (error) {
                    console.error('Error cargando asignaturas compartidas para la modal:', error);
                    Swal.fire('Error', 'No se pudieron cargar las asignaturas para la selección compartida.', 'error');
                }
            }

            // Al cargar la vista, precargar el horario existente en la tabla
            function precargarHorarioExistente() {
                if (!window.bloquesPrecargados || !Array.isArray(window.bloquesPrecargados)) return;
                generarHorario(); // Generar la cuadrícula vacía primero
                
                // Inicializar assignedHoursPerSubject basado en los bloques precargados
                window.bloquesPrecargados.forEach(bloque => {
                    const subjectTypeKey = `${bloque.asignatura_id}_${bloque.tipo_horas}`;
                    assignedHoursPerSubject[subjectTypeKey] = (assignedHoursPerSubject[subjectTypeKey] || 0) + bloque.bloques;
                });

                window.bloquesPrecargados.forEach((bloque, idx) => {
                    const rowIndex = getTimeRowIndex(bloque.hora_inicio);
                    const diaInt = parseInt(bloque.dia_semana);
                    const targetCell = document.querySelector(`[data-dia="${diaInt}"][data-hora="${bloque.hora_inicio}"]`);
                    if (targetCell) {
                        // Color por asignatura (opcional)
                        // Usar un mapeo de colores consistente o recuperar de datos almacenados si están disponibles
                        const colorClass = `bg-asignatura-${((idx % 7) + 1)}`; 
                        
                        crearBloqueVisual(
                            targetCell,
                            bloque.asignatura_id,
                            (bloque.asignatura && (bloque.asignatura.name || bloque.asignatura.nombre)) || '',
                            bloque.dia_semana,
                            bloque.hora_inicio,
                            bloque.hora_fin,
                            bloque.bloques,
                            bloque.tipo_horas,
                            bloque.aula_id,
                            (bloque.aula && (bloque.aula.nombre || bloque.aula.name)) || '',
                            colorClass,
                            rowIndex,
                            diaInt,
                            bloque.id
                        );
                        // No es necesario marcar las celdas como ocupadas aquí, ya que crearBloqueVisual ya lo hace
                        // y ya inicializamos assignedHoursPerSubject arriba.
                        bloquesParaGuardar.push({
                            id: bloque.id,
                            asignatura_id: bloque.asignatura_id,
                            docente_id: bloque.docente_id,
                            dia_semana: bloque.dia_semana,
                            hora_inicio: bloque.hora_inicio,
                            hora_fin: bloque.hora_fin,
                            tipo_horas: bloque.tipo_horas,
                            bloques: bloque.bloques,
                            aula_id: bloque.aula_id
                        });
                    }
                });
                actualizarBotonGuardar();
            }

            // Precargar bloques desde PHP (debes pasar la variable desde el controlador)
            window.bloquesPrecargados = @json($bloques);
            precargarHorarioExistente();

            // Precargar asignaturas disponibles en el panel izquierdo al cargar la vista de edición
            function precargarAsignaturasDisponibles() {
                if (!window.asignaturasDisponiblesInitial || !Array.isArray(window.asignaturasDisponiblesInitial)) return;
                if (!listaAsignaturas) return;
                if (window.asignaturasDisponiblesInitial.length === 0) {
                    listaAsignaturas.innerHTML = `<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle me-2"></i>No hay asignaturas disponibles.</div>`;
                    return;
                }
                listaAsignaturas.innerHTML = window.asignaturasDisponiblesInitial.map((asignatura, index) => {
                    const colorClass = `bg-asignatura-${((index % 7) + 1)}`;
                    let cargaHorariaText = 'No definida';
                    if (asignatura.carga_horaria && asignatura.carga_horaria.length > 0) {
                        cargaHorariaText = asignatura.carga_horaria.map(c => `${c.tipo.substring(0,1)}:${c.horas_academicas}b`).join(', ');
                    }
                    return `
                        <div class="list-group-item asignatura-item ${colorClass} text-white mb-2 py-2 px-3"
                            draggable="true"
                            data-asignatura-id="${asignatura.asignatura_id}"
                            data-asignatura-name="${asignatura.name}"
                            data-docentes='${JSON.stringify(asignatura.docentes)}'
                            data-carga-horaria='${JSON.stringify(asignatura.carga_horaria)}'
                            data-color-class="${colorClass}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-size: 0.8rem;">${asignatura.name} <small>(${cargaHorariaText})</small></span>
                                <i class="fas fa-arrows-alt ms-2"></i>
                            </div>
                        </div>
                    `;
                }).join('');
                configurarDragAndDrop();

                // Habilitar el botón de asignatura compartida si hay asignaturas disponibles
                if (window.asignaturasDisponiblesInitial.length > 0) {
                    btnAsignaturaCompartida.disabled = false;
                }

                // Si se precarga una asignatura compartida, actualizar la etiqueta
                @if($horario->asignaturaCompartida)
                    asignaturaCompartidaIdHidden.value = "{{ $horario->asignatura_compartida_id }}";
                    sharedAsignaturaLabel.textContent = `Compartida: {{ $horario->asignaturaCompartida->name }}`;
                    sharedAsignaturaLabel.style.display = 'inline-block';
                @endif
            }

            // Precargar asignaturas desde PHP (debes pasar la variable desde el controlador)
            window.asignaturasDisponiblesInitial = @json($asignaturas);
            precargarAsignaturasDisponibles();
        });
    </script>
</body>

</html>
