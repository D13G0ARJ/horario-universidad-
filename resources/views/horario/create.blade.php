<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Horario</title> {{-- Título actualizado para la vista --}}

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <style>
        /* Eliminar scrollbars globalmente */
        ::-webkit-scrollbar {
            display: none;
        }

        html {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
            overflow: hidden;
            height: 100%;
        }

        body {
            overflow: hidden;
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        /* Ajustar estructura principal */
        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            /* Ocupar toda la altura visible */
            overflow: hidden;
        }

        .content-wrapper {
            flex: 1;
            overflow: hidden;
            /* Deshabilitar scroll interno */
            padding-bottom: 60px;
        }

        /* Ajustar sidebar */
        .main-sidebar {
            height: 100vh;
            overflow-y: hidden;
            /* Ocultar scroll en sidebar */
            position: fixed;
        }

        /* Asegurar contenido principal */
        .content {
            margin-top: 60px;
            /* Ajustar según altura de tu navbar */
            height: calc(100vh - 120px);
            /* 60px header + 60px footer */
            overflow: hidden;
        }

        /* Estilos para elementos en arrastre */
        .asignatura-item.dragging {
            opacity: 0.5;
            transform: scale(0.98);
        }
        
        /* Estilo para celdas con hover */
        .drop-zone.hover-cell { 
            background-color: rgba(0, 123, 255, 0.1) !important;
            border: 2px dashed #007bff !important;
        }
        
        /* Bloques de horario */
        .bloque-horario {
            position: relative;
            height: 60px; 
            border-radius: 4px;
            padding: 8px;
            font-size: 0.85rem;
            color: white;
            cursor: move;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s;
            margin: 2px 0;
            display: flex; 
            flex-direction: column;
            justify-content: space-between;
        }
        
        /* Bloques expandidos */
        .bloque-horario.expanded-block {
            /* No se usa gridRowEnd, se usa rowspan y height */
        }
        
        /* Botones en bloques */
        .bloque-horario .btn {
            opacity: 1; 
            transition: none; 
        }
        
        /* Colores para asignaturas */
        .bg-asignatura-1 { background: linear-gradient(135deg, #4e73df, #3a56c8); }
        .bg-asignatura-2 { background: linear-gradient(135deg, #1cc88a, #17a673); }
        .bg-asignatura-3 { background: linear-gradient(135deg, #36b9cc, #2a96a5); }
        .bg-asignatura-4 { background: linear-gradient(135deg, #f6c23e, #e0b12d); }
        .bg-asignatura-5 { background: linear-gradient(135deg, #e74a3b, #d62c1a); }
        .bg-asignatura-6 { background: linear-gradient(135deg, #858796, #6c6e7e); }
        .bg-asignatura-7 { background: linear-gradient(135deg, #5a5c69, #484a58); }
        
        /* Scroll personalizado */
        .card-body::-webkit-scrollbar {
            width: 10px;
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
        
        /* Ajustes de tabla para scroll */
        #horarioTable thead th, #horarioTable tbody td, #horarioTable tbody th {
            vertical-align: top; 
            padding: 5px; 
            height: 60px; 
            box-sizing: border-box; 
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
        }

        /* Estilos para los contenedores de asignaturas y horario para que se adapten a la altura */
        #asignaturasContainer {
            flex-grow: 1; 
            overflow-y: auto; /* Asegura scroll si el contenido de asignaturas es largo */
        }
        .card-body.p-0 > .table-responsive {
            flex-grow: 1; 
            overflow-y: auto; /* Asegura scroll si el contenido del horario es largo */
        }

        /* Contenedor principal de paneles para asegurar que la altura se adapte */
        .row.g-3 {
            height: auto; 
        }

        /* Asegurar que los cards dentro de las columnas flexibles tomen la altura disponible */
        .col-md-3 .card, .col-md-9 .card {
            display: flex;
            flex-direction: column;
            height: 100%; 
        }
        .col-md-3 .card-body, .col-md-9 .card-body {
            flex-grow: 1; 
            display: flex; 
            flex-direction: column;
        }
    </style> 
</head>

<body>
    {{-- El contenido de la vista --}}
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 text-primary">
                    <i class="fas fa-calendar-plus mr-2"></i>Crear Nuevo Horario
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('horario.index') }}">Horarios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                    </ol>
                </nav>
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
                <input type="hidden" name="horario_data" id="horarioData"> {{-- Para enviar los bloques de horario --}}
                
                <div class="card-body p-4">
                    <div class="row g-3 mb-4 bg-light p-3 rounded">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="periodo_id" class="form-label fw-bold">Periodo</label>
                            <select id="periodo_id" name="periodo_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="carrera_id" class="form-label fw-bold">Carrera</label>
                            <select id="carrera_id" name="carrera_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}">{{ $carrera->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="turno_id" class="form-label fw-bold">Turno</label>
                            <select id="turno_id" name="turno_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="semestre_id" class="form-label fw-bold">Semestre</label>
                            <select id="semestre_id" name="semestre_id" class="form-select form-select-lg" required disabled>
                                <option value="">Seleccione turno</option>
                            </select>
                        </div>
                        
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="seccion_id" class="form-label fw-bold">Sección</label>
                            <select id="seccion_id" name="seccion_id" class="form-select form-select-lg" required disabled>
                                <option value="">Complete filtros</option>
                            </select>
                        </div>
                        
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
                                            <i class="fas fa-info-circle me-2"></i>Seleccione una sección y haga clic en Buscar
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-9 d-flex flex-column">
                            <div class="card flex-grow-1 border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Horario - Lunes a Sábado</h5>
                                </div>
                                <div class="card-body p-0 d-flex flex-column">
                                    <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                                        <table class="table table-bordered table-hover mb-0" id="horarioTable">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="text-center" style="width: 10%">Hora</th>
                                                    <th class="text-center" style="width: 15%">Lunes</th>
                                                    <th class="text-center" style="width: 15%">Martes</th>
                                                    <th class="text-center" style="width: 15%">Miércoles</th>
                                                    <th class="text-center" style="width: 15%">Jueves</th>
                                                    <th class="text-center" style="width: 15%">Viernes</th>
                                                    <th class="text-center" style="width: 15%">Sábado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="horarioBody">
                                                {{-- Las filas del horario se generarán dinámicamente --}}
                                                <tr>
                                                    <td colspan="7" class="text-center py-5 text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>Complete los filtros y haga clic en Buscar
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

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Script principal de la vista --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            let selectedBlock = null;
            let initialCell = null;
            let isExpanding = false;
            const blockDuration = 45; // Duración en minutos de cada bloque (para referencia)
            
            // Elementos del DOM
            const periodoSelect = document.getElementById('periodo_id');
            const turnoSelect = document.getElementById('turno_id');
            const semestreSelect = document.getElementById('semestre_id');
            const carreraSelect = document.getElementById('carrera_id');
            const seccionSelect = document.getElementById('seccion_id');
            const buscarBtn = document.getElementById('buscarHorarios');
            const listaAsignaturas = document.getElementById('listaAsignaturas');
            const horarioBody = document.getElementById('horarioBody');
            const guardarBtn = document.getElementById('guardarHorario');
            
            // 1. Cargar semestres según turno seleccionado
            turnoSelect.addEventListener('change', function() {
                const turnoId = this.value;
                semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                semestreSelect.disabled = true;
                seccionSelect.innerHTML = '<option value="">Complete filtros</option>'; // Resetear sección
                seccionSelect.disabled = true;

                if (!turnoId) return;

                const loadingOption = new Option('Cargando semestres...', '');
                loadingOption.disabled = true;
                semestreSelect.add(loadingOption);

                fetch(`{{ url('/horario/api/semestres-por-turno/') }}/${turnoId}`) // Usar url() helper
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                        data.forEach(semestre => {
                            const option = new Option(`${semestre.numero}º Semestre`, semestre.id_semestre); // Usar id_semestre
                            semestreSelect.add(option);
                        });
                        semestreSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error al cargar semestres:', error);
                        semestreSelect.innerHTML = '<option value="">Error al cargar</option>';
                        semestreSelect.disabled = false;
                    });
            });
            
            // 2. Función para cargar secciones según los filtros
            async function cargarSecciones() {
                const carreraId = carreraSelect.value;
                const semestreId = semestreSelect.value;
                const turnoId = turnoSelect.value;
                
                seccionSelect.innerHTML = '<option value="">Cargando...</option>';
                seccionSelect.disabled = true;

                if (!carreraId || !semestreId || !turnoId) {
                    seccionSelect.innerHTML = '<option value="">Complete filtros</option>';
                    return;
                }

                try {
                    const url = new URL(`{{ url('/horario/obtener-secciones') }}`); // Usar url() helper
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);

                    const response = await fetch(url);
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`Error al obtener secciones: ${response.status} - ${errorText}`);
                    }
                    
                    const data = await response.json();
                    
                    seccionSelect.innerHTML = '<option value="">Seleccione sección</option>'; 
                    if (data.length > 0) {
                        data.forEach(s => {
                            // Asegurarse de que 's' es un objeto y tiene 'codigo_seccion'
                            const value = s.codigo_seccion || s.id; // Fallback a 'id' si el backend envía 'id'
                            const text = s.codigo_seccion || s.text; // Fallback a 'text' si el backend envía 'text'
                            const option = new Option(text, value); 
                            seccionSelect.add(option);
                        });
                    } else {
                        seccionSelect.innerHTML = '<option value="">No hay secciones</option>';
                    }
                        
                    seccionSelect.disabled = false;
                } catch (error) {
                    console.error('Error:', error);
                    seccionSelect.innerHTML = '<option value="">Error al cargar</option>';
                    seccionSelect.disabled = false;
                }
            }

            // 3. Event listeners para los filtros que activan la carga de secciones
            carreraSelect.addEventListener('change', cargarSecciones);
            semestreSelect.addEventListener('change', cargarSecciones); // Semestre ya se carga después del turno

            // 4. Función principal al hacer clic en Buscar
            buscarBtn.addEventListener('click', async function() {
                const periodoId = periodoSelect.value;
                const carreraId = carreraSelect.value;
                const turnoId = turnoSelect.value;
                const semestreId = semestreSelect.value;
                const seccionId = seccionSelect.value;
                
                if (!periodoId || !carreraId || !turnoId || !semestreId || !seccionId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los filtros (Periodo, Carrera, Turno, Semestre, Sección) antes de buscar.', 'warning');
                    return;
                }
                
                // Mostrar carga en asignaturas
                listaAsignaturas.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando asignaturas...</p>
                    </div>
                `;
                
                // Mostrar carga en horario (se generará la tabla vacía)
                horarioBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Preparando horario...</p>
                        </td>
                    </tr>
                `;
                
                try {
                    // Obtener asignaturas filtradas
                    const url = new URL(`{{ url('/horario/asignaturas') }}`); // Usar url() helper
                    url.searchParams.append('seccion_id', seccionId);
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);
                    url.searchParams.append('periodo_id', periodoId); // Pasar el periodo también

                    console.log('Fetching asignaturas from URL:', url.toString()); // DEBUG

                    const response = await fetch(url);
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`Error al obtener asignaturas: ${response.status} - ${errorText}`);
                    }
                    
                    const asignaturas = await response.json();
                    console.log('Asignaturas recibidas:', asignaturas); // DEBUG
                    
                    // Mostrar asignaturas en el panel izquierdo
                    if (asignaturas.length > 0) {
                        listaAsignaturas.innerHTML = asignaturas.map((asignatura, index) => {
                            const colorClass = `bg-asignatura-${(index % 7) + 1}`;
                            let cargaHorariaText = '';
                            if (asignatura.carga_horaria && asignatura.carga_horaria.length > 0) {
                                cargaHorariaText = asignatura.carga_horaria.map(c => `${c.tipo}: ${c.horas_academicas}h`).join(', ');
                            } else {
                                cargaHorariaText = 'Sin carga';
                            }

                            return `
                                <div class="list-group-item asignatura-item ${colorClass} text-white mb-2" 
                                     draggable="true" 
                                     data-asignatura-id="${asignatura.asignatura_id}"
                                     data-asignatura-name="${asignatura.name}"
                                     data-docentes='${JSON.stringify(asignatura.docentes)}'
                                     data-carga-horaria='${JSON.stringify(asignatura.carga_horaria)}'>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>${asignatura.name} <small>(${cargaHorariaText})</small></span>
                                        <i class="fas fa-arrows-alt"></i>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        
                        // Configurar eventos de drag and drop
                        configurarDragAndDrop();
                    } else {
                        listaAsignaturas.innerHTML = `
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                No hay asignaturas asignadas a esta sección con los filtros seleccionados.
                            </div>
                        `;
                    }
                    
                    // Generar la estructura del horario (tabla vacía lista para arrastrar)
                    generarHorario();
                    
                } catch (error) {
                    console.error('Error en Buscar Asignaturas:', error);
                    Swal.fire('Error', `Ocurrió un error al cargar las asignaturas: ${error.message}`, 'error');
                    listaAsignaturas.innerHTML = `
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar las asignaturas
                        </div>
                    `;
                    // Asegurarse de que la tabla del horario también se resetee
                    horarioBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-info-circle me-2"></i>Complete los filtros y haga clic en Buscar
                            </td>
                        </tr>
                    `;
                }
            });
            
            // 5. Configurar eventos de drag and drop para las asignaturas
            function configurarDragAndDrop() {
                document.querySelectorAll('.asignatura-item').forEach(item => {
                    item.addEventListener('dragstart', function(e) {
                        e.dataTransfer.setData('text/plain', JSON.stringify({
                            asignatura_id: this.dataset.asignaturaId,
                            name: this.dataset.asignaturaName,
                            docentes: JSON.parse(this.dataset.docentes),
                            cargaHoraria: JSON.parse(this.dataset.cargaHoraria),
                            colorClass: Array.from(this.classList).find(cls => cls.startsWith('bg-asignatura-'))
                        }));
                        this.classList.add('dragging');
                    });
                    
                    item.addEventListener('dragend', function() {
                        this.classList.remove('dragging');
                    });
                });
            }
            
            // 6. Generar la estructura del horario con bloques de 45 minutos
            function generarHorario() {
                horarioBody.innerHTML = '';
                
                // Generar bloques de tiempo (de 7:00 AM a 9:00 PM, bloques de 45 minutos)
                const horaInicio = 7;
                const horaFin = 21; // Hasta las 21:00 (9 PM)

                for (let h = horaInicio; h <= horaFin; h++) {
                    // Horas en punto (ej. 07:00, 08:00)
                    let horaFormato = `${h.toString().padStart(2, '0')}:00`;
                    let fila = document.createElement('tr');
                    fila.innerHTML = `
                        <th class="time-slot" data-time="${horaFormato}">${horaFormato}</th>
                        <td class="drop-zone" data-hora="${horaFormato}" data-dia="1"></td>
                        <td class="drop-zone" data-hora="${horaFormato}" data-dia="2"></td>
                        <td class="drop-zone" data-hora="${horaFormato}" data-dia="3"></td>
                        <td class="drop-zone" data-hora="${horaFormato}" data-dia="4"></td>
                        <td class="drop-zone" data-hora="${horaFormato}" data-dia="5"></td>
                        <td class="drop-zone" data-hora="${horaFormato}" data-dia="6"></td>
                    `;
                    horarioBody.appendChild(fila);

                    // Horas y 45 minutos (ej. 07:45, 08:45)
                    // Asegurarse de no agregar 21:45 si la hora fin es 21:00
                    if (h < horaFin) {
                        horaFormato = `${h.toString().padStart(2, '0')}:45`;
                        fila = document.createElement('tr');
                        fila.innerHTML = `
                            <th class="time-slot" data-time="${horaFormato}">${horaFormato}</th>
                            <td class="drop-zone" data-hora="${horaFormato}" data-dia="1"></td>
                            <td class="drop-zone" data-hora="${horaFormato}" data-dia="2"></td>
                            <td class="drop-zone" data-hora="${horaFormato}" data-dia="3"></td>
                            <td class="drop-zone" data-hora="${horaFormato}" data-dia="4"></td>
                            <td class="drop-zone" data-hora="${horaFormato}" data-dia="5"></td>
                            <td class="drop-zone" data-hora="${horaFormato}" data-dia="6"></td>
                        `;
                        horarioBody.appendChild(fila);
                    }
                }
                
                // Configurar eventos para las celdas del horario
                configurarCeldasHorario();
            }
            
            // 7. Configurar eventos para las celdas del horario (dragover, dragleave, drop)
            function configurarCeldasHorario() {
                const celdas = document.querySelectorAll('.drop-zone'); // Usar .drop-zone como selector
                
                celdas.forEach(celda => {
                    celda.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.classList.add('hover-cell');
                    });
                    
                    celda.addEventListener('dragleave', function() {
                        this.classList.remove('hover-cell');
                    });
                    
                    celda.addEventListener('drop', handleDrop); // Usar la función handleDrop existente
                });
            }
            
            // 8. Función para manejar el drop (arrastrar y soltar)
            async function handleDrop(e) {
                e.preventDefault();
                this.classList.remove('hover-cell');
                
                const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                const asignaturaId = data.asignatura_id;
                const asignaturaName = data.name;
                const docentesData = data.docentes; // Ya viene parseado
                const cargaHorariaData = data.cargaHoraria; // Ya viene parseado
                const colorClass = data.colorClass;

                const dia = this.dataset.dia;
                const horaInicio = this.dataset.hora;
                
                // Verificar si la celda ya tiene un bloque
                if (this.querySelector('.bloque-horario')) {
                    Swal.fire('Advertencia', 'Ya existe un bloque en esta celda. Elimínelo primero.', 'warning');
                    return;
                }

                // Crear el menú contextual para seleccionar tipo y duración
                let tipoOptionsHtml = '';
                if (cargaHorariaData && cargaHorariaData.length > 0) {
                    cargaHorariaData.forEach(carga => {
                        tipoOptionsHtml += `<option value="${carga.tipo}">${carga.tipo} (${carga.horas_academicas}h)</option>`;
                    });
                } else {
                    tipoOptionsHtml = `<option value="teorica">Teórica (Sin carga definida)</option>`; // Opción por defecto si no hay carga
                }

                let docenteOptionsHtml = '';
                if (docentesData && docentesData.length > 0) {
                    docentesData.forEach(docente => {
                        docenteOptionsHtml += `<option value="${docente.cedula_doc}">${docente.name}</option>`;
                    });
                } else {
                    docenteOptionsHtml = `<option value="">Sin docentes asignados</option>`;
                }

                const { value: formValues } = await Swal.fire({
                    title: 'Configurar Bloque',
                    html: `
                        <p class="text-start">Asignatura: <strong>${asignaturaName}</strong></p>
                        <p class="text-start">Día: <strong>${convertirDiaNumeroATexto(dia)}</strong></p>
                        <p class="text-start">Hora de inicio: <strong>${horaInicio}</strong></p>
                        <hr>
                        <div class="mb-3 text-start">
                            <label for="swal-tipo" class="form-label">Tipo de Horas:</label>
                            <select id="swal-tipo" class="swal2-input form-control">
                                ${tipoOptionsHtml}
                            </select>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="swal-bloques" class="form-label">Bloques (45 min c/u):</label>
                            <input type="number" id="swal-bloques" class="swal2-input form-control" value="1" min="1" max="8">
                        </div>
                        <div class="mb-3 text-start">
                            <label for="swal-docente" class="form-label">Docente:</label>
                            <select id="swal-docente" class="swal2-input form-control">
                                ${docenteOptionsHtml}
                            </select>
                        </div>
                        <div class="text-muted text-start mt-2">
                            <small>Cada bloque dura 45 minutos.</small>
                        </div>
                    `,
                    focusConfirm: false,
                    preConfirm: () => {
                        const tipo = Swal.getPopup().querySelector('#swal-tipo').value;
                        const bloques = parseInt(Swal.getPopup().querySelector('#swal-bloques').value);
                        const docenteSeleccionado = Swal.getPopup().querySelector('#swal-docente').value;

                        if (isNaN(bloques) || bloques < 1) {
                            Swal.showValidationMessage('Por favor, ingrese un número válido de bloques.');
                            return false;
                        }
                        if (!docenteSeleccionado) {
                            Swal.showValidationMessage('Por favor, seleccione un docente.');
                            return false;
                        }
                        return { tipo, bloques, docenteSeleccionado };
                    }
                });

                if (formValues) {
                    const { tipo, bloques, docenteSeleccionado } = formValues;
                    const horaFin = calcularHoraFinSimple(horaInicio, bloques);
                    const duracionMinutos = bloques * 45;

                    crearBloqueHorario(this, asignaturaId, asignaturaName, dia, horaInicio, horaFin, duracionMinutos, bloques, tipo, docenteSeleccionado, colorClass);
                    actualizarTablaHorario(dia, horaInicio, horaFin, bloques);
                }
            }
            
            // 9. Crear el bloque visual en la tabla
            function crearBloqueHorario(cell, asignaturaId, asignaturaName, dia, horaInicio, horaFin, duracionMinutos, bloques, tipoHoras, docenteId, colorClass) {
                const bloque = document.createElement('div');
                bloque.classList.add('bloque-horario', colorClass, 'text-white', 'rounded', 'p-1', 'position-relative');
                
                const baseCellHeight = cell.offsetHeight; 
                bloque.style.height = `${bloques * baseCellHeight}px`;

                const docenteName = document.querySelector(`#swal-docente option[value="${docenteId}"]`)?.textContent || 'N/A';

                bloque.innerHTML = `
                    <div class="d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-truncate">${asignaturaName}</span>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-light p-0 delete-btn" title="Eliminar" style="width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-auto small">
                            <span class="d-block">${horaInicio} - ${horaFin}</span>
                            <span class="d-block">(${tipoHoras} - ${bloques} bloques)</span>
                            <span class="d-block">Docente: ${docenteName}</span>
                        </div>
                    </div>
                `;
                
                bloque.dataset.asignaturaId = asignaturaId;
                bloque.dataset.asignaturaName = asignaturaName;
                bloque.dataset.dia = dia;
                bloque.dataset.horaInicio = horaInicio;
                bloque.dataset.horaFin = horaFin;
                bloque.dataset.duracion = duracionMinutos; 
                bloque.dataset.bloques = bloques;
                bloque.dataset.tipoHoras = tipoHoras;
                bloque.dataset.docenteId = docenteId;

                bloque.dataset.periodoId = periodoSelect.value;
                bloque.dataset.carreraId = carreraSelect.value;
                bloque.dataset.semestreId = semestreSelect.value;
                bloque.dataset.turnoId = turnoSelect.value;
                bloque.dataset.seccionId = seccionSelect.value;
                
                bloque.querySelector('.delete-btn').addEventListener('click', function(e) {
                    e.stopPropagation(); 
                    eliminarBloque(bloque);
                });
                
                cell.innerHTML = ''; 
                cell.appendChild(bloque);
                actualizarBotonGuardar();
            }
            
            // 10. Función para calcular la hora de fin (simple)
            function calcularHoraFinSimple(horaInicioStr, bloques) {
                const [h, m] = horaInicioStr.split(':').map(Number);
                const totalMinutosInicio = h * 60 + m;
                const duracionMinutos = bloques * 45; 
                let totalMinutosFin = totalMinutosInicio + duracionMinutos;

                let horasFin = Math.floor(totalMinutosFin / 60);
                let minutosFin = totalMinutosFin % 60;

                if (minutosFin > 0 && minutosFin < 45) {
                    minutosFin = 45;
                } else if (minutosFin > 45) {
                    minutosFin = 0;
                    horasFin++;
                }
                
                return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
            }
            
            // 11. Actualizar la tabla del horario (manejo de rowspan y celdas ocultas)
            function actualizarTablaHorario(dia, horaInicio, horaFin, bloques) {
                const tabla = document.getElementById('horarioTable');
                const filas = tabla.rows;
                
                const inicioIdx = getRowIndex(horaInicio);
                
                for (let i = 1; i < bloques; i++) {
                    const currentHora = calcularHoraFinSimple(horaInicio, i); 
                    const nextRowIndex = getRowIndex(currentHora);
                    
                    if (nextRowIndex !== -1) {
                        const row = filas[nextRowIndex + 1]; 
                        if (row) {
                            const cellToHide = row.cells[parseInt(dia)];
                            if (cellToHide) {
                                cellToHide.style.display = 'none';
                                cellToHide.classList.add('hidden-cell'); 
                            }
                        }
                    }
                }
                
                const initialRow = filas[inicioIdx + 1]; 
                if (initialRow) {
                    const initialCell = initialRow.cells[parseInt(dia)];
                    if (initialCell) {
                        initialCell.rowSpan = bloques;
                        const baseCellHeight = filas[inicioIdx + 1].cells[0].offsetHeight; 
                        initialCell.style.height = `${bloques * baseCellHeight}px`;
                        initialCell.classList.add('expanded-block'); 
                    }
                }
            }

            function getRowIndex(timeString) {
                const table = document.getElementById('horarioTable');
                for (let i = 1; i < table.rows.length; i++) { 
                    if (table.rows[i].cells[0].dataset.time === timeString) {
                        return i -1; 
                    }
                }
                return -1; 
            }

            // 12. Convertir número de día a texto
            function convertirDiaNumeroATexto(diaNumero) {
                const dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                return dias[parseInt(diaNumero)];
            }
            
            // 13. Función para eliminar un bloque y restaurar celdas
            function eliminarBloque(bloqueElement) {
                const dia = bloqueElement.dataset.dia;
                const horaInicio = bloqueElement.dataset.horaInicio;
                const bloques = parseInt(bloqueElement.dataset.bloques);

                const tabla = document.getElementById('horarioTable');
                const filas = tabla.rows;
                const inicioIdx = getRowIndex(horaInicio);

                const initialRow = filas[inicioIdx + 1];
                if (initialRow) {
                    const initialCell = initialRow.cells[parseInt(dia)];
                    if (initialCell) {
                        initialCell.rowSpan = 1; 
                        initialCell.style.height = ''; 
                        initialCell.classList.remove('expanded-block'); 
                    }
                }

                for (let i = 1; i < bloques; i++) {
                    const currentHora = calcularHoraFinSimple(horaInicio, i); 
                    const nextRowIndex = getRowIndex(currentHora);

                    if (nextRowIndex !== -1) {
                        const row = filas[nextRowIndex + 1];
                        if (row) {
                            const cellToShow = row.cells[parseInt(dia)];
                            if (cellToShow) {
                                cellToShow.style.display = ''; 
                                cellToShow.classList.remove('hidden-cell'); 
                            }
                        }
                    }
                }
                
                bloqueElement.remove(); 
                actualizarBotonGuardar();
            }
            
            // 14. Actualizar estado del botón guardar
            function actualizarBotonGuardar() {
                const bloques = document.querySelectorAll('.bloque-horario');
                guardarBtn.disabled = bloques.length === 0;
            }
            
            // 15. Enviar formulario
            document.getElementById('horarioForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const bloques = Array.from(document.querySelectorAll('.bloque-horario')).map(bloque => ({
                    asignatura_id: bloque.dataset.asignaturaId,
                    dia_semana: parseInt(bloque.dataset.dia),
                    hora_inicio: bloque.dataset.horaInicio,
                    hora_fin: bloque.dataset.horaFin,
                    bloques: parseInt(bloque.dataset.bloques),
                    tipo_horas: bloque.dataset.tipoHoras,
                    docente_id: bloque.dataset.docenteId,
                }));

                if (bloques.length === 0) {
                    Swal.fire('Advertencia', 'Debe agregar al menos un bloque de horario.', 'warning');
                    return;
                }

                const periodoId = periodoSelect.value;
                const turnoId = turnoSelect.value;
                const carreraId = carreraSelect.value;
                const semestreId = semestreSelect.value;
                const seccionId = seccionSelect.value;

                if (!periodoId || !turnoId || !carreraId || !semestreId || !seccionId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los campos de configuración del horario antes de guardar.', 'warning');
                    return;
                }

                const formData = {
                    periodo_id: periodoId,
                    turno_id: turnoId,
                    carrera_id: carreraId,
                    semestre_id: semestreId,
                    seccion_id: seccionId,
                    horarios: bloques 
                };

                console.log('Sending data:', formData); 

                try {
                    const response = await fetch(this.action, { 
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(formData)
                    });

                    const responseText = await response.text();
                    console.log('Raw response:', responseText);

                    if (!response.ok) {
                        let errorMessage = 'Error desconocido al guardar el horario.';
                        try {
                            const errorData = JSON.parse(responseText);
                            errorMessage = errorData.message || errorData.error || errorMessage;
                            if (errorData.errors) {
                                for (const key in errorData.errors) {
                                    errorMessage += `\n- ${errorData.errors[key].join(', ')}`;
                                }
                            }
                        } catch (parseError) {
                            errorMessage = responseText;
                        }
                        throw new Error(errorMessage);
                    }

                    const data = JSON.parse(responseText);
                    
                    if (data.success) {
                        Swal.fire('Éxito', data.message, 'success')
                            .then(() => {
                                window.location.href = data.redirect;
                            });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error al enviar el formulario (fetch):', error);
                    Swal.fire('Error', `Ocurrió un error al guardar el horario: ${error.message}. Verifique la consola para más detalles.`, 'error');
                }
            });

        });
    </script>
</body>
</html>
