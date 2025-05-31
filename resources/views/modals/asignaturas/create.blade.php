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

                    <div class="form-group mb-3">
                        <label for="docentes" class="form-label">Docentes <span class="text-danger">*</span></label>
                        <div id="docentes-selected-container" class="selected-items-container mb-2">
                            </div>
                        <div class="search-container">
                            <input type="text" class="form-control" id="buscarDocente" placeholder="Buscar docente...">
                            <div class="no-results" style="display: none;">No se encontraron resultados</div>
                        </div>
                        <select name="docentes[]"
                            id="docentes"
                            class="form-select @error('docentes') is-invalid @enderror"
                            multiple
                            required
                            style="display: none;"> {{-- Ocultamos el select original --}}
                            @foreach($docentes as $docente)
                            <option value="{{ $docente->cedula_doc }}" data-nombre="{{ $docente->name }} - {{ $docente->cedula_doc }}">
                                {{ $docente->name }} - {{ $docente->cedula_doc }}
                            </option>
                            @endforeach
                        </select>
                        @error('docentes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="secciones" class="form-label">Secciones <span class="text-danger">*</span></label>
                        <div id="secciones-selected-container" class="selected-items-container mb-2">
                            </div>
                        <div class="search-container">
                            <input type="text" class="form-control" id="buscarSeccion" placeholder="Buscar sección...">
                            <div class="no-results" style="display: none;">No se encontraron resultados</div>
                        </div>
                        <select name="secciones[]"
                            id="secciones"
                            class="form-select @error('secciones') is-invalid @enderror"
                            multiple
                            required
                            style="display: none;"> {{-- Ocultamos el select original --}}
                            @foreach($secciones as $seccion)
                            <option value="{{ $seccion->codigo_seccion }}"
                                data-carrera-id="{{ $seccion->carrera_id }}"
                                data-semestre-id="{{ $seccion->semestre_id }}"
                                data-turno-id="{{ $seccion->turno_id }}"
                                data-nombre="{{ $seccion->codigo_seccion }} - {{ $seccion->carrera->name }} - Sem. {{ $seccion->semestre->numero }} - {{ $seccion->turno->nombre }}">
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
                                                <option value="teorica" {{ $carga['tipo'] == 'teorica' ? 'selected' : '' }}>Teórica</option>
                                                <option value="practica" {{ $carga['tipo'] == 'practica' ? 'selected' : '' }}>Práctica</option>
                                                <option value="laboratorio" {{ $carga['tipo'] == 'laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select horas-select" name="carga_horaria[{{$index}}][horas_academicas]" required>
                                                <option value="">Horas...</option>
                                                @for ($i = 1; $i <= 6; $i++)
                                                <option value="{{ $i }}" {{ $carga['horas_academicas'] == $i ? 'selected' : '' }}>{{ $i }}h</option>
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
                            @endif
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="agregarBloqueCarga()">
                            <i class="fas fa-plus me-1"></i> Agregar Tipo
                        </button>
                        @error('carga_horaria')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitButton">
                            <i class="fas fa-save me-2"></i>Registrar Asignatura
                        </button>
                    </div>

                    <template id="cargaHorariaTemplate">
                        <div class="carga-horaria-block mb-3">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <select class="form-select tipo-select" name="carga_horaria[__INDEX__][tipo]" required>
                                        <option value="">Seleccionar tipo...</option>
                                        <option value="teorica">Teórica</option>
                                        <option value="practica">Práctica</option>
                                        <option value="laboratorio">Laboratorio</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select horas-select" name="carga_horaria[__INDEX__][horas_academicas]" required>
                                        <option value="">Horas...</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}">{{ $i }}h</option>
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
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let bloqueIndex = {{ is_array(old('carga_horaria')) ? count(old('carga_horaria')) : 0 }};
    let initialLoad = true;

    function agregarBloqueCarga() {
        const container = document.getElementById('cargaHorariaContainer');
        const template = document.getElementById('cargaHorariaTemplate').innerHTML;
        const html = template.replace(/__INDEX__/g, bloqueIndex);
        container.insertAdjacentHTML('beforeend', html);
        bloqueIndex++;

        if (!initialLoad) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Nuevo bloque agregado',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }

    function eliminarBloqueCarga(btn) {
        const bloque = btn.closest('.carga-horaria-block');
        const bloquesRestantes = document.querySelectorAll('.carga-horaria-block').length;

        if (bloquesRestantes <= 1) {
            Swal.fire('Error', 'Debe mantener al menos un bloque de carga horaria', 'error');
            return;
        }

        bloque.remove();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Bloque eliminado',
            showConfirmButton: false,
            timer: 1500
        });
    }

    /**
     * Filtra las opciones de un select basado en la búsqueda y gestiona los chips.
     * @param {string} selectId - El ID del select (ej. 'docentes' o 'secciones').
     * @param {string} inputId - El ID del input de búsqueda (ej. 'buscarDocente' o 'buscarSeccion').
     * @param {string} containerId - El ID del contenedor de chips (ej. 'docentes-selected-container').
     * @param {Array} initialSelectedValues - Un array de valores que deben estar seleccionados inicialmente.
     */
    function setupSearchableSelect(selectId, inputId, containerId, initialSelectedValues = []) {
        const selectElement = document.getElementById(selectId);
        const searchInput = document.getElementById(inputId);
        const noResultsDiv = searchInput.nextElementSibling;
        const selectedContainer = document.getElementById(containerId);
        let allOptions = Array.from(selectElement.options);

        // Limpiar selecciones previas y chips al inicializar
        allOptions.forEach(option => option.selected = false);
        selectedContainer.innerHTML = '';
        searchInput.value = '';
        selectElement.style.display = 'none';
        noResultsDiv.style.display = 'none';

        // Función para renderizar los chips seleccionados
        function renderSelectedChips() {
            selectedContainer.innerHTML = '';
            let selectedOptions = Array.from(selectElement.selectedOptions);

            selectedOptions.forEach(option => {
                const chip = document.createElement('span');
                chip.classList.add('selected-chip', 'badge', 'bg-primary', 'me-2', 'mb-1');
                chip.setAttribute('data-value', option.value);
                chip.innerHTML = `${option.dataset.nombre || option.textContent} <i class="fas fa-times-circle remove-chip-icon"></i>`;
                selectedContainer.appendChild(chip);
            });
        }

        // Precargar opciones al inicio o cuando se abre el modal con old()
        initialSelectedValues.forEach(val => {
            const option = selectElement.querySelector(`option[value="${val}"]`);
            if (option) {
                option.selected = true;
            }
        });
        renderSelectedChips(); // Renderizar los chips basados en las opciones seleccionadas

        // Event listener para filtrar las opciones al escribir
        searchInput.addEventListener('input', function() {
            const busqueda = this.value.toLowerCase().trim();
            let foundResults = false;

            allOptions.forEach(option => {
                const optionText = (option.dataset.nombre || option.textContent).toLowerCase();
                // Solo mostrar opciones que NO ESTÉN seleccionadas
                if (optionText.includes(busqueda) && !option.selected) {
                    option.style.display = '';
                    foundResults = true;
                } else {
                    option.style.display = 'none';
                }
            });

            noResultsDiv.style.display = foundResults || busqueda === '' ? 'none' : 'block';
            selectElement.style.display = foundResults && busqueda !== '' ? 'block' : 'none';
        });

        // Event listener para seleccionar una opción del select
        selectElement.addEventListener('change', function() {
            renderSelectedChips();

            // Limpiar el input de búsqueda y ocultar las opciones filtradas
            searchInput.value = '';
            allOptions.forEach(option => option.style.display = 'none');
            selectElement.style.display = 'none';
            noResultsDiv.style.display = 'none';
        });

        // Event listener para eliminar chips (delegación de eventos)
        selectedContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-chip-icon')) {
                const chip = event.target.closest('.selected-chip');
                const valueToRemove = chip.dataset.value;

                // Desmarcar la opción en el select original
                const optionToRemove = selectElement.querySelector(`option[value="${valueToRemove}"]`);
                if (optionToRemove) {
                    optionToRemove.selected = false;
                }
                chip.remove();

                renderSelectedChips();
            }
        });
    }


    // Llamar la función para configurar los buscadores de docentes y secciones
    document.addEventListener('DOMContentLoaded', function() {
        // Pasar los valores old() directamente desde Blade
        setupSearchableSelect('docentes', 'buscarDocente', 'docentes-selected-container', {!! json_encode(old('docentes', [])) !!});
        setupSearchableSelect('secciones', 'buscarSeccion', 'secciones-selected-container', {!! json_encode(old('secciones', [])) !!});

        // Agregar primer bloque si no hay datos antiguos de carga horaria
        if (!{{ is_array(old('carga_horaria')) && count(old('carga_horaria')) > 0 ? 'true' : 'false' }}) {
            agregarBloqueCarga();
        }
        initialLoad = false;
    });

    document.getElementById('formAsignatura').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitButton = document.getElementById('submitButton');
        const originalButtonText = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';

        let valid = true;
        const errorMessages = [];

        // Validar al menos un bloque de carga horaria
        const bloques = document.querySelectorAll('.carga-horaria-block');
        if (bloques.length === 0) {
            valid = false;
            errorMessages.push('Debe agregar al menos un bloque de carga horaria');
        }

        // Validar campos de carga horaria
        bloques.forEach(bloque => {
            const tipo = bloque.querySelector('.tipo-select').value;
            const horas = bloque.querySelector('.horas-select').value;

            bloque.querySelector('.tipo-select').classList.remove('is-invalid');
            bloque.querySelector('.horas-select').classList.remove('is-invalid');

            if (!tipo) {
                valid = false;
                tipo.classList.add('is-invalid');
                errorMessages.push('Complete el tipo de hora en todos los bloques de carga horaria.');
            }
            if (!horas) {
                valid = false;
                horas.classList.add('is-invalid');
                errorMessages.push('Complete las horas académicas en todos los bloques de carga horaria.');
            }
        });

        // Validar selección de docentes
        const docentesSelect = document.getElementById('docentes');
        const docentesSelectedCount = docentesSelect.selectedOptions.length;
        if (docentesSelectedCount === 0) {
            valid = false;
            docentesSelect.classList.add('is-invalid');
            errorMessages.push('Debe seleccionar al menos un docente.');
        } else {
            docentesSelect.classList.remove('is-invalid');
        }

        // Validar selección de secciones
        const seccionesSelect = document.getElementById('secciones');
        const seccionesSelectedCount = seccionesSelect.selectedOptions.length;
        if (seccionesSelectedCount === 0) {
            valid = false;
            seccionesSelect.classList.add('is-invalid');
            errorMessages.push('Debe seleccionar al menos una sección.');
        } else {
            seccionesSelect.classList.remove('is-invalid');
        }

        if (!valid) {
            Swal.fire({
                icon: 'error',
                title: 'Error en el formulario',
                html: [...new Set(errorMessages)].join('<br>')
            });

            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            return;
        }

        form.submit();
    });

    // Mantener modal abierto si hay errores
    @if ($errors->any() && session('open_modal'))
        $(document).ready(function() {
            $('#registroModal').modal('show');

            @if(is_array(old('carga_horaria')))
                bloqueIndex = {{ count(old('carga_horaria')) }};
            @endif

            // Reconfigurar los selects de búsqueda con los valores antiguos
            setupSearchableSelect('docentes', 'buscarDocente', 'docentes-selected-container', {!! json_encode(old('docentes', [])) !!});
            setupSearchableSelect('secciones', 'buscarSeccion', 'secciones-selected-container', {!! json_encode(old('secciones', [])) !!});
        });
    @endif
</script>

<style>
    /* Estilos existentes para carga horaria */
    .carga-horaria-block {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    .carga-horaria-block:hover {
        background: #e9ecef;
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
        color: #6c757d;
        text-align: center;
    }
    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.25rem rgba(220,53,69,.25);
    }
    /* Estilos para los chips seleccionados */
    .selected-items-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 5px; /* Ajustado para que los chips no se peguen al borde */
        border: 1px solid #ced4da;
        border-radius: .25rem;
        min-height: calc(1.5em + 0.75rem + 2px);
        align-items: center;
        background-color: #e9ecef;
    }
    .selected-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.35em 0.65em;
        font-size: .8em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
        background-color: #007bff;
    }
    .selected-chip .remove-chip-icon {
        margin-left: 0.5em;
        cursor: pointer;
        font-size: 0.9em;
        transition: color 0.2s ease-in-out;
    }
    .selected-chip .remove-chip-icon:hover {
        color: #f8f9fa;
    }

    /* Opciones del select al filtrar */
    select[multiple] {
        border-top: none;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>