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

                    <!-- Carga Horaria -->
                    <div class="form-group mb-4">
                        <label class="form-label">Carga Horaria <span class="text-danger">*</span></label>
                        <div id="cargaHorariaContainer">
                            @if(old('carga_horaria'))
                                @foreach(old('carga_horaria') as $index => $carga)
                                <div class="carga-horaria-block mb-3">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <select class="form-select tipo-select" name="carga_horaria[{{$index}}][tipo]" required>
                                                <option value="">Seleccionar tipo...</option>
                                                <option value="teorica" {{ $carga['tipo'] == 'teorica' ? 'selected' : '' }}>Horas Teóricas</option>
                                                <option value="practica" {{ $carga['tipo'] == 'practica' ? 'selected' : '' }}>Horas Prácticas</option>
                                                <option value="laboratorio" {{ $carga['tipo'] == 'laboratorio' ? 'selected' : '' }}>Horas Laboratorio</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select horas-select" name="carga_horaria[{{$index}}][horas_academicas]" required>
                                                <option value="">Horas...</option>
                                                @for ($i = 1; $i <= 6; $i++)
                                                <option value="{{ $i }}" {{ $carga['horas_academicas'] == $i ? 'selected' : '' }}>{{ $i }} hora(s)</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-block" onclick="eliminarBloqueCarga(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <!-- Bloques dinámicos se agregarán aquí -->
                            @endif
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="agregarBloqueCarga()">
                            <i class="fas fa-plus me-1"></i> Agregar Tipo
                        </button>
                        @error('carga_horaria')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campos ocultos para datos de sección -->
                    <input type="hidden" name="carrera_id" id="carrera_id" value="{{ old('carrera_id') }}">
                    <input type="hidden" name="semestre_id" id="semestre_id" value="{{ old('semestre_id') }}">
                    <input type="hidden" name="turno_id" id="turno_id" value="{{ old('turno_id') }}">

                    <!-- Botón de envío -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Registrar Asignatura
                        </button>
                    </div>

                    <!-- Template para carga horaria -->
                    <template id="cargaHorariaTemplate">
                        <div class="carga-horaria-block mb-3">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <select class="form-select tipo-select" name="carga_horaria[__INDEX__][tipo]" required>
                                        <option value="">Seleccionar tipo...</option>
                                        <option value="teorica">Horas Teóricas</option>
                                        <option value="practica">Horas Prácticas</option>
                                        <option value="laboratorio">Horas Laboratorio</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select horas-select" name="carga_horaria[__INDEX__][horas_academicas]" required>
                                        <option value="">Horas...</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}">{{ $i }} hora(s)</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-block" onclick="eliminarBloqueCarga(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Scripts -->
                    <script>
                        let bloqueIndex = {{ count(old('carga_horaria', [])) }};

                        // Función para agregar bloques de carga horaria
                        function agregarBloqueCarga() {
                            const container = document.getElementById('cargaHorariaContainer');
                            const template = document.getElementById('cargaHorariaTemplate').innerHTML;
                            const html = template.replace(/__INDEX__/g, bloqueIndex++);
                            container.insertAdjacentHTML('beforeend', html);
                        }

                        // Función para eliminar bloques
                        function eliminarBloqueCarga(btn) {
                            btn.closest('.carga-horaria-block').remove();
                        }

                        // Actualizar datos de sección
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

                        // Validación del formulario
                        document.getElementById('formAsignatura').addEventListener('submit', function(e) {
                            let valid = true;
                            const bloques = document.querySelectorAll('.carga-horaria-block');
                            
                            if (bloques.length === 0) {
                                valid = false;
                                Swal.fire('Error', 'Debe agregar al menos un bloque de carga horaria', 'error');
                            }

                            bloques.forEach(bloque => {
                                const tipo = bloque.querySelector('.tipo-select').value;
                                const horas = bloque.querySelector('.horas-select').value;
                                
                                if (!tipo || !horas) {
                                    valid = false;
                                    bloque.querySelector('.tipo-select').classList.add('is-invalid');
                                    bloque.querySelector('.horas-select').classList.add('is-invalid');
                                }
                            });

                            if (!valid) {
                                e.preventDefault();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Complete todos los campos requeridos'
                                });
                            }
                        });

                        // Inicializar bloques si hay datos antiguos
                        @if(!old('carga_horaria'))
                            document.addEventListener('DOMContentLoaded', agregarBloqueCarga);
                        @endif
                    </script>

                    <style>
                        .carga-horaria-block {
                            background: #f8f9fa;
                            padding: 10px;
                            border-radius: 5px;
                            border: 1px solid #dee2e6;
                        }
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
                        .is-invalid {
                            border-color: #dc3545 !important;
                            box-shadow: 0 0 0 0.25rem rgba(220,53,69,.25);
                        }
                    </style>
                </form>
            </div>
        </div>
    </div>
</div>