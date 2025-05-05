<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="registroModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Registrar Nueva Asignatura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('asignatura.store') }}" id="formAsignatura">
                    @csrf

                    <!-- Código de Asignatura -->
                    <div class="form-group mb-3">
                        <label for="asignatura_id" class="form-label">Código <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            <input type="text"
                                class="form-control @error('asignatura_id') is-invalid @enderror"
                                id="asignatura_id"
                                name="asignatura_id"
                                value="{{ old('asignatura_id') }}"
                                placeholder="Ej: MAT-101"
                                required
                                autofocus>
                        </div>
                        @error('asignatura_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nombre de Asignatura -->
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Ej: Matemáticas Básicas"
                                required>
                        </div>
                        @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selector de Docentes con buscador -->
                    <div class="form-group mb-3">
                        <label for="docentes" class="form-label">Docentes <span class="text-danger">*</span></label>
                        <div class="search-container mb-2">
                            <input type="text"
                                class="form-control"
                                id="buscarDocente"
                                placeholder="Buscar docente..."
                                onkeyup="filtrarOpciones('docentes', this.value)"
                                autocomplete="off">
                            <div class="no-results" id="docentesNoResults" style="display: none;">
                                <small class="text-muted">No se encontraron resultados</small>
                            </div>
                        </div>
                        <select name="docentes[]"
                            id="docentes"
                            class="form-select @error('docentes') is-invalid @enderror"
                            multiple
                            size="5"
                            required
                            style="display: none;">
                            @foreach($docentes as $docente)
                            <option value="{{ $docente->cedula_doc }}"
                                data-busqueda="{{ strtolower($docente->name) }} {{ $docente->cedula_doc }}"
                                {{ in_array($docente->cedula_doc, old('docentes', [])) ? 'selected' : '' }}
                                style="display: none;">
                                {{ $docente->name }} - {{ $docente->cedula_doc }}
                            </option>
                            @endforeach
                        </select>
                        @error('docentes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selector de Secciones con datos embebidos -->
                    <div class="form-group mb-4">
                        <label for="secciones" class="form-label">Secciones <span class="text-danger">*</span></label>
                        <div class="search-container mb-2">
                            <input type="text"
                                class="form-control"
                                id="buscarSeccion"
                                placeholder="Buscar sección..."
                                onkeyup="filtrarOpciones('secciones', this.value)"
                                autocomplete="off">
                            <div class="no-results" id="seccionesNoResults" style="display: none;">
                                <small class="text-muted">No se encontraron resultados</small>
                            </div>
                        </div>
                        <select name="secciones[]"
                            id="secciones"
                            class="form-select @error('secciones') is-invalid @enderror"
                            multiple
                            size="5"
                            required
                            style="display: none;"
                            onchange="actualizarDatosSeccion()">
                            @foreach($secciones as $seccion)
                            <option value="{{ $seccion->codigo_seccion }}"
                                data-carrera="{{ $seccion->carrera_id }}"
                                data-semestre="{{ $seccion->semestre_id }}"
                                data-turno="{{ $seccion->turno_id }}"
                                data-busqueda="{{ strtolower($seccion->codigo_seccion) }} {{ strtolower($seccion->carrera->name) }} semestre{{ $seccion->semestre->numero }} {{ strtolower($seccion->turno->nombre) }}"
                                {{ in_array($seccion->codigo_seccion, old('secciones', [])) ? 'selected' : '' }}
                                style="display: none;">
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
                    <input type="hidden" name="carrera_id" id="carrera_id">
                    <input type="hidden" name="semestre_id" id="semestre_id">
                    <input type="hidden" name="turno_id" id="turno_id">

                    <!-- Botón de envío -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Registrar Asignatura
                        </button>
                    </div>




                                <!-- Scripts para manejar alerts -->
            <script>
                // Mostrar errores de validación
                @if($errors->any())
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            html: `@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach`,
                            timer: 5000,
                            timerProgressBar: true
                        });
                    });
                @endif

                // Mostrar éxito después de redirección
                @if(session('success'))
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: "{{ session('success') }}",
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
                @endif

                // Auto-abrir modal si hay errores
                @if($errors->any())
                    document.addEventListener('DOMContentLoaded', function() {
                        $('#registroModal').modal('show');
                    });
                @endif

                // Confirmación antes de enviar el formulario
                document.getElementById('formAsignatura').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    
                    Swal.fire({
                        title: '¿Registrar nueva asignatura?',
                        text: "¡Verifique que los datos sean correctos!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, registrar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            </script>

            <style>
                /* Estilos personalizados para SweetAlert */
                .swal2-popup {
                    font-size: 1.6rem;
                }
                .swal2-title {
                    font-size: 2.0rem;
                }
            </style>

                    <script>
                        // Actualizar datos ocultos al seleccionar secciones
                        function actualizarDatosSeccion() {
                            const seccionesSelect = document.getElementById('secciones');
                            const selectedOptions = Array.from(seccionesSelect.selectedOptions);
                            
                            if (selectedOptions.length > 0) {
                                const primeraSeccion = selectedOptions[0];
                                document.getElementById('carrera_id').value = primeraSeccion.dataset.carrera;
                                document.getElementById('semestre_id').value = primeraSeccion.dataset.semestre;
                                document.getElementById('turno_id').value = primeraSeccion.dataset.turno;
                            }
                        }

                        // Función para filtrar opciones
                        function filtrarOpciones(selectId, busqueda) {
                            const select = document.getElementById(selectId);
                            const noResults = document.getElementById(`${selectId}NoResults`);
                            const opciones = select.options;
                            let resultados = 0;

                            busqueda = busqueda.toLowerCase().trim();
                            select.style.display = busqueda ? 'block' : 'none';

                            for (let i = 0; i < opciones.length; i++) {
                                const textoBusqueda = opciones[i].getAttribute('data-busqueda').toLowerCase();
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
                        }
                        select[multiple] option {
                            padding: 0.5rem 1rem;
                            cursor: pointer;
                            border-bottom: 1px solid #f8f9fa;
                        }
                    </style>
                </form>
            </div>
        </div>
    </div>
</div>