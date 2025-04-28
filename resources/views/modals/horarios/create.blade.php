<!-- Modal para agregar un nuevo horario -->
<div class="modal fade" id="agregarHorarioModal" tabindex="-1" aria-labelledby="agregarHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-3">
                <h2 class="modal-title fs-3" id="agregarHorarioModalLabel">Gestión de Horarios</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('horario.store') }}" method="POST" id="horarioForm">
                @csrf
                <input type="hidden" name="horario_data" id="horarioData">
                <div class="modal-body p-4">
                    <!-- Fila superior de filtros -->
                    <div class="row g-3 mb-4 bg-light p-3 rounded">
                        <!-- Periodo -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="periodo" class="form-label fw-bold">Periodo</label>
                            <select id="periodo" name="periodo_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Carrera -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="carrera" class="form-label fw-bold">Carrera</label>
                            <select id="carrera" name="carrera_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}">{{ $carrera->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Turno -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="turno" class="form-label fw-bold">Turno</label>
                            <select id="turno" name="turno_id" class="form-select form-select-lg" required>
                                <option value="">Seleccione...</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Semestre -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="semestre" class="form-label fw-bold">Semestre</label>
                            <select id="semestre" name="semestre_id" class="form-select form-select-lg" required disabled>
                                <option value="">Seleccione turno</option>
                            </select>
                        </div>
                        
                        <!-- Sección -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="seccion" class="form-label fw-bold">Sección</label>
                            <select id="seccion" name="seccion_id" class="form-select form-select-lg" required disabled>
                                <option value="">Complete filtros</option>
                            </select>
                        </div>
                        
                        <!-- Botón de búsqueda -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary btn-lg w-100 py-2" id="buscarHorarios">
                                <i class="fas fa-search me-2"></i> Buscar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Contenedor principal -->
                    <div class="row g-3" style="height: calc(100vh - 250px);">
                        <!-- Panel de asignaturas -->
                        <div class="col-md-3 h-100">
                            <div class="card h-100 border-primary">
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
                        
                        <!-- Panel del horario -->
                        <div class="col-md-9 h-100">
                            <div class="card h-100 border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Horario - Lunes a Sábado</h5>
                                </div>
                                <div class="card-body p-0 h-100">
                                    <div class="table-responsive h-100">
                                        <table class="table table-bordered table-hover mb-0 h-100">
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
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg px-4" id="guardarHorario" disabled>
                        <i class="fas fa-save me-2"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    // 1. Cargar semestres según turno seleccionado
    turnoSelect.addEventListener('change', function() {
        const turnoId = this.value;
        const turnoNombre = this.options[this.selectedIndex].text.toLowerCase();
        
        semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
        semestreSelect.disabled = !turnoId;
        
        if (turnoId) {
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
        }
    });
    
    // 2. Función para cargar secciones según los filtros
    async function cargarSecciones() {
        const carreraId = carreraSelect.value;
        const semestreId = semestreSelect.value;
        const turnoId = turnoSelect.value;
        
        seccionSelect.innerHTML = '<option value="">Cargando...</option>';
        seccionSelect.disabled = true;

        if (!carreraId || !semestreId || !turnoId) {
            seccionSelect.innerHTML = '<option value="">Complete los filtros</option>';
            return;
        }

        try {
            const response = await fetch(`/obtener-secciones?carrera_id=${carreraId}&semestre_id=${semestreId}&turno_id=${turnoId}`);
            
            if (!response.ok) {
                throw new Error('Error al obtener secciones');
            }
            
            const data = await response.json();
            
            seccionSelect.innerHTML = data.length > 0 
                ? '<option value="">Seleccione sección</option>' + 
                  data.map(s => `<option value="${s.id}">${s.text}</option>`).join('')
                : '<option value="">No hay secciones</option>';
                
            seccionSelect.disabled = false;
        } catch (error) {
            console.error('Error:', error);
            seccionSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // 3. Event listeners para los filtros
    ['carrera', 'semestre'].forEach(filter => {
        document.getElementById(filter).addEventListener('change', cargarSecciones);
    });

    // 4. Función principal al hacer clic en Buscar
    buscarBtn.addEventListener('click', async function() {
        const seccionId = seccionSelect.value;
        
        if (!seccionId) {
            alert('Por favor seleccione una sección primero');
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
        
        // Mostrar carga en horario
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
            // Obtener asignaturas de la sección
            const response = await fetch(`/obtener-asignaturas/${seccionId}`);
            
            if (!response.ok) {
                throw new Error('Error al obtener asignaturas');
            }
            
            const asignaturas = await response.json();
            
            // Mostrar asignaturas en el panel izquierdo
            if (asignaturas.length > 0) {
                listaAsignaturas.innerHTML = asignaturas.map((asignatura, index) => {
                    const colorClass = `bg-asignatura-${(index % 7) + 1}`;
                    return `
                        <div class="list-group-item asignatura-item ${colorClass} text-white mb-2" 
                             draggable="true" 
                             data-asignatura-id="${asignatura.asignatura_id}"
                             data-asignatura-nombre="${asignatura.nombre}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>${asignatura.nombre}</span>
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
                        No hay asignaturas asignadas a esta sección
                    </div>
                `;
            }
            
            // Generar la estructura del horario
            generarHorario();
            
        } catch (error) {
            console.error('Error:', error);
            listaAsignaturas.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar las asignaturas
                </div>
            `;
        }
    });
    
    // 5. Configurar eventos de drag and drop para las asignaturas
    function configurarDragAndDrop() {
        document.querySelectorAll('.asignatura-item').forEach(item => {
            item.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', JSON.stringify({
                    asignatura_id: this.dataset.asignaturaId,
                    nombre: this.dataset.asignaturaNombre,
                    colorIndex: Array.from(this.classList)
                        .find(cls => cls.startsWith('bg-asignatura-'))
                        .split('-')[2]
                }));
                this.classList.add('dragging');
            });
            
            item.addEventListener('dragend', function() {
                this.classList.remove('dragging');
            });
        });
    }
    
    // 6. Generar la estructura del horario
    function generarHorario() {
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
                this.classList.add('hover-cell');
            });
            
            celda.addEventListener('dragleave', function() {
                this.classList.remove('hover-cell');
            });
            
            celda.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('hover-cell');
                
                const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                
                // Limpiar bloques existentes para esta celda
                this.innerHTML = '';
                
                // Crear bloque de horario
                const bloque = document.createElement('div');
                bloque.className = `bloque-horario bg-asignatura-${data.colorIndex}`;
                bloque.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center h-100">
                        <span>${data.nombre}</span>
                        <button class="btn btn-sm btn-light p-0" onclick="this.parentElement.parentElement.remove(); actualizarBotonGuardar();">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                bloque.dataset.asignaturaId = data.asignaturaId;
                bloque.dataset.hora = this.dataset.hora;
                bloque.dataset.dia = this.dataset.dia;
                
                this.appendChild(bloque);
                actualizarBotonGuardar();
            });
        });
    }
    
    // 7. Actualizar estado del botón guardar
    function actualizarBotonGuardar() {
        const bloques = document.querySelectorAll('.bloque-horario');
        guardarBtn.disabled = bloques.length === 0;
    }
    
    // 8. Enviar formulario
    document.getElementById('horarioForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const bloques = Array.from(document.querySelectorAll('.bloque-horario')).map(bloque => ({
            asignatura_id: bloque.dataset.asignaturaId,
            dia: bloque.dataset.dia,
            hora: bloque.dataset.hora
        }));
        
        document.getElementById('horarioData').value = JSON.stringify(bloques);
        this.submit();
    });
    
    // Función global para actualizar botón guardar
    window.actualizarBotonGuardar = actualizarBotonGuardar;
});
</script>

<style>
    /* Estilos para elementos en arrastre */
    .asignatura-item.dragging {
        opacity: 0.5;
        transform: scale(0.98);
    }
    
    /* Estilo para celdas con hover */
    .celda-horario.hover-cell {
        background-color: rgba(0, 123, 255, 0.1) !important;
        border: 2px dashed #007bff !important;
    }
    
    /* Ajustes para bloques de horario */
    .bloque-horario {
        position: absolute;
        top: 2px;
        left: 2px;
        right: 2px;
        bottom: 2px;
        border-radius: 4px;
        padding: 5px;
        font-size: 0.85rem;
        overflow: hidden;
        cursor: move;
    }
    
    /* Botón de eliminar en bloques */
    .bloque-horario button {
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .bloque-horario:hover button {
        opacity: 1;
    }
</style>