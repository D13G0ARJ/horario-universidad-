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

                    <!-- Selector de Docente -->
                    <div class="form-group mb-3">
                        <label for="docente_editar" class="form-label">Docente <span class="text-danger">*</span></label>
                        <div class="search-container mb-2">
                            <input type="text" 
                                class="form-control" 
                                id="buscarDocenteEditar"
                                placeholder="Buscar docente..."
                                onkeyup="filtrarOpcionesEditar('docente_editar', this.value)"
                                autocomplete="off">
                        </div>
                        <select name="docente" 
                            id="docente_editar"
                            class="form-select"
                            size="5"
                            required>
                            <option value="" disabled>-- Seleccione un docente --</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->cedula_doc }}"
                                    data-busqueda="{{ strtolower($docente->name) }} {{ $docente->cedula_doc }}">
                                    {{ $docente->name }} - {{ $docente->cedula_doc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selector de Sección -->
                    <div class="form-group mb-4">
                        <label for="seccion_editar" class="form-label">Sección <span class="text-danger">*</span></label>
                        <div class="search-container mb-2">
                            <input type="text" 
                                class="form-control" 
                                id="buscarSeccionEditar"
                                placeholder="Buscar sección..."
                                onkeyup="filtrarOpcionesEditar('seccion_editar', this.value)"
                                autocomplete="off">
                        </div>
                        <select name="seccion" 
                            id="seccion_editar"
                            class="form-select"
                            size="5"
                            required>
                            <option value="" disabled>-- Seleccione una sección --</option>
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->codigo_seccion }}"
                                    data-busqueda="{{ strtolower($seccion->codigo_seccion) }} {{ strtolower($seccion->carrera->nombre) }}">
                                    {{ $seccion->codigo_seccion }} - {{ $seccion->carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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
        const opciones = select.options;
        busqueda = busqueda.toLowerCase().trim();
        
        for (let i = 0; i < opciones.length; i++) {
            const textoBusqueda = opciones[i].getAttribute('data-busqueda')?.toLowerCase() || '';
            opciones[i].style.display = textoBusqueda.includes(busqueda) ? 'block' : 'none';
        }
    }

    // Manejar la apertura del modal de edición
    document.getElementById('editarModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const form = this.querySelector('form');
        
        // Actualizar acción del formulario
        form.action = `/asignaturas/${button.dataset.asignatura_id}`;
        
        // Cargar datos existentes
        document.getElementById('asignatura_id_editar').value = button.dataset.asignatura_id;
        document.getElementById('name_editar').value = button.dataset.name;
        
        // Seleccionar docente actual
        const docenteSelect = document.getElementById('docente_editar');
        const docentes = JSON.parse(button.dataset.docentes);
        Array.from(docenteSelect.options).forEach(option => {
            option.selected = docentes.includes(option.value);
        });

        // Seleccionar sección actual
        const seccionSelect = document.getElementById('seccion_editar');
        const secciones = JSON.parse(button.dataset.secciones);
        Array.from(seccionSelect.options).forEach(option => {
            option.selected = secciones.includes(option.value);
        });
    });

    // Manejar selección única
    document.querySelectorAll('#docente_editar, #seccion_editar').forEach(select => {
        select.addEventListener('change', function() {
            Array.from(this.options).forEach(option => {
                if (option.value !== this.value) option.selected = false;
            });
            this.size = 1;
        });
        
        select.addEventListener('click', function() {
            this.size = this.options.length > 5 ? 5 : this.options.length;
        });
    });
</script>

<style>
    .search-container {
        position: relative;
        margin-bottom: 0.5rem;
    }
    
    select {
        transition: all 0.3s ease;
        overflow-y: auto;
        cursor: pointer;
        border-radius: 0.25rem;
    }
    
    option {
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    option:hover {
        background-color: #f8f9fa !important;
    }
    
    option:last-child {
        border-bottom: none;
    }
</style>