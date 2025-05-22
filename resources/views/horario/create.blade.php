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
        /* Eliminar scrollbars globalmente */
        ::-webkit-scrollbar {
            display: none;
        }

        html {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
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

        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .content-wrapper {
            flex: 1;
            overflow: hidden;
            padding-bottom: 60px;
        }

        .main-sidebar {
            height: 100vh;
            overflow-y: hidden;
            position: fixed;
        }

        .content {
            margin-top: 60px;
            height: calc(100vh - 120px);
            overflow: hidden;
        }

        .asignatura-item.dragging {
            opacity: 0.5;
            transform: scale(0.98);
        }
        
        .drop-zone.hover-cell { 
            background-color: rgba(0, 123, 255, 0.1) !important;
            border: 2px dashed #007bff !important;
        }
        
        /* MODIFICADO: Bloques de horario con altura reducida */
        .bloque-horario {
            position: relative;
            /* La altura se calculará dinámicamente, pero ajustamos padding y fuente */
            padding: 3px 5px; /* Reducir padding interno */
            font-size: 0.7rem; /* Reducir tamaño de fuente */
            color: white;
            cursor: move;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            transition: all 0.2s;
            margin: 1px 0; /* Margen pequeño */
            display: flex; 
            flex-direction: column;
            justify-content: space-between;
            height: 100%; /* El div debe llenar la celda TD que lo contiene */
            width: 100%;
        }
        
        .bloque-horario .btn {
            opacity: 1; 
            transition: none; 
        }
        
        .bg-asignatura-1 { background: linear-gradient(135deg, #4e73df, #3a56c8); }
        .bg-asignatura-2 { background: linear-gradient(135deg, #1cc88a, #17a673); }
        .bg-asignatura-3 { background: linear-gradient(135deg, #36b9cc, #2a96a5); }
        .bg-asignatura-4 { background: linear-gradient(135deg, #f6c23e, #e0b12d); }
        .bg-asignatura-5 { background: linear-gradient(135deg, #e74a3b, #d62c1a); }
        .bg-asignatura-6 { background: linear-gradient(135deg, #858796, #6c6e7e); }
        .bg-asignatura-7 { background: linear-gradient(135deg, #5a5c69, #484a58); }
        
        .card-body::-webkit-scrollbar { width: 8px; }
        .card-body::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .card-body::-webkit-scrollbar-thumb { background: #b0b0b0; border-radius: 10px; }
        .card-body::-webkit-scrollbar-thumb:hover { background: #888; }
        
        /* MODIFICADO: Ajustes de tabla para scroll y altura de celda reducida */
        #horarioTable thead th, #horarioTable tbody td, #horarioTable tbody th {
            vertical-align: top; 
            padding: 4px; /* Padding reducido */
            height: 40px; /* Altura base de la celda reducida */
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

        #asignaturasContainer {
            flex-grow: 1; 
            overflow-y: auto;
        }
        .card-body.p-0 > .table-responsive {
            flex-grow: 1; 
            overflow-y: auto;
        }

        .row.g-3 { height: auto; }

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

        /* Estilo para el contenido del bloque de asignatura */
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
            font-size: 0.75rem; /* Ajustar si es necesario */
        }
        .bloque-contenido .asignatura-details {
            font-size: 0.65rem; /* Detalles más pequeños */
            line-height: 1.2;
        }
        .bloque-contenido .delete-btn {
             width: 18px !important; /* Botón más pequeño */
             height: 18px !important; /* Botón más pequeño */
             font-size: 0.6rem !important; /* Icono más pequeño */
        }
    </style> 
</head>

<body>
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
                <input type="hidden" name="horario_data" id="horarioData">
                
                <div class="card-body p-4">
                    <div class="row g-3 mb-4 bg-light p-3 rounded">
                        {{-- Filtros: Periodo, Carrera, Turno, Semestre, Sección --}}
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
                                            <i class="fas fa-info-circle me-2"></i>Seleccione filtros y haga clic en Buscar
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            const BASE_CELL_HEIGHT = 40; // MODIFICADO: Altura base de la celda en px, debe coincidir con CSS
            
            const periodoSelect = document.getElementById('periodo_id');
            const turnoSelect = document.getElementById('turno_id');
            const semestreSelect = document.getElementById('semestre_id');
            const carreraSelect = document.getElementById('carrera_id');
            const seccionSelect = document.getElementById('seccion_id');
            const buscarBtn = document.getElementById('buscarHorarios');
            const listaAsignaturas = document.getElementById('listaAsignaturas');
            const horarioBody = document.getElementById('horarioBody');
            const guardarBtn = document.getElementById('guardarHorario');

            // NUEVO: Objeto para rastrear horas asignadas por asignatura y tipo
            let assignedHoursPerSubject = {}; // Ej: { "asignaturaId_Teórica": 2, "asignaturaId_Práctica": 1 }

            // 1. Cargar semestres según turno seleccionado
            turnoSelect.addEventListener('change', function() {
                const turnoId = this.value;
                semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                semestreSelect.disabled = true;
                seccionSelect.innerHTML = '<option value="">Complete filtros</option>';
                seccionSelect.disabled = true;

                if (!turnoId) return;
                // ... (resto de la función sin cambios)
                fetch(`{{ url('/horario/api/semestres-por-turno/') }}/${turnoId}`)
                    .then(response => response.json())
                    .then(data => {
                        semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                        data.forEach(semestre => {
                            const option = new Option(`${semestre.numero}º Semestre`, semestre.id_semestre);
                            semestreSelect.add(option);
                        });
                        semestreSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error al cargar semestres:', error);
                        semestreSelect.innerHTML = '<option value="">Error al cargar</option>';
                    });
            });
            
            // 2. Función para cargar secciones
            async function cargarSecciones() {
                // ... (función sin cambios significativos, solo asegurar que se llama correctamente)
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
                    const url = new URL(`{{ url('/horario/obtener-secciones') }}`);
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);
                    const response = await fetch(url);
                    if (!response.ok) throw new Error(`Error al obtener secciones: ${response.status}`);
                    const data = await response.json();
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
                    console.error('Error:', error);
                    seccionSelect.innerHTML = '<option value="">Error al cargar</option>';
                }
            }

            carreraSelect.addEventListener('change', cargarSecciones);
            semestreSelect.addEventListener('change', cargarSecciones);

            // 4. Función principal al hacer clic en Buscar
            buscarBtn.addEventListener('click', async function() {
                // ... (validación de filtros)
                const periodoId = periodoSelect.value;
                const carreraId = carreraSelect.value;
                const turnoId = turnoSelect.value;
                const semestreId = semestreSelect.value;
                const seccionId = seccionSelect.value;
                
                if (!periodoId || !carreraId || !turnoId || !semestreId || !seccionId) {
                    Swal.fire('Advertencia', 'Por favor, complete todos los filtros.', 'warning');
                    return;
                }

                // NUEVO: Resetear contador de horas asignadas
                assignedHoursPerSubject = {};
                
                // ... (código para mostrar carga y fetch de asignaturas)
                listaAsignaturas.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
                horarioBody.innerHTML = `<tr><td colspan="7" class="text-center py-5"><div class="spinner-border text-primary"></div><p>Preparando...</p></td></tr>`;

                try {
                    const url = new URL(`{{ url('/horario/asignaturas') }}`);
                    url.searchParams.append('seccion_id', seccionId);
                    url.searchParams.append('carrera_id', carreraId);
                    url.searchParams.append('semestre_id', semestreId);
                    url.searchParams.append('turno_id', turnoId);
                    url.searchParams.append('periodo_id', periodoId);

                    const response = await fetch(url);
                    if (!response.ok) throw new Error(`Error al obtener asignaturas: ${response.status}`);
                    const asignaturas = await response.json();
                    
                    if (asignaturas.length > 0) {
                        listaAsignaturas.innerHTML = asignaturas.map((asignatura, index) => {
                            const colorClass = `bg-asignatura-${(index % 7) + 1}`;
                            // MODIFICADO: Mostrar carga horaria más clara
                            let cargaHorariaText = 'No definida';
                            if (asignatura.carga_horaria && asignatura.carga_horaria.length > 0) {
                                cargaHorariaText = asignatura.carga_horaria.map(c => `${c.tipo.substring(0,1)}:${c.horas_academicas}b`).join(', '); // Ej: T:4b, P:2b
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
                    } else {
                        listaAsignaturas.innerHTML = `<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle me-2"></i>No hay asignaturas.</div>`;
                    }
                    generarHorario();
                } catch (error) {
                    // ... (manejo de error)
                    console.error('Error en Buscar Asignaturas:', error);
                    Swal.fire('Error', `Ocurrió un error al cargar: ${error.message}`, 'error');
                    listaAsignaturas.innerHTML = `<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error al cargar.</div>`;
                    horarioBody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted">Error.</td></tr>`;
                }
            });
            
            // 5. Configurar drag and drop
            function configurarDragAndDrop() {
                // ... (sin cambios significativos)
                 document.querySelectorAll('.asignatura-item').forEach(item => {
                    item.addEventListener('dragstart', function(e) {
                        e.dataTransfer.setData('text/plain', JSON.stringify({
                            asignatura_id: this.dataset.asignaturaId,
                            name: this.dataset.asignaturaName,
                            docentes: JSON.parse(this.dataset.docentes),
                            cargaHoraria: JSON.parse(this.dataset.cargaHoraria), // Array de objetos {tipo, horas_academicas}
                            colorClass: Array.from(this.classList).find(cls => cls.startsWith('bg-asignatura-'))
                        }));
                        this.classList.add('dragging');
                    });
                    item.addEventListener('dragend', function() { this.classList.remove('dragging'); });
                });
            }
            
            // 6. Generar estructura del horario
            function generarHorario() {
                // ... (sin cambios significativos en la lógica de generación de filas y celdas base)
                horarioBody.innerHTML = '';
                const horaInicio = 7;
                const horaFin = 21; 

                for (let h = horaInicio; h <= horaFin; h++) {
                    let horaFormato = `${h.toString().padStart(2, '0')}:00`;
                    let fila = document.createElement('tr');
                    fila.innerHTML = `
                        <th class="time-slot" data-time="${horaFormato}">${horaFormato}</th>
                        ${[1,2,3,4,5,6].map(dia => `<td class="drop-zone" data-hora="${horaFormato}" data-dia="${dia}"></td>`).join('')}
                    `;
                    horarioBody.appendChild(fila);

                    if (h < horaFin) {
                        horaFormato = `${h.toString().padStart(2, '0')}:45`;
                        fila = document.createElement('tr');
                        fila.innerHTML = `
                            <th class="time-slot" data-time="${horaFormato}">${horaFormato}</th>
                             ${[1,2,3,4,5,6].map(dia => `<td class="drop-zone" data-hora="${horaFormato}" data-dia="${dia}"></td>`).join('')}
                        `;
                        horarioBody.appendChild(fila);
                    }
                }
                configurarCeldasHorario();
            }
            
            // 7. Configurar celdas del horario
            function configurarCeldasHorario() {
                // ... (sin cambios)
                document.querySelectorAll('.drop-zone').forEach(celda => {
                    celda.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('hover-cell'); });
                    celda.addEventListener('dragleave', function() { this.classList.remove('hover-cell'); });
                    celda.addEventListener('drop', handleDrop);
                });
            }
            
            // 8. MODIFICADO: Manejar drop con validación de horas
            async function handleDrop(e) {
                e.preventDefault();
                this.classList.remove('hover-cell');
                
                const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                const { asignatura_id, name: asignaturaName, docentes: docentesData, cargaHoraria: cargaHorariaData, colorClass } = data;

                const dia = this.dataset.dia;
                const horaInicio = this.dataset.hora;
                
                if (this.querySelector('.bloque-horario')) {
                    Swal.fire('Advertencia', 'Ya existe un bloque en esta celda.', 'warning');
                    return;
                }

                // NUEVO: Preparar opciones de tipo de hora y validar disponibilidad general
                let tipoOptionsHtml = '';
                let tieneHorasDisponibles = false;
                if (cargaHorariaData && cargaHorariaData.length > 0) {
                    cargaHorariaData.forEach(carga => {
                        const subjectTypeKey = `${asignatura_id}_${carga.tipo}`;
                        const horasYaAsignadas = assignedHoursPerSubject[subjectTypeKey] || 0;
                        // Asumimos que horas_academicas es el número de bloques de 45min
                        const horasMaximasParaTipo = parseInt(carga.horas_academicas); 
                        const horasRestantesParaTipo = horasMaximasParaTipo - horasYaAsignadas;

                        if (horasRestantesParaTipo > 0) {
                            tipoOptionsHtml += `<option value="${carga.tipo}">${carga.tipo} (${horasRestantesParaTipo} bloques rest.)</option>`;
                            tieneHorasDisponibles = true;
                        } else {
                            tipoOptionsHtml += `<option value="${carga.tipo}" disabled>${carga.tipo} (Límite alcanzado)</option>`;
                        }
                    });
                } else { // Si no hay carga horaria definida, se asume un tipo genérico sin límite (o podrías prohibirlo)
                    tipoOptionsHtml = `<option value="Clase">Clase (carga no definida)</option>`;
                    tieneHorasDisponibles = true; // Permite agregar si no hay carga definida
                }

                if (!tieneHorasDisponibles && cargaHorariaData && cargaHorariaData.length > 0) {
                    Swal.fire('Límite Alcanzado', `No hay horas disponibles para la asignatura '${asignaturaName}'. Ya ha asignado todos los bloques permitidos.`, 'info');
                    return;
                }
                
                let docenteOptionsHtml = docentesData && docentesData.length > 0 
                    ? docentesData.map(d => `<option value="${d.cedula_doc}">${d.name}</option>`).join('')
                    : '<option value="">Sin docentes</option>';

                const { value: formValues } = await Swal.fire({
                    title: 'Configurar Bloque',
                    html: `
                        <p class="text-start mb-1">Asignatura: <strong>${asignaturaName}</strong></p>
                        <p class="text-start mb-1">Día: <strong>${convertirDiaNumeroATexto(dia)}</strong>, Hora inicio: <strong>${horaInicio}</strong></p>
                        <hr class="my-2">
                        <div class="mb-2 text-start">
                            <label for="swal-tipo" class="form-label">Tipo de Horas:</label>
                            <select id="swal-tipo" class="swal2-input form-control form-control-sm">${tipoOptionsHtml}</select>
                        </div>
                        <div class="mb-2 text-start">
                            <label for="swal-bloques" class="form-label">Bloques (45 min c/u):</label>
                            <input type="number" id="swal-bloques" class="swal2-input form-control form-control-sm" value="1" min="1" max="8">
                        </div>
                        <div class="mb-2 text-start">
                            <label for="swal-docente" class="form-label">Docente:</label>
                            <select id="swal-docente" class="swal2-input form-control form-control-sm">${docenteOptionsHtml}</select>
                        </div>`,
                    focusConfirm: false,
                    preConfirm: () => {
                        const tipo = Swal.getPopup().querySelector('#swal-tipo').value;
                        const bloques = parseInt(Swal.getPopup().querySelector('#swal-bloques').value);
                        const docenteSeleccionado = Swal.getPopup().querySelector('#swal-docente').value;

                        if (!tipo) { Swal.showValidationMessage('Seleccione un tipo de horas.'); return false; }
                        if (isNaN(bloques) || bloques < 1) { Swal.showValidationMessage('Número de bloques inválido.'); return false; }
                        if (!docenteSeleccionado && docentesData && docentesData.length > 0) { Swal.showValidationMessage('Seleccione un docente.'); return false; }

                        // NUEVO: Validación final de horas antes de confirmar
                        if (cargaHorariaData && cargaHorariaData.length > 0) {
                            const cargaEspecifica = cargaHorariaData.find(c => c.tipo === tipo);
                            if (cargaEspecifica) {
                                const subjectTypeKey = `${asignatura_id}_${tipo}`;
                                const horasYaAsignadas = assignedHoursPerSubject[subjectTypeKey] || 0;
                                const horasMaximasParaTipo = parseInt(cargaEspecifica.horas_academicas);

                                if ((horasYaAsignadas + bloques) > horasMaximasParaTipo) {
                                    Swal.showValidationMessage(`Excede el límite. Solo puede agregar ${horasMaximasParaTipo - horasYaAsignadas} bloques de tipo '${tipo}'.`);
                                    return false;
                                }
                            }
                        }
                        return { tipo, bloques, docenteSeleccionado };
                    }
                });

                if (formValues) {
                    const { tipo, bloques, docenteSeleccionado } = formValues;
                    const horaFin = calcularHoraFinSimple(horaInicio, bloques);
                    
                    // NUEVO: Actualizar contador de horas asignadas
                    const subjectTypeKey = `${asignatura_id}_${tipo}`;
                    assignedHoursPerSubject[subjectTypeKey] = (assignedHoursPerSubject[subjectTypeKey] || 0) + bloques;

                    crearBloqueVisual(this, asignatura_id, asignaturaName, dia, horaInicio, horaFin, bloques, tipo, docenteSeleccionado, colorClass, docentesData);
                    actualizarTablaHorario(dia, horaInicio, bloques); // Solo necesita horaInicio y bloques para rowspan
                }
            }
            
            // 9. MODIFICADO: Crear el bloque visual en la tabla
            function crearBloqueVisual(cell, asignaturaId, asignaturaName, dia, horaInicio, horaFin, bloques, tipoHoras, docenteId, colorClass, docentesData) {
                const bloque = document.createElement('div');
                bloque.classList.add('bloque-horario', colorClass); // Clases base
                
                // El CSS se encarga de height: 100% del TD padre.
                // El TD padre tendrá su altura ajustada por rowspan y BASE_CELL_HEIGHT.

                const docenteObj = docentesData.find(d => d.cedula_doc == docenteId);
                const docenteName = docenteObj ? docenteObj.name.split(' ')[0] : 'N/A'; // Solo primer nombre

                bloque.innerHTML = `
                    <div class="bloque-contenido">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="asignatura-nombre" title="${asignaturaName}">${asignaturaName}</span>
                            <button class="btn btn-sm btn-light p-0 delete-btn" title="Eliminar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="asignatura-details mt-auto">
                            <div title="${tipoHoras} - ${bloques} bloques">${tipoHoras.substring(0,4)}. (${bloques}b)</div>
                            <div title="Docente: ${docenteObj ? docenteObj.name : 'N/A'}">Doc: ${docenteName}</div>
                            <div>${horaInicio} - ${horaFin}</div>
                        </div>
                    </div>
                `;
                
                // Guardar todos los datos necesarios en el elemento
                Object.assign(bloque.dataset, {
                    asignaturaId, asignaturaName, dia, horaInicio, horaFin, bloques, tipoHoras, docenteId,
                    periodoId: periodoSelect.value, carreraId: carreraSelect.value,
                    semestreId: semestreSelect.value, turnoId: turnoSelect.value, seccionId: seccionSelect.value
                });
                
                bloque.querySelector('.delete-btn').addEventListener('click', function(e) {
                    e.stopPropagation(); 
                    eliminarBloque(bloque);
                });
                
                cell.innerHTML = ''; 
                cell.appendChild(bloque);
                actualizarBotonGuardar();
            }
            
            // 10. Calcular hora de fin
            function calcularHoraFinSimple(horaInicioStr, bloques) {
                // ... (sin cambios)
                const [h, m] = horaInicioStr.split(':').map(Number);
                let totalMinutosFin = (h * 60 + m) + (bloques * 45);
                let horasFin = Math.floor(totalMinutosFin / 60) % 24; // Asegurar que no pase de 23
                let minutosFin = totalMinutosFin % 60;
                return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
            }
            
            // 11. MODIFICADO: Actualizar tabla (rowspan y celdas ocultas)
            function actualizarTablaHorario(dia, horaInicio, bloques) {
                const tabla = document.getElementById('horarioTable');
                const filas = Array.from(tabla.rows); // Convertir HTMLCollection a Array para .findIndex
                
                const inicioIdx = filas.findIndex(row => row.cells[0] && row.cells[0].dataset.time === horaInicio);

                if (inicioIdx === -1 || !filas[inicioIdx]) return; // Fila de inicio no encontrada

                const celdaInicial = filas[inicioIdx].cells[parseInt(dia)];
                if (!celdaInicial) return;

                celdaInicial.rowSpan = bloques;
                // La altura de la celda se define por el número de bloques * BASE_CELL_HEIGHT
                // Esto se aplica a la celda TD, y el div.bloque-horario interno toma height: 100%
                celdaInicial.style.height = `${bloques * BASE_CELL_HEIGHT}px`; 
                celdaInicial.classList.add('expanded-block');
                
                // Ocultar celdas subsiguientes que son cubiertas por el rowspan
                for (let i = 1; i < bloques; i++) {
                    const siguienteFilaIdx = inicioIdx + i;
                    if (siguienteFilaIdx < filas.length && filas[siguienteFilaIdx]) {
                        const celdaAOcultar = filas[siguienteFilaIdx].cells[parseInt(dia)];
                        if (celdaAOcultar) {
                            celdaAOcultar.style.display = 'none';
                            celdaAOcultar.classList.add('hidden-cell'); 
                        }
                    }
                }
            }

            // 12. Convertir número de día a texto
            function convertirDiaNumeroATexto(diaNumero) { /* ... (sin cambios) ... */ return ['','Lun','Mar','Mié','Jue','Vie','Sáb'][parseInt(diaNumero)]; }
            
            // 13. MODIFICADO: Eliminar bloque y restaurar celdas
            function eliminarBloque(bloqueElement) {
                const { asignaturaId, tipoHoras, dia, horaInicio, bloques: numBloquesStr } = bloqueElement.dataset;
                const numBloques = parseInt(numBloquesStr);

                // NUEVO: Actualizar contador de horas asignadas
                const subjectTypeKey = `${asignaturaId}_${tipoHoras}`;
                if (assignedHoursPerSubject[subjectTypeKey]) {
                    assignedHoursPerSubject[subjectTypeKey] -= numBloques;
                    if (assignedHoursPerSubject[subjectTypeKey] <= 0) {
                        delete assignedHoursPerSubject[subjectTypeKey];
                    }
                }

                const tabla = document.getElementById('horarioTable');
                const filas = Array.from(tabla.rows);
                const inicioIdx = filas.findIndex(row => row.cells[0] && row.cells[0].dataset.time === horaInicio);

                if (inicioIdx === -1 || !filas[inicioIdx]) return;

                const celdaInicial = filas[inicioIdx].cells[parseInt(dia)];
                if (celdaInicial) {
                    celdaInicial.rowSpan = 1;
                    celdaInicial.style.height = `${BASE_CELL_HEIGHT}px`; // Restaurar altura base
                    celdaInicial.classList.remove('expanded-block');
                    // celdaInicial.innerHTML = ''; // Limpiar la celda si es necesario, pero el remove() de abajo lo hace
                }

                for (let i = 1; i < numBloques; i++) {
                    const siguienteFilaIdx = inicioIdx + i;
                    if (siguienteFilaIdx < filas.length && filas[siguienteFilaIdx]) {
                        const celdaAMostrar = filas[siguienteFilaIdx].cells[parseInt(dia)];
                        if (celdaAMostrar) {
                            celdaAMostrar.style.display = ''; 
                            celdaAMostrar.classList.remove('hidden-cell');
                        }
                    }
                }
                
                bloqueElement.parentElement.innerHTML = ''; // Limpia la celda que contenía el bloque
                // bloqueElement.remove(); // Esto elimina solo el div, pero queremos limpiar la celda
                actualizarBotonGuardar();
            }
            
            // 14. Actualizar botón guardar
            function actualizarBotonGuardar() { /* ... (sin cambios) ... */ guardarBtn.disabled = document.querySelectorAll('.bloque-horario').length === 0; }
            
            // 15. Enviar formulario
            document.getElementById('horarioForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                // ... (recolección de datos de bloques sin cambios significativos)
                const bloques = Array.from(document.querySelectorAll('.bloque-horario')).map(b => ({
                    asignatura_id: b.dataset.asignaturaId,
                    dia_semana: parseInt(b.dataset.dia),
                    hora_inicio: b.dataset.horaInicio,
                    hora_fin: b.dataset.horaFin,
                    bloques: parseInt(b.dataset.bloques),
                    tipo_horas: b.dataset.tipoHoras,
                    docente_id: b.dataset.docenteId,
                }));

                if (bloques.length === 0) { /* ... */ return; }
                // ... (validación de filtros principales)

                const formData = {
                    periodo_id: periodoSelect.value, turno_id: turnoSelect.value, carrera_id: carreraSelect.value,
                    semestre_id: semestreSelect.value, seccion_id: seccionSelect.value, horarios: bloques 
                };

                try {
                    const response = await fetch(this.action, { 
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify(formData)
                    });
                    // ... (manejo de respuesta sin cambios)
                    const responseText = await response.text();
                    if (!response.ok) {
                        let errorMessage = 'Error al guardar.';
                        try { const errorData = JSON.parse(responseText); errorMessage = errorData.message || errorData.error || errorMessage; } 
                        catch (parseError) { errorMessage = responseText.substring(0, 200); } // Evitar mensajes muy largos
                        throw new Error(errorMessage);
                    }
                    const data = JSON.parse(responseText);
                    if (data.success) {
                        Swal.fire('Éxito', data.message, 'success').then(() => { window.location.href = data.redirect; });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo guardar.', 'error');
                    }
                } catch (error) {
                    console.error('Error al enviar:', error);
                    Swal.fire('Error', `Ocurrió un error: ${error.message}`, 'error');
                }
            });
        });
    </script>
</body>
</html>
```

