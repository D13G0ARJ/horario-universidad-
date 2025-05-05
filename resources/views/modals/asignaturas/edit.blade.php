<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Editar Asignatura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <!-- Código de Asignatura -->
                    <div class="form-group mb-3">
                        <label for="asignatura_id_editar" class="form-label">Código <span class="text-danger">*</span></label>
                        <input type="text" 
                            class="form-control" 
                            name="asignatura_id" 
                            id="asignatura_id_editar" 
                            required
                            readonly>
                    </div>

                    <!-- Nombre de Asignatura -->
                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            name="name" 
                            id="name_editar" 
                            required>
                    </div>

                    <!-- Selector de Docentes -->
                    <div class="form-group mb-3">
                        <label for="docentes_editar" class="form-label">Docentes <span class="text-danger">*</span></label>
                        <div class="search-container mb-2">
                            <input type="text" 
                                class="form-control" 
                                id="buscarDocenteEditar"
                                placeholder="Buscar docente..."
                                onkeyup="filtrarOpcionesEditar('docentes_editar', this.value)"
                                autocomplete="off">
                            <div class="no-results" id="docentes_editarNoResults" style="display: none;">
                                <small class="text-muted">No se encontraron resultados</small>
                            </div>
                        </div>
                        <select name="docentes[]" 
                            id="docentes_editar"
                            class="form-select @error('docentes') is-invalid @enderror"
                            multiple
                            size="5"
                            required
                            style="display: none;">
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->cedula_doc }}"
                                    data-busqueda="{{ strtolower($docente->name) }} {{ $docente->cedula_doc }}">
                                    {{ $docente->name }} - {{ $docente->cedula_doc }}
                                </option>
                            @endforeach
                        </select>
                        @error('docentes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selector de Secciones -->
                    <div class="form-group mb-4">
                        <label for="secciones_editar" class="form-label">Secciones <span class="text-danger">*</span></label>
                        <div class="search-container mb-2">
                            <input type="text" 
                                class="form-control" 
                                id="buscarSeccionEditar"
                                placeholder="Buscar sección..."
                                onkeyup="filtrarOpcionesEditar('secciones_editar', this.value)"
                                autocomplete="off">
                            <div class="no-results" id="secciones_editarNoResults" style="display: none;">
                                <small class="text-muted">No se encontraron resultados</small>
                            </div>
                        </div>
                        <select name="secciones[]" 
                            id="secciones_editar"
                            class="form-select @error('secciones') is-invalid @enderror"
                            multiple
                            size="5"
                            required
                            style="display: none;"
                            onchange="actualizarDatosSeccionEditar()">
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->codigo_seccion }}"
                                    data-carrera="{{ $seccion->carrera_id }}"
                                    data-semestre="{{ $seccion->semestre_id }}"
                                    data-turno="{{ $seccion->turno_id }}"
                                    data-busqueda="{{ strtolower($seccion->codigo_seccion) }} {{ strtolower($seccion->carrera->name) }} semestre{{ $seccion->semestre->numero }} {{ strtolower($seccion->turno->nombre) }}">
                                    {{ $seccion->codigo_seccion }} - 
                                    {{ $seccion->carrera->name }} - 
                                    Sem. {{ $seccion->semestre->numero }} - 
                                    {{ $seccion->turno->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('secciones')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campos ocultos para datos de sección -->
                    <input type="hidden" name="carrera_id" id="carrera_id_editar">
                    <input type="hidden" name="semestre_id" id="semestre_id_editar">
                    <input type="hidden" name="turno_id" id="turno_id_editar">

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Función de filtrado para edición
    function filtrarOpcionesEditar(selectId, busqueda) {
        const select = document.getElementById(selectId);
        const noResults = document.getElementById(`${selectId}NoResults`);
        const opciones = select.options;
        let resultados = 0;

        busqueda = busqueda.toLowerCase().trim();
        select.style.display = busqueda ? 'block' : 'none';

        for (let i = 0; i < opciones.length; i++) {
            const textoBusqueda = opciones[i].getAttribute('data-busqueda')?.toLowerCase() || '';
            if (textoBusqueda.includes(busqueda)) {
                opciones[i].style.display = 'block';
                resultados++;
            } else {
                opciones[i].style.display = 'none';
            }
        }

        noResults.style.display = resultados === 0 && busqueda !== '' ? 'block' : 'none';
        select.size = resultados > 5 ? 5 : resultados === 0 ? 1 : resultados;
    }

    // Actualizar datos de sección en edición
    function actualizarDatosSeccionEditar() {
        const seccionesSelect = document.getElementById('secciones_editar');
        const selectedOptions = Array.from(seccionesSelect.selectedOptions);
        
        if (selectedOptions.length > 0) {
            const primeraSeccion = selectedOptions[0];
            document.getElementById('carrera_id_editar').value = primeraSeccion.dataset.carrera;
            document.getElementById('semestre_id_editar').value = primeraSeccion.dataset.semestre;
            document.getElementById('turno_id_editar').value = primeraSeccion.dataset.turno;
        }
    }

    // Manejar la apertura del modal de edición
    document.getElementById('editarModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const form = this.querySelector('form');
        
        // Actualizar acción del formulario
        form.action = `/asignaturas/${button.dataset.asignatura_id}`;
        
        // Cargar datos básicos
        document.getElementById('asignatura_id_editar').value = button.dataset.asignatura_id;
        document.getElementById('name_editar').value = button.dataset.name;
        
        // Cargar docentes seleccionados
        const docenteSelect = document.getElementById('docentes_editar');
        const docentes = JSON.parse(button.dataset.docentes);
        Array.from(docenteSelect.options).forEach(option => {
            option.selected = docentes.includes(option.value);
            if (option.selected) option.style.display = 'block';
        });
        docenteSelect.size = Math.min(docenteSelect.selectedOptions.length + 1, 5);
        
        // Cargar secciones seleccionadas
        const seccionSelect = document.getElementById('secciones_editar');
        const secciones = JSON.parse(button.dataset.secciones);
        Array.from(seccionSelect.options).forEach(option => {
            option.selected = secciones.includes(option.value);
            if (option.selected) option.style.display = 'block';
        });
        seccionSelect.size = Math.min(seccionSelect.selectedOptions.length + 1, 5);
        
        // Cargar datos de relaciones
        document.getElementById('carrera_id_editar').value = button.dataset.carrera_id;
        document.getElementById('semestre_id_editar').value = button.dataset.semestre_id;
        document.getElementById('turno_id_editar').value = button.dataset.turno_id;
        
        // Forzar actualización visual
        filtrarOpcionesEditar('docentes_editar', '');
        filtrarOpcionesEditar('secciones_editar', '');
    });

    // Manejar cambios en las secciones
    document.getElementById('secciones_editar').addEventListener('change', function() {
        actualizarDatosSeccionEditar();
        this.size = Math.min(this.selectedOptions.length + 1, 5);
    });

    // Manejar cambios en docentes
    document.getElementById('docentes_editar').addEventListener('change', function() {
        this.size = Math.min(this.selectedOptions.length + 1, 5);
    });
</script>

<style>
    .search-container {
        position: relative;
        margin-bottom: 0.5rem;
    }
    
    .no-results {
        position: absolute;
        background: white;
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #dee2e6;
        border-top: none;
        z-index: 2;
    }
    
    select[multiple] {
        border-radius: 0.25rem;
        width: 100%;
        transition: height 0.3s ease;
    }
    
    select[multiple] option {
        padding: 0.5rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
    }
    
    select[multiple] option[selected] {
        background-color: #e9ecef;
        font-weight: 500;
    }
    
    select[multiple] option:hover {
        background-color: #f1f3f5 !important;
    }
</style>