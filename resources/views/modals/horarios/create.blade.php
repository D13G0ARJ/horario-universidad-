<!-- Modal para agregar un nuevo horario -->
<div class="modal fade" id="agregarHorarioModal" tabindex="-1" aria-labelledby="agregarHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xxl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-3">
                <h2 class="modal-title fs-4" id="agregarHorarioModalLabel">Agregar Nuevo Horario</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('horario.store') }}" method="POST" id="horarioForm">
                @csrf
                <input type="hidden" name="horario_data" id="horarioData">
                <div class="modal-body p-4">
                    <!-- Fila de campos del formulario -->
                    <div class="row g-3 mb-4">
                        <!-- Periodo -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="periodo" class="form-label fw-bold">Periodo</label>
                            <select id="periodo" name="periodo_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Carrera -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="carrera" class="form-label fw-bold">Carrera</label>
                            <select id="carrera" name="carrera_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}">{{ $carrera->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Turno (ahora antes de semestre) -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="turno" class="form-label fw-bold">Turno</label>
                            <select id="turno" name="turno_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id_turno }}" data-tipo="{{ $turno->tipo }}">{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Semestre (se carga dinámicamente según turno) -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="semestre" class="form-label fw-bold">Semestre</label>
                            <select id="semestre" name="semestre_id" class="form-select form-select-sm" required disabled>
                                <option value="">Seleccione turno primero</option>
                            </select>
                        </div>
                        
                        <!-- Sección (se carga dinámicamente) -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="seccion" class="form-label fw-bold">Sección</label>
                            <select id="seccion" name="seccion_id" class="form-select form-select-sm" required disabled>
                                <option value="">Complete los filtros</option>
                            </select>
                        </div>
                        
                        <!-- Botón de búsqueda -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary btn-sm w-100" id="buscarHorarios">
                                <i class="fas fa-search me-1"></i> Buscar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Contenedor del horario y asignaturas -->
                    <div class="row mt-3">
                        <!-- Lista de asignaturas -->
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Asignaturas Disponibles</h6>
                                </div>
                                <div class="card-body p-2" id="asignaturasContainer">
                                    <div class="list-group" id="listaAsignaturas">
                                        <!-- Las asignaturas se cargarán aquí dinámicamente -->
                                        <div class="text-center py-3 text-muted">
                                            Seleccione una sección primero
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Horario -->
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Horario - Lunes a Sábado</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0" id="horarioTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 10%">Hora</th>
                                                    <th style="width: 15%">Lunes</th>
                                                    <th style="width: 15%">Martes</th>
                                                    <th style="width: 15%">Miércoles</th>
                                                    <th style="width: 15%">Jueves</th>
                                                    <th style="width: 15%">Viernes</th>
                                                    <th style="width: 15%">Sábado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="horarioBody">
                                                <!-- Las filas del horario se generarán aquí dinámicamente -->
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">
                                                        Complete los filtros y haga clic en Buscar
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
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" id="guardarHorario" disabled>
                        <i class="fas fa-save me-1"></i> Guardar Horario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Estilos personalizados para la modal extra grande */
    .modal-xxl {
        max-width: 150%;
    }
    
    @media (min-width: 1900px) {
        .modal-xxl {
            max-width: 1700px;
        }
    }
    
    /* Estilos para los selects */
    #agregarHorarioModal .form-select-sm {
        padding: 0.35rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Estilo para selects deshabilitados */
    #agregarHorarioModal select[disabled] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    
    /* Estilos para el horario */
    #horarioTable th, #horarioTable td {
        text-align: center;
        vertical-align: middle;
        height: 60px;
    }
    
    #horarioTable td {
        position: relative;
    }
    
    /* Estilos para las asignaturas */
    .asignatura-item {
        cursor: move;
        margin-bottom: 5px;
        padding: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
    }
    
    .asignatura-item:hover {
        background-color: #d1e7ff;
    }
    
    /* Estilos para las celdas del horario */
    .celda-horario {
        min-height: 60px;
    }
    
    .bloque-horario {
        position: absolute;
        width: 100%;
        left: 0;
        border-radius: 4px;
        padding: 2px 5px;
        font-size: 0.8rem;
        color: white;
        cursor: pointer;
        z-index: 10;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Colores para diferentes asignaturas */
    .bg-asignatura-1 { background-color: #4e73df; }
    .bg-asignatura-2 { background-color: #1cc88a; }
    .bg-asignatura-3 { background-color: #36b9cc; }
    .bg-asignatura-4 { background-color: #f6c23e; }
    .bg-asignatura-5 { background-color: #e74a3b; }
    .bg-asignatura-6 { background-color: #858796; }
    .bg-asignatura-7 { background-color: #5a5c69; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const turnoSelect = document.getElementById('turno');
    const semestreSelect = document.getElementById('semestre');
    const carreraSelect = document.getElementById('carrera');
    const seccionSelect = document.getElementById('seccion');
    const buscarBtn = document.getElementById('buscarHorarios');
    const listaAsignaturas = document.getElementById('listaAsignaturas');
    const horarioBody = document.getElementById('horarioBody');
    const guardarBtn = document.getElementById('guardarHorario');
    
    // Cargar semestres según turno seleccionado
// En tu archivo JavaScript del modal
turnoSelect.addEventListener('change', function() {
    const turnoId = this.value;
    const turnoNombre = this.options[this.selectedIndex].text.toLowerCase();
    
    semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
    semestreSelect.disabled = !turnoId;
    
    if (!turnoId) return;
    
    // Determinar cantidad de semestres según turno
    const esNocturno = turnoNombre.includes('nocturno');
    const maxSemestres = esNocturno ? 10 : 8;
    
    // Llenar semestres
    for (let i = 1; i <= maxSemestres; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `Semestre ${i}`;
        semestreSelect.appendChild(option);
    }
});
    
    // Función para filtrar secciones
    function filtrarSecciones() {
    const carreraId = carreraSelect.value;
    const semestreId = semestreSelect.value;
    const turnoId = turnoSelect.value;
    
    seccionSelect.innerHTML = '<option value="">Cargando secciones...</option>';
    seccionSelect.disabled = true;

    // Verificar que todos los filtros estén seleccionados
    if (!carreraId || !semestreId || !turnoId) {
        seccionSelect.innerHTML = '<option value="">Complete todos los filtros</option>';
        return;
    }

    // Mostrar spinner de carga
    const loadingSpinner = document.createElement('div');
    loadingSpinner.className = 'text-center py-2';
    loadingSpinner.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando secciones...';
    seccionSelect.parentNode.appendChild(loadingSpinner);

    // Hacer petición para obtener secciones filtradas
    fetch(`/api/secciones-filtradas?carrera_id=${carreraId}&semestre_id=${semestreId}&turno_id=${turnoId}`)
        .then(response => {
            // Remover spinner
            if (loadingSpinner.parentNode) {
                loadingSpinner.parentNode.removeChild(loadingSpinner);
            }
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            seccionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
            
            if (data.length === 0) {
                seccionSelect.innerHTML = '<option value="">No hay secciones disponibles</option>';
                return;
            }
            
            data.forEach(seccion => {
                const option = document.createElement('option');
                option.value = seccion.id;
                option.textContent = seccion.text || seccion.id;
                seccionSelect.appendChild(option);
            });
            
            seccionSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error al cargar secciones:', error);
            seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
            
            // Mostrar mensaje de error detallado
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-danger small mt-1';
            errorDiv.textContent = 'Error al conectar con el servidor';
            seccionSelect.parentNode.appendChild(errorDiv);
            
            // Eliminar mensaje después de 5 segundos
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.parentNode.removeChild(errorDiv);
                }
            }, 5000);
        });
}
    
    // Event listeners para los selects
    carreraSelect.addEventListener('change', filtrarSecciones);
    semestreSelect.addEventListener('change', filtrarSecciones);
    
    // Función para cargar asignaturas de la sección seleccionada
    function cargarAsignaturas(seccionId) {
        listaAsignaturas.innerHTML = '<div class="text-center py-2"><i class="fas fa-spinner fa-spin"></i> Cargando asignaturas...</div>';
        
        fetch(`/api/asignaturas-seccion/${seccionId}`)
            .then(response => response.json())
            .then(data => {
                listaAsignaturas.innerHTML = '';
                
                if (data.length === 0) {
                    listaAsignaturas.innerHTML = '<div class="text-center py-2 text-muted">No hay asignaturas disponibles</div>';
                    return;
                }
                
                data.forEach((asignatura, index) => {
                    const colorClass = `bg-asignatura-${(index % 7) + 1}`;
                    
                    const item = document.createElement('div');
                    item.className = `list-group-item asignatura-item ${colorClass} text-white mb-2`;
                    item.textContent = asignatura.nombre;
                    item.draggable = true;
                    item.dataset.asignaturaId = asignatura.asignatura_id;
                    
                    // Eventos para drag and drop
                    item.addEventListener('dragstart', function(e) {
                        e.dataTransfer.setData('text/plain', JSON.stringify({
                            asignatura_id: asignatura.asignatura_id,
                            nombre: asignatura.nombre,
                            colorIndex: (index % 7) + 1
                        }));
                        this.classList.add('opacity-50');
                    });
                    
                    item.addEventListener('dragend', function() {
                        this.classList.remove('opacity-50');
                    });
                    
                    listaAsignaturas.appendChild(item);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                listaAsignaturas.innerHTML = '<div class="text-center py-2 text-danger">Error al cargar asignaturas</div>';
            });
    }
    
    // Función para generar el horario
    function generarHorario() {
        // Limpiar el horario existente
        horarioBody.innerHTML = '';
        
        // Generar bloques de tiempo (de 7:00 AM a 9:00 PM, bloques de 45 minutos)
        const horaInicio = 7;
        const horaFin = 21;
        
        for (let hora = horaInicio; hora < horaFin; hora++) {
            for (let minuto = 0; minuto < 60; minuto += 15) {
                if (hora === horaFin - 1 && minuto >= 45) break;
                
                const horaFormato = `${hora.toString().padStart(2, '0')}:${minuto.toString().padStart(2, '0')}`;
                
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td class="align-middle">${horaFormato}</td>
                    <td class="celda-horario" data-hora="${horaFormato}" data-dia="1"></td>
                    <td class="celda-horario" data-hora="${horaFormato}" data-dia="2"></td>
                    <td class="celda-horario" data-hora="${horaFormato}" data-dia="3"></td>
                    <td class="celda-horario" data-hora="${horaFormato}" data-dia="4"></td>
                    <td class="celda-horario" data-hora="${horaFormato}" data-dia="5"></td>
                    <td class="celda-horario" data-hora="${horaFormato}" data-dia="6"></td>
                `;
                
                horarioBody.appendChild(fila);
            }
        }
        
        // Configurar eventos de drop en las celdas del horario
        document.querySelectorAll('.celda-horario').forEach(celda => {
            celda.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('bg-light');
            });
            
            celda.addEventListener('dragleave', function() {
                this.classList.remove('bg-light');
            });
            
            celda.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('bg-light');
                
                const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                
                // Limpiar bloques existentes para esta celda
                this.innerHTML = '';
                
                // Crear bloque de horario
                const bloque = document.createElement('div');
                bloque.className = `bloque-horario bg-asignatura-${data.colorIndex}`;
                bloque.textContent = data.nombre;
                bloque.dataset.asignaturaId = data.asignaturaId;
                bloque.dataset.hora = this.dataset.hora;
                bloque.dataset.dia = this.dataset.dia;
                
                // Botón para eliminar
                const btnEliminar = document.createElement('span');
                btnEliminar.className = 'float-end cursor-pointer';
                btnEliminar.innerHTML = '&times;';
                btnEliminar.addEventListener('click', function(e) {
                    e.stopPropagation();
                    bloque.remove();
                    actualizarEstadoGuardado();
                });
                
                bloque.appendChild(btnEliminar);
                this.appendChild(bloque);
                
                // Habilitar botón de guardar
                actualizarEstadoGuardado();
            });
        });
    }
    
    // Función para actualizar estado del botón guardar
    function actualizarEstadoGuardado() {
        const bloques = document.querySelectorAll('.bloque-horario');
        guardarBtn.disabled = bloques.length === 0;
    }
    
    // Evento para el botón de búsqueda
    buscarBtn.addEventListener('click', function() {
        if (!seccionSelect.value) {
            alert('Por favor seleccione una sección primero');
            return;
        }
        
        generarHorario();
        cargarAsignaturas(seccionSelect.value);
    });
    
    // Evento para enviar el formulario
    document.getElementById('horarioForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Recopilar datos del horario
        const horario = [];
        document.querySelectorAll('.bloque-horario').forEach(bloque => {
            horario.push({
                asignatura_id: bloque.dataset.asignaturaId,
                dia: bloque.dataset.dia,
                hora: bloque.dataset.hora,
                seccion_id: seccionSelect.value
            });
        });
        
        // Asignar datos al campo oculto
        document.getElementById('horarioData').value = JSON.stringify(horario);
        
        // Enviar formulario
        this.submit();
    });
    
    // Inicializar el modal
    const horarioModal = document.getElementById('agregarHorarioModal');
    if (horarioModal) {
        horarioModal.addEventListener('shown.bs.modal', function() {
            // Resetear formulario
            this.querySelector('form').reset();
            semestreSelect.innerHTML = '<option value="">Seleccione turno primero</option>';
            semestreSelect.disabled = true;
            seccionSelect.innerHTML = '<option value="">Complete los filtros</option>';
            seccionSelect.disabled = true;
            listaAsignaturas.innerHTML = '<div class="text-center py-3 text-muted">Seleccione una sección primero</div>';
            horarioBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">Complete los filtros y haga clic en Buscar</td></tr>';
            guardarBtn.disabled = true;
        });
    }
});
</script>