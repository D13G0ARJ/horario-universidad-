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
                <form method="POST" action="{{ route('asignatura.store') }}">
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
                                placeholder="Escribe para buscar docentes..."
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
                            style="display: none;"> <!-- Ocultar inicialmente -->
                            @foreach($docentes as $docente)
                            <option value="{{ $docente->cedula_doc }}"
                                data-busqueda="{{ strtolower($docente->name) }} {{ $docente->cedula_doc }}"
                                {{ in_array($docente->cedula_doc, old('docentes', [])) ? 'selected' : '' }}
                                style="display: none;"> <!-- Ocultar inicialmente -->
                                {{ $docente->name }} - {{ $docente->cedula_doc }}
                            </option>
                            @endforeach
                        </select>
                        @error('docentes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selector de Secciones con buscador -->
                    <div class="form-group mb-4">
                        <label for="secciones" class="form-label">Secciones <span class="text-danger">*</span></label>

                        <div class="search-container mb-2">
                            <input type="text"
                                class="form-control"
                                id="buscarSeccion"
                                placeholder="Escribe para buscar secciones..."
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
                            style="display: none;"> <!-- Ocultar inicialmente -->
                            @foreach($secciones as $seccion)
                            <option value="{{ $seccion->codigo_seccion }}"
                                data-busqueda="{{ strtolower($seccion->codigo_seccion) }} {{ strtolower($seccion->carrera->nombre) }} semestre{{ $seccion->semestre->nombre }}"
                                {{ in_array($seccion->codigo_seccion, old('secciones', [])) ? 'selected' : '' }}
                                style="display: none;"> <!-- Ocultar inicialmente -->
                                {{ $seccion->codigo_seccion }} -
                                {{ $seccion->carrera->nombre }} -
                                Sem. {{ $seccion->semestre->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('secciones')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botón de envío -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Registrar Asignatura
                        </button>
                    </div>

                    <script>
                        function filtrarOpciones(selectId, busqueda) {
                            const select = document.getElementById(selectId);
                            const input = document.getElementById(`buscar${selectId.charAt(0).toUpperCase() + selectId.slice(1)}`);
                            const noResults = document.getElementById(`${selectId}NoResults`);
                            const opciones = select.options;
                            let resultados = 0;

                            busqueda = busqueda.toLowerCase().trim();

                            // Mostrar/ocultar select
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

                            // Manejar mensaje de no resultados
                            noResults.style.display = resultados === 0 && busqueda !== '' ? 'block' : 'none';

                            // Ajustar tamaño del select
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
                            transition: all 0.3s ease;
                        }

                        select[multiple] option {
                            padding: 0.5rem 1rem;
                            cursor: pointer;
                            border-bottom: 1px solid #f8f9fa;
                        }

                        select[multiple] option:last-child {
                            border-bottom: none;
                        }
                    </style>
                </form>
            </div>
        </div>
    </div>
</div>