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
                                        <table class="table table-bordered table-hover mb-0 h-100" id="horarioTable">
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
    // Variables globales
    let selectedBlock = null;
    let initialCell = null;
    let isExpanding = false;
    const blockDuration = 45; // Duración en minutos de cada bloque
    
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
            semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
            semestreSelect.disabled = true;
            
            if (!turnoId) return;

            semestreSelect.disabled = true;
            const loadingOption = new Option('Cargando semestres...', '');
            loadingOption.disabled = true;
            semestreSelect.add(loadingOption);

            fetch(`/api/semestres-por-turno/${turnoId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error al cargar semestres');
                    return response.json();
                })
                .then(data => {
                    semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(semestre => {
                        const option = new Option(`Semestre ${semestre.numero}`, semestre.id);
                        semestreSelect.add(option);
                    });
                    semestreSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
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
            seccionSelect.innerHTML = '<option value="">Complete los filtros</option>';
            return;
        }

        try {
            const response = await fetch(`/obtener-secciones?carrera_id=${carreraId}&semestre_id=${semestreId}&turno_id=${turnoId}`);
            
            if (!response.ok) throw new Error('Error al obtener secciones');
            
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
            
            if (!response.ok) throw new Error('Error al obtener asignaturas');
            
            const asignaturas = await response.json();
            
            // Mostrar asignaturas en el panel izquierdo
            if (asignaturas.length > 0) {
                listaAsignaturas.innerHTML = asignaturas.map((asignatura, index) => {
                    const colorClass = `bg-asignatura-${(index % 7) + 1}`;
                    return `
                        <div class="list-group-item asignatura-item ${colorClass} text-white mb-2" 
                             draggable="true" 
                             data-asignatura-id="${asignatura.asignatura_id}"
                             data-asignatura-name="${asignatura.name}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>${asignatura.name}</span>
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
                    name: this.dataset.asignaturaName,
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
    
    // 6. Generar la estructura del horario con bloques expandibles
    function generarHorario() {
        horarioBody.innerHTML = '';
        
        // Generar bloques de tiempo (de 7:00 AM a 9:00 PM, bloques de 15 minutos)
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
        
        // Configurar eventos para las celdas del horario
        configurarCeldasHorario();
    }
    
    // 7. Configurar eventos para las celdas del horario
    function configurarCeldasHorario() {
        const celdas = document.querySelectorAll('.celda-horario');
        
        celdas.forEach(celda => {
            // Evento dragover para permitir drop
            celda.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('hover-cell');
            });
            
            // Evento dragleave para quitar el hover
            celda.addEventListener('dragleave', function() {
                this.classList.remove('hover-cell');
            });
            
            // Evento drop para colocar la asignatura
            celda.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('hover-cell');
                
                const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                
                // Limpiar celdas seleccionadas previamente
                document.querySelectorAll('.selected-cell').forEach(c => c.classList.remove('selected-cell'));
                
                // Marcar esta celda como seleccionada
                this.classList.add('selected-cell');
                
                // Crear bloque de horario
                const bloque = document.createElement('div');
                bloque.className = `bloque-horario bg-asignatura-${data.colorIndex}`;
                bloque.innerHTML = `
                    <div class="d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">${data.name}</span>
                            <div>
                                <button class="btn btn-sm btn-light p-0 expand-btn" title="Expandir">
                                    <i class="fas fa-arrows-alt-v"></i>
                                </button>
                                <button class="btn btn-sm btn-light p-0 delete-btn" title="Eliminar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-auto small">${this.dataset.hora}</div>
                    </div>
                `;
                
                // Datos del bloque
                bloque.dataset.asignaturaId = data.asignaturaId;
                bloque.dataset.horaInicio = this.dataset.hora;
                bloque.dataset.horaFin = this.dataset.hora;
                bloque.dataset.dia = this.dataset.dia;
                bloque.dataset.duracion = '1'; // Bloques de 45 minutos
                
                // Limpiar la celda antes de agregar el bloque
                this.innerHTML = '';
                this.appendChild(bloque);
                
                // Configurar eventos para los botones del bloque
                const expandBtn = bloque.querySelector('.expand-btn');
                const deleteBtn = bloque.querySelector('.delete-btn');
                
                deleteBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    eliminarBloque(bloque);
                });
                
                expandBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    iniciarExpansion(bloque);
                });
                
                actualizarBotonGuardar();
            });
        });
    }
    
    // 8. Función para iniciar la expansión del bloque
    function iniciarExpansion(bloque) {
        isExpanding = true;
        selectedBlock = bloque;
        initialCell = bloque.parentElement;
        
        // Resaltar celdas disponibles para expansión
        const dia = bloque.dataset.dia;
        const horaInicio = bloque.dataset.horaInicio;
        const filaActual = initialCell.parentElement;
        const filas = Array.from(horarioBody.querySelectorAll('tr'));
        const indiceFilaActual = filas.indexOf(filaActual);
        
        // Limpiar selecciones anteriores
        document.querySelectorAll('.expand-cell').forEach(c => c.classList.remove('expand-cell'));
        
        // Marcar celdas disponibles para expansión (hacia abajo)
        for (let i = indiceFilaActual + 1; i < filas.length; i++) {
            const celda = filas[i].querySelector(`[data-dia="${dia}"]`);
            if (celda && celda.innerHTML === '') {
                celda.classList.add('expand-cell');
                
                celda.addEventListener('click', function expandirHandler() {
                    if (isExpanding) {
                        expandirBloque(bloque, this);
                        isExpanding = false;
                        selectedBlock = null;
                        initialCell = null;
                        document.querySelectorAll('.expand-cell').forEach(c => {
                            c.classList.remove('expand-cell');
                            c.removeEventListener('click', expandirHandler);
                        });
                    }
                });
            } else {
                break;
            }
        }
    }
    
    // 9. Función para expandir el bloque
    function expandirBloque(bloque, celdaFinal) {
        const dia = bloque.dataset.dia;
        const horaInicio = bloque.dataset.horaInicio;
        const horaFin = celdaFinal.dataset.hora;
        
        // Calcular duración en bloques de 45 minutos
        const inicio = convertirHoraAMinutos(horaInicio);
        const fin = convertirHoraAMinutos(horaFin);
        const duracion = Math.round((fin - inicio + 15) / 45); // Bloques de 45 minutos
        
        // Actualizar datos del bloque
        bloque.dataset.horaFin = horaFin;
        bloque.dataset.duracion = duracion;
        
        // Actualizar visualización del bloque
        bloque.style.gridRowEnd = `span ${duracion}`;
        bloque.querySelector('.small').textContent = `${horaInicio} - ${horaFin}`;
        
        // Combinar celdas
        const filaInicial = initialCell.parentElement;
        const filaFinal = celdaFinal.parentElement;
        const filas = Array.from(horarioBody.querySelectorAll('tr'));
        const inicioIdx = filas.indexOf(filaInicial);
        const finIdx = filas.indexOf(filaFinal);
        
        // Limpiar celdas intermedias
        for (let i = inicioIdx + 1; i <= finIdx; i++) {
            const celda = filas[i].querySelector(`[data-dia="${dia}"]`);
            if (celda) {
                celda.innerHTML = '';
                celda.style.backgroundColor = 'transparent';
            }
        }
        
        // Aplicar estilo de bloque expandido
        bloque.classList.add('expanded-block');
        bloque.style.height = `${(finIdx - inicioIdx + 1) * 60}px`; // 60px por fila
        
        // Quitar clases de expansión
        document.querySelectorAll('.expand-cell').forEach(c => c.classList.remove('expand-cell'));
    }
    
    // 10. Función para eliminar un bloque
    function eliminarBloque(bloque) {
        bloque.parentElement.innerHTML = '';
        actualizarBotonGuardar();
    }
    
    // 11. Función auxiliar para convertir hora a minutos
    function convertirHoraAMinutos(horaStr) {
        const [horas, minutos] = horaStr.split(':').map(Number);
        return horas * 60 + minutos;
    }
    
    // 12. Actualizar estado del botón guardar
    function actualizarBotonGuardar() {
        const bloques = document.querySelectorAll('.bloque-horario');
        guardarBtn.disabled = bloques.length === 0;
    }
    
    // 13. Enviar formulario
    document.getElementById('horarioForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const bloques = Array.from(document.querySelectorAll('.bloque-horario')).map(bloque => ({
            asignatura_id: bloque.dataset.asignaturaId,
            dia: bloque.dataset.dia,
            hora_inicio: bloque.dataset.horaInicio,
            hora_fin: bloque.dataset.horaFin,
            duracion: bloque.dataset.duracion
        }));
        
        document.getElementById('horarioData').value = JSON.stringify(bloques);
        this.submit();
    });
});
</script>

<style>
    /* Estilos generales */
    #agregarHorarioModal {
        font-size: 1.1rem;
    }
    
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
    
    /* Estilo para celdas seleccionadas para expansión */
    .celda-horario.expand-cell {
        background-color: rgba(25, 135, 84, 0.2) !important;
        border: 2px dashed #198754 !important;
        cursor: pointer;
    }
    
    /* Bloques de horario */
    .bloque-horario {
        position: relative;
        height: 60px; /* Altura de una fila */
        border-radius: 4px;
        padding: 8px;
        font-size: 0.85rem;
        color: white;
        cursor: move;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.2s;
        margin: 2px 0;
    }
    
    /* Bloques expandidos */
    .bloque-horario.expanded-block {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    /* Botones en bloques */
    .bloque-horario .btn {
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .bloque-horario:hover .btn {
        opacity: 1;
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
    
    /* Ajustes para modal fullscreen */
    #agregarHorarioModal .modal-body {
        overflow-y: auto;
    }
    
    #horarioTable tbody {
        display: block;
        overflow-y: auto;
        height: calc(100vh - 350px);
    }
    
    #horarioTable thead, #horarioTable tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
</style>