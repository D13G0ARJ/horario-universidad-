<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editarModalLabel">
                    <i class="fas fa-edit me-2"></i>Editar Asignatura </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditarAsignatura">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="asignatura_id_editar" class="form-label">Código <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            <input type="text"
                                   class="form-control @error('asignatura_id', 'update') is-invalid @enderror"
                                   name="asignatura_id"
                                   id="asignatura_id_editar"
                                   required
                                   readonly>
                        </div>
                        @error('asignatura_id', 'update')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <input type="text"
                                   class="form-control @error('name', 'update') is-invalid @enderror"
                                   name="name"
                                   id="name_editar"
                                   value="{{ old('name') }}"
                                   placeholder="Ej: Matemáticas Avanzadas"
                                   required>
                        </div>
                        @error('name', 'update')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="docentes_editar" class="form-label">Docentes <span class="text-danger">*</span></label>
                        <div class="search-container mb-2">
                            <input type="text"
                                   class="form-control"
                                   id="buscarDocenteEditar"
                                   placeholder="Buscar docente..."
                                   onkeyup="filtrarOpciones('docentes_editar', this.value, true)"
                                   autocomplete="off">
                            <div class="no-results" id="docentes_editarNoResults" style="display: none;">
                                <small class="text-muted">No se encontraron resultados</small>
                            </div>
                        </div>
                        <select name="docentes[]"
                                id="docentes_editar"
                                class="form-select @error('docentes', 'update') is-invalid @enderror"
                                multiple
                                size="5"
                                required
                                style="display: none;">
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->cedula_doc }}"
                                        data-busqueda="{{ strtolower($docente->name) }} {{ $docente->cedula_doc }}"
                                        {{ (is_array(old('docentes')) && in_array($docente->cedula_doc, old('docentes'))) ? 'selected' : '' }}>
                                    {{ $docente->name }} - {{ $docente->cedula_doc }}
                                </option>
                            @endforeach
                        </select>
                        @error('docentes', 'update')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                         @error('docentes.*', 'update')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="secciones_editar" class="form-label">Secciones <span class="text-danger">*</span></label>
                        <div class="search-container mb-2">
                            <input type="text"
                                   class="form-control"
                                   id="buscarSeccionEditar"
                                   placeholder="Buscar sección..."
                                   onkeyup="filtrarOpciones('secciones_editar', this.value, true)"
                                   autocomplete="off">
                            <div class="no-results" id="secciones_editarNoResults" style="display: none;">
                                <small class="text-muted">No se encontraron resultados</small>
                            </div>
                        </div>
                        <select name="secciones[]"
                                id="secciones_editar"
                                class="form-select @error('secciones', 'update') is-invalid @enderror"
                                multiple
                                size="5"
                                required
                                style="display: none;">
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->codigo_seccion }}"
                                        data-busqueda="{{ strtolower($seccion->codigo_seccion) }} {{ strtolower($seccion->carrera->name) }} semestre{{ $seccion->semestre->numero }} {{ strtolower($seccion->turno->nombre) }}"
                                        {{ (is_array(old('secciones')) && in_array($seccion->codigo_seccion, old('secciones'))) ? 'selected' : '' }}>
                                    {{ $seccion->codigo_seccion }} -
                                    {{ $seccion->carrera->name }} -
                                    Sem. {{ $seccion->semestre->numero }} -
                                    {{ $seccion->turno->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('secciones', 'update')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('secciones.*', 'update')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Carga Horaria <span class="text-danger">*</span></label>
                        <div id="cargaHorariaContainerEditar">
                            </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="agregarBloqueCargaEditar()">
                            <i class="fas fa-plus me-1"></i> Agregar Tipo
                        </button>
                        @error('carga_horaria', 'update') <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="cargaHorariaEditarErrorClient" class="text-danger mt-1" style="font-size: .875em;"></div> </div>

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
                                    <div class="invalid-feedback d-block" data-error-template-tipo="__INDEX__"></div>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select horas-select" name="carga_horaria[__INDEX__][horas_academicas]" required>
                                        <option value="">Horas...</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}">{{ $i }}h</option>
                                        @endfor
                                    </select>
                                     <div class="invalid-feedback d-block" data-error-template-horas="__INDEX__"></div>
                                </div>
                                <div class="col-md-2 d-flex align-items-center"> <button type="button" class="btn btn-danger btn-block w-100" onclick="eliminarBloqueCarga(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitButtonEditar">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Asegúrate de que las funciones eliminarBloqueCarga y filtrarOpciones estén definidas globalmente
    // o cópialas/importalas aquí si es necesario. Por ejemplo:
    /*
    function eliminarBloqueCarga(btn) { ... } // Tu función existente
    function filtrarOpciones(selectId, busqueda, mantenerSeleccionadasVisibles = false) { ... } // Tu función existente
    */

    let bloqueIndexEditar = 0; // Índice para los bloques de carga horaria en edición

    function agregarBloqueCargaEditar(tipo = '', horas = '') {
        const container = document.getElementById('cargaHorariaContainerEditar');
        const template = document.getElementById('cargaHorariaTemplateEditar').innerHTML;
        const html = template.replace(/__INDEX__/g, bloqueIndexEditar)
                             .replace(/data-error-template-tipo="__INDEX__"/g, `data-error-tipo="${bloqueIndexEditar}"`)
                             .replace(/data-error-template-horas="__INDEX__"/g, `data-error-horas="${bloqueIndexEditar}"`);

        container.insertAdjacentHTML('beforeend', html);
        const nuevoBloque = container.lastElementChild;
        if (tipo) nuevoBloque.querySelector(`.tipo-select`).value = tipo;
        if (horas) nuevoBloque.querySelector(`.horas-select`).value = horas;

        bloqueIndexEditar++;
        // No mostrar SweetAlert aquí si se llama durante la carga inicial o errores de validación
    }


    document.addEventListener('DOMContentLoaded', function () {
        const editarModalEl = document.getElementById('editarModal');
        if (!editarModalEl) return;
        const editarModal = new bootstrap.Modal(editarModalEl); // Instancia de Bootstrap Modal

        const formEditar = document.getElementById('formEditarAsignatura');
        const cargaHorariaContainerEditar = document.getElementById('cargaHorariaContainerEditar');

        editarModalEl.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            formEditar.action = `{{ url('asignaturas') }}/${button.dataset.asignaturaId}`; // Ajusta 'asignaturaId'

            document.getElementById('asignatura_id_editar').value = button.dataset.asignaturaId;
            document.getElementById('name_editar').value = button.dataset.name;

            // Resetear campos de búsqueda y selectores
            document.getElementById('buscarDocenteEditar').value = '';
            const docenteSelect = document.getElementById('docentes_editar');
            Array.from(docenteSelect.options).forEach(opt => opt.selected = false);
            if (button.dataset.docentes) {
                try {
                    JSON.parse(button.dataset.docentes).forEach(id => {
                        const opt = docenteSelect.querySelector(`option[value="${id}"]`);
                        if (opt) opt.selected = true;
                    });
                } catch (e) { console.error("Error parsing docentes:", e); }
            }
            filtrarOpciones('docentes_editar', '', true); // true para mantener seleccionadas visibles

            document.getElementById('buscarSeccionEditar').value = '';
            const seccionSelect = document.getElementById('secciones_editar');
            Array.from(seccionSelect.options).forEach(opt => opt.selected = false);
            if (button.dataset.secciones) {
                try {
                    JSON.parse(button.dataset.secciones).forEach(id => {
                        const opt = seccionSelect.querySelector(`option[value="${id}"]`);
                        if (opt) opt.selected = true;
                    });
                } catch (e) { console.error("Error parsing secciones:", e); }
            }
            filtrarOpciones('secciones_editar', '', true);

            // Cargar carga horaria
            cargaHorariaContainerEditar.innerHTML = '';
            bloqueIndexEditar = 0;
            let cargasHorariasData = [];
            if (button.dataset.cargaHoraria) {
                try {
                    cargasHorariasData = JSON.parse(button.dataset.cargaHoraria);
                } catch (e) { console.error("Error parsing carga_horaria:", e); }
            }

            if (cargasHorariasData && cargasHorariasData.length > 0) {
                cargasHorariasData.forEach(carga => agregarBloqueCargaEditar(carga.tipo, carga.horas_academicas));
            } else {
                agregarBloqueCargaEditar(); // Agregar un bloque vacío si no hay datos
            }
        });

        editarModalEl.addEventListener('hidden.bs.modal', function () {
            formEditar.reset(); // Resetea el formulario
            cargaHorariaContainerEditar.innerHTML = ''; // Limpia bloques
            bloqueIndexEditar = 0;
            // Limpiar inputs de búsqueda y re-filtrar para mostrar todo
            document.getElementById('buscarDocenteEditar').value = '';
            filtrarOpciones('docentes_editar', '', true);
            document.getElementById('buscarSeccionEditar').value = '';
            filtrarOpciones('secciones_editar', '', true);

            // Limpiar clases 'is-invalid' y mensajes de error de JS
            formEditar.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.getElementById('cargaHorariaEditarErrorClient').textContent = '';
            cargaHorariaContainerEditar.querySelectorAll('[data-error-tipo], [data-error-horas]').forEach(el => el.textContent = '');

            // Si tienes errores de Laravel en el modal, también querrás limpiarlos,
            // aunque usualmente se limpian en la recarga de página.
        });

        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitButton = document.getElementById('submitButtonEditar');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';

            // Limpiar errores previos de JS
            formEditar.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.getElementById('cargaHorariaEditarErrorClient').textContent = '';
            cargaHorariaContainerEditar.querySelectorAll('[data-error-tipo], [data-error-horas]').forEach(el => el.textContent = '');


            let valid = true;
            const errorMessages = [];

            if (!document.getElementById('name_editar').value.trim()) {
                valid = false; errorMessages.push('El nombre es requerido.');
                document.getElementById('name_editar').classList.add('is-invalid');
            }
            if (document.getElementById('docentes_editar').selectedOptions.length === 0) {
                valid = false; errorMessages.push('Seleccione al menos un docente.');
                 document.getElementById('docentes_editar').classList.add('is-invalid');
            }
            if (document.getElementById('secciones_editar').selectedOptions.length === 0) {
                valid = false; errorMessages.push('Seleccione al menos una sección.');
                document.getElementById('secciones_editar').classList.add('is-invalid');
            }

            const bloquesCarga = cargaHorariaContainerEditar.querySelectorAll('.carga-horaria-block');
            if (bloquesCarga.length === 0) {
                valid = false; errorMessages.push('Debe agregar al menos un bloque de carga horaria.');
                document.getElementById('cargaHorariaEditarErrorClient').textContent = 'Agregue al menos un bloque.';
            } else {
                let cargaHorariaCompleta = true;
                bloquesCarga.forEach((bloque, index) => {
                    const tipoSel = bloque.querySelector('.tipo-select');
                    const horasSel = bloque.querySelector('.horas-select');
                    if (!tipoSel.value) { valid = false; cargaHorariaCompleta = false; tipoSel.classList.add('is-invalid'); }
                    if (!horasSel.value) { valid = false; cargaHorariaCompleta = false; horasSel.classList.add('is-invalid'); }
                });
                if (!cargaHorariaCompleta) errorMessages.push('Complete todos los campos de carga horaria (tipo y horas).');
            }

            if (!valid) {
                Swal.fire({ icon: 'error', title: 'Error en el Formulario', html: [...new Set(errorMessages)].join('<br>') });
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                return;
            }
            formEditar.submit();
        });

        // Manejo de errores de validación del servidor para el modal de edición
        @if ($errors->any() && session('open_edit_modal_id') == old('asignatura_id') && $errors->setBag('update')) // Asegúrate que el error bag sea 'update'
            $(document).ready(function() {
                // Re-poblar datos básicos con old()
                $('#asignatura_id_editar').val("{{ old('asignatura_id') }}");
                $('#name_editar').val("{{ old('name') }}");

                // Re-seleccionar docentes
                const docenteSelect = document.getElementById('docentes_editar');
                Array.from(docenteSelect.options).forEach(opt => opt.selected = false);
                @if(is_array(old('docentes')))
                    @foreach(old('docentes') as $docenteId)
                        const optDoc = docenteSelect.querySelector(`option[value="{{ $docenteId }}"]`);
                        if (optDoc) optDoc.selected = true;
                    @endforeach
                @endif
                filtrarOpciones('docentes_editar', '', true);


                // Re-seleccionar secciones
                const seccionSelect = document.getElementById('secciones_editar');
                Array.from(seccionSelect.options).forEach(opt => opt.selected = false);
                 @if(is_array(old('secciones')))
                    @foreach(old('secciones') as $seccionId)
                        const optSec = seccionSelect.querySelector(`option[value="{{ $seccionId }}"]`);
                        if (optSec) optSec.selected = true;
                    @endforeach
                @endif
                filtrarOpciones('secciones_editar', '', true);


                // Re-poblar carga horaria con old() y mostrar errores
                cargaHorariaContainerEditar.innerHTML = '';
                bloqueIndexEditar = 0;
                @if(is_array(old('carga_horaria')))
                    @foreach(old('carga_horaria') as $index => $cargaData)
                        agregarBloqueCargaEditar("{{ $cargaData['tipo'] ?? '' }}", "{{ $cargaData['horas_academicas'] ?? '' }}");
                        const ultimoBloque = cargaHorariaContainerEditar.lastElementChild;
                        @if($errors->update->has("carga_horaria.$index.tipo"))
                            ultimoBloque.querySelector('.tipo-select').classList.add('is-invalid');
                            $(ultimoBloque.querySelector('[data-error-tipo="{{$index}}"]')).text("{{ $errors->update->first("carga_horaria.$index.tipo") }}");
                        @endif
                        @if($errors->update->has("carga_horaria.$index.horas_academicas"))
                            ultimoBloque.querySelector('.horas-select').classList.add('is-invalid');
                             $(ultimoBloque.querySelector('[data-error-horas="{{$index}}"]')).text("{{ $errors->update->first("carga_horaria.$index.horas_academicas") }}");
                        @endif
                    @endforeach
                @else
                    agregarBloqueCargaEditar(); // Agrega uno vacío si no hay old data
                @endif

                // Finalmente, abrir el modal
                const modalTriggerButton = document.querySelector(`button[data-bs-target="#editarModal"][data-asignatura-id="{{ old('asignatura_id') }}"]`);
                if (modalTriggerButton) { // Si existe el botón (para tomar otros data attributes si fuera necesario)
                     editarModal.show(); // Abre el modal
                } else { // Fallback si no se encuentra el botón pero sí los errores y el ID
                    $('#editarModal').modal('show');
                }
            });
        @endif
    });
</script>