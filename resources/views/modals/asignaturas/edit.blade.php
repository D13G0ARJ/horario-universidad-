<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editarModalLabel">
                    <i class="fas fa-edit me-2"></i>Editar Asignatura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="asignatura_id_editar" class="form-label">Código <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            <input type="text"
                                class="form-control @error('asignatura_id') is-invalid @enderror"
                                name="asignatura_id"
                                id="asignatura_id_editar"
                                required
                                readonly>
                        </div>
                        @error('asignatura_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name_editar"
                                name="name"
                                placeholder="Ej: Matemáticas Básicas"
                                required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Docentes --}}
                    <div class="form-group mb-3">
                        <label for="docentes_editar" class="form-label">Docentes <span class="text-danger">*</span></label>
                        <div id="docentes-selected-container-editar" class="selected-items-container mb-2">
                            </div>
                        <div class="search-container">
                            <input type="text" class="form-control" id="buscarDocenteEditar" placeholder="Buscar docente...">
                            <div class="no-results" style="display: none;">No se encontraron resultados</div>
                        </div>
                        <select name="docentes[]"
                            id="docentes_editar"
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

                    {{-- Secciones --}}
                    <div class="form-group mb-4">
                        <label for="secciones_editar" class="form-label">Secciones <span class="text-danger">*</span></label>
                        <div id="secciones-selected-container-editar" class="selected-items-container mb-2">
                            </div>
                        <div class="search-container">
                            <input type="text" class="form-control" id="buscarSeccionEditar" placeholder="Buscar sección...">
                            <div class="no-results" style="display: none;">No se encontraron resultados</div>
                        </div>
                        <select name="secciones[]"
                            id="secciones_editar"
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
                        <div id="cargaHorariaContainerEditar">
                            {{-- Los bloques de carga horaria se cargarán aquí dinámicamente con JavaScript --}}
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="agregarBloqueCargaButtonEditar">
                            <i class="fas fa-plus me-1"></i> Agregar Tipo
                        </button>
                        @error('carga_horaria')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" id="carrera_id_hidden_editar" name="carrera_id">
                    <input type="hidden" id="semestre_id_hidden_editar" name="semestre_id">
                    <input type="hidden" id="turno_id_hidden_editar" name="turno_id">

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitButtonEditar">
                            <i class="fas fa-save me-2"></i>Actualizar Asignatura
                        </button>
                    </div>

                    <template id="cargaHorariaTemplateEditar">
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
                                    <button type="button" class="btn btn-danger btn-block eliminar-bloque-carga-editar">
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
    let bloqueIndexEditar = 0;

    function agregarBloqueCargaEditar(tipo = '', horas = '') {
        const container = document.getElementById('cargaHorariaContainerEditar');
        const template = document.getElementById('cargaHorariaTemplateEditar').innerHTML;
        const html = template.replace(/__INDEX__/g, bloqueIndexEditar);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;

        const appendedBlock = tempDiv.firstElementChild;
        container.appendChild(appendedBlock);

        if (tipo) {
            const tipoSelect = appendedBlock.querySelector('.tipo-select');
            if (tipoSelect) {
                tipoSelect.value = tipo;
            } else {
                console.error('Error: .tipo-select no encontrado en el bloque de carga horaria.');
            }
        }
        if (horas) {
            const horasSelect = appendedBlock.querySelector('.horas-select');
            if (horasSelect) {
                horasSelect.value = horas;
            } else {
                console.error('Error: .horas-select no encontrado en el bloque de carga horaria.');
            }
        }

        bloqueIndexEditar++;

        if (document.getElementById('editarModal').classList.contains('show')) {
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

    function eliminarBloqueCargaEditar(btn) {
        const bloque = btn.closest('.carga-horaria-block');
        const bloquesRestantes = document.querySelectorAll('#cargaHorariaContainerEditar .carga-horaria-block').length;

        if (bloquesRestantes <= 1) {
            Swal.fire('Error', 'Debe mantener al menos un bloque de carga horaria', 'error');
            return;
        }

        bloque.remove();
        reindexarBloquesCargaEditar();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Bloque eliminado',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function reindexarBloquesCargaEditar() {
        const blocks = document.querySelectorAll('#cargaHorariaContainerEditar .carga-horaria-block');
        blocks.forEach((block, newIndex) => {
            block.querySelector('.tipo-select').name = `carga_horaria[${newIndex}][tipo]`;
            block.querySelector('.horas-select').name = `carga_horaria[${newIndex}][horas_academicas]`;
        });
        bloqueIndexEditar = blocks.length;
    }

    /**
     * Filtra las opciones de un select basado en la búsqueda y gestiona los chips para el modal de edición.
     * @param {string} selectId - El ID del select (ej. 'docentes_editar' o 'secciones_editar').
     * @param {string} inputId - El ID del input de búsqueda (ej. 'buscarDocenteEditar' o 'buscarSeccionEditar').
     * @param {string} containerId - El ID del contenedor de chips (ej. 'docentes-selected-container-editar').
     * @param {Array} initialSelectedValues - Un array de valores que deben estar seleccionados inicialmente.
     */
    function setupSearchableSelectEditar(selectId, inputId, containerId, initialSelectedValues = []) {
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

        // Precargar opciones al inicio o cuando se abre el modal
        initialSelectedValues.forEach(val => {
            const option = selectElement.querySelector(`option[value="${val}"]`);
            if (option) {
                option.selected = true;
            }
        });
        renderSelectedChips(); // Renderizar los chips con los valores iniciales

        // Event listener para filtrar las opciones al escribir
        searchInput.oninput = function() {
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
        };

        // Event listener para seleccionar una opción del select
        selectElement.onchange = function() {
            renderSelectedChips();

            // Limpiar el input de búsqueda y ocultar las opciones filtradas
            searchInput.value = '';
            allOptions.forEach(option => option.style.display = 'none');
            selectElement.style.display = 'none';
            noResultsDiv.style.display = 'none';
        };

        // Event listener para eliminar chips (delegación de eventos)
        selectedContainer.onclick = function(event) {
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
        };
    }


    // Event listener para el botón "Agregar Tipo" de carga horaria
    document.getElementById('agregarBloqueCargaButtonEditar').addEventListener('click', function() {
        agregarBloqueCargaEditar();
    });

    // Event listener para los botones de eliminar carga horaria (delegación de eventos)
    document.getElementById('cargaHorariaContainerEditar').addEventListener('click', function(event) {
        if (event.target.classList.contains('eliminar-bloque-carga-editar') || event.target.closest('.eliminar-bloque-carga-editar')) {
            eliminarBloqueCargaEditar(event.target.closest('.eliminar-bloque-carga-editar'));
        }
    });

    // Event listener para cuando el modal de edición se muestra
    const editarModalElement = document.getElementById('editarModal');
    editarModalElement.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const asignaturaId = button.dataset.asignaturaId;
        const asignaturaName = button.dataset.asignaturaName;
        const asignaturaDocentes = JSON.parse(button.dataset.asignaturaDocentes || '[]');
        const asignaturaSecciones = JSON.parse(button.dataset.asignaturaSecciones || '[]');
        const asignaturaCargaHoraria = JSON.parse(button.dataset.asignaturaCargaHoraria || '[]');

        // Precargar campos básicos
        document.getElementById('asignatura_id_editar').value = asignaturaId;
        document.getElementById('name_editar').value = asignaturaName;

        // Actualizar la acción del formulario
        const formEditar = document.getElementById('formEditar');
        formEditar.action = `/asignaturas/${asignaturaId}`;

        // Configurar y precargar Docentes
        setupSearchableSelectEditar('docentes_editar', 'buscarDocenteEditar', 'docentes-selected-container-editar', asignaturaDocentes);

        // Configurar y precargar Secciones
        setupSearchableSelectEditar('secciones_editar', 'buscarSeccionEditar', 'secciones-selected-container-editar', asignaturaSecciones);

        // Precargar campos ocultos de carrera, semestre y turno de la primera sección seleccionada
        const seccionesSelect = document.getElementById('secciones_editar');
        const firstSelectedOption = Array.from(seccionesSelect.selectedOptions).find(option => option.dataset.carreraId);

        if (firstSelectedOption) {
            document.getElementById('carrera_id_hidden_editar').value = firstSelectedOption.dataset.carreraId;
            document.getElementById('semestre_id_hidden_editar').value = firstSelectedOption.dataset.semestreId;
            document.getElementById('turno_id_hidden_editar').value = firstSelectedOption.dataset.turnoId;
        } else {
            document.getElementById('carrera_id_hidden_editar').value = '';
            document.getElementById('semestre_id_hidden_editar').value = '';
            document.getElementById('turno_id_hidden_editar').value = '';
        }

        // Precargar bloques de carga horaria
        const cargaHorariaContainer = document.getElementById('cargaHorariaContainerEditar');
        cargaHorariaContainer.innerHTML = '';
        bloqueIndexEditar = 0;

        if (asignaturaCargaHoraria.length > 0) {
            asignaturaCargaHoraria.forEach(carga => {
                agregarBloqueCargaEditar(carga.tipo, carga.horas_academicas);
            });
        } else {
            agregarBloqueCargaEditar();
        }
    });

    // Event listener para la validación del formulario al enviar
    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitButton = document.getElementById('submitButtonEditar');
        const originalButtonText = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';

        let valid = true;
        const errorMessages = [];

        // Validar al menos un bloque de carga horaria
        const bloquesCargaHoraria = document.querySelectorAll('#cargaHorariaContainerEditar .carga-horaria-block');
        if (bloquesCargaHoraria.length === 0) {
            valid = false;
            errorMessages.push('Debe agregar al menos un bloque de carga horaria.');
        }

        // Validar campos de carga horaria
        bloquesCargaHoraria.forEach(bloque => {
            const tipoSelect = bloque.querySelector('.tipo-select');
            const horasSelect = bloque.querySelector('.horas-select');

            tipoSelect.classList.remove('is-invalid');
            horasSelect.classList.remove('is-invalid');

            if (!tipoSelect.value) {
                valid = false;
                tipoSelect.classList.add('is-invalid');
                errorMessages.push('Complete el tipo de hora en todos los bloques de carga horaria.');
            }
            if (!horasSelect.value) {
                valid = false;
                horasSelect.classList.add('is-invalid');
                errorMessages.push('Complete las horas académicas en todos los bloques de carga horaria.');
            }
        });

        // Validar selección de docentes
        const docentesSelect = document.getElementById('docentes_editar');
        const docentesSelectedCount = docentesSelect.selectedOptions.length;
        if (docentesSelectedCount === 0) {
            valid = false;
            docentesSelect.classList.add('is-invalid');
            errorMessages.push('Debe seleccionar al menos un docente.');
        } else {
            docentesSelect.classList.remove('is-invalid');
        }

        // Validar selección de secciones
        const seccionesSelect = document.getElementById('secciones_editar');
        const seccionesSelectedCount = seccionesSelect.selectedOptions.length;
        if (seccionesSelectedCount === 0) {
            valid = false;
            seccionesSelect.classList.add('is-invalid');
            errorMessages.push('Debe seleccionar al menos una sección.');
        } else {
            seccionesSelect.classList.remove('is-invalid');
            const firstSelectedOption = Array.from(seccionesSelect.selectedOptions).find(option => option.dataset.carreraId);
            if (firstSelectedOption) {
                document.getElementById('carrera_id_hidden_editar').value = firstSelectedOption.dataset.carreraId;
                document.getElementById('semestre_id_hidden_editar').value = firstSelectedOption.dataset.semestreId;
                document.getElementById('turno_id_hidden_editar').value = firstSelectedOption.dataset.turnoId;
            } else {
                document.getElementById('carrera_id_hidden_editar').value = '';
                document.getElementById('semestre_id_hidden_editar').value = '';
                document.getElementById('turno_id_hidden_editar').value = '';
            }
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

    // Mantener modal abierto si hay errores de validación de Laravel
    @if ($errors->any() && session('open_edit_modal'))
        $(document).ready(function() {
            $('#editarModal').modal('show');
            // Al reabrir el modal por errores, necesitamos reconfigurar los selects de búsqueda
            // con los valores antiguos (Laravel debería haberlos puesto en old()).
            // Como no tenemos los 'old()' de docentes/secciones directamente en JS para el edit,
            // si Laravel los repobla, el `setupSearchableSelectEditar` se encargará si la opción
            // tiene 'selected' atributo. Si no, habría que pasarlos explícitamente.
            // Para fines de este ejemplo, asumiremos que Laravel los marca.
            // Opcional: si `old()` no los precarga, necesitarías una forma de pasar esos `old()`
            // valores al JS aquí, quizás en un `data` attribute del modal.
            // Por ejemplo, si tienes los old docentes en PHP:
            // const oldDocentes = {!! json_encode(old('docentes', [])) !!};
            // setupSearchableSelectEditar('docentes_editar', 'buscarDocenteEditar', 'docentes-selected-container-editar', oldDocentes);
        });
    @endif
</script>

<style>
    /* Estilos copiados de create.blade.php y ya existentes */
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
    .selected-items-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 5px;
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

    select[multiple] {
        border-top: none;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    .btn-danger {
        transition: all 0.2s ease;
    }
    .btn-danger:hover {
        transform: scale(1.05);
    }
</style>