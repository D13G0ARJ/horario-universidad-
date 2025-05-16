@extends('layouts.admin')

@section('style')
<style>
    #mensaje-inicial {
        transition: all 0.3s ease;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    #mensaje-inicial h4 {
        font-weight: 300;
        letter-spacing: 0.5px;
    }
    /* Estilos para selectores múltiples y carga horaria (pueden estar en tu CSS global o aquí) */
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
        z-index: 1056; /* Encima del modal */
    }
    select[multiple] {
        min-height: 120px; /* Altura mínima para selectores múltiples */
        border-radius: 0.25rem;
        width: 100%;
        transition: height 0.3s ease;
    }
    select[multiple] option {
        padding: 0.5rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
    }
    select[multiple] option:checked,
    select[multiple] option[selected] {
        background-color: #e9ecef !important;
        font-weight: 500;
    }
    select[multiple] option:hover {
        background-color: #f1f3f5 !important;
    }
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
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback.d-block {
        display: block !important;
    }
    .btn-danger.btn-block {
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-book me-2"></i>Listado de Asignaturas
            </h3>
        </div>
    </div>

    <div class="modal fade" id="modalNoResultados" tabindex="-1" aria-labelledby="modalNoResultadosLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalNoResultadosLabel">
                        <i class="fas fa-exclamation-circle me-2"></i>Sin resultados
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    No se encontraron registros para los datos seleccionados.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <label for="carrera" class="form-label">Carrera:</label>
            <select id="carrera" name="carrera_id" class="form-select form-select-lg" required>
                <option value="">Seleccione...</option>
                @foreach($carreras as $carrera)
                    <option value="{{ $carrera->carrera_id }}">{{ $carrera->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="turno" class="form-label">Turno:</label>
            <select id="turno" name="turno_id" class="form-select form-select-lg" required>
                <option value="">Seleccione...</option>
                 @if(isset($turnos) && $turnos->count() > 0)
                    @foreach($turnos as $turno_item) {{-- Renombrada variable del bucle --}}
                        <option value="{{ $turno_item->id_turno }}">{{ $turno_item->nombre }}</option>
                    @endforeach
                @else
                    <option value="" disabled>No hay turnos cargados</option>
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <label for="semestre" class="form-label">Semestre:</label>
            <select id="semestre" name="semestre_id" class="form-select form-select-lg" required disabled>
                <option value="">Seleccione turno</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button id="filtrar-datos" class="btn btn-primary w-100">
                <i class="fas fa-search me-2"></i>Buscar
            </button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button id="reset-filtros" class="btn btn-outline-secondary w-100">
                <i class="fas fa-broom me-2"></i>Limpiar
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>Asignaturas Registradas
                    </h4>
                    <button type="button" class="btn btn-success ms-auto text-dark" data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="fas fa-plus me-1"></i>Nueva Asignatura
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-asignaturas" class="table table-bordered table-hover" style="width:100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center;">N°</th>
                                    <th style="text-align: center;">Código</th>
                                    <th style="text-align: center;">Nombre</th>
                                    <th style="text-align: center;">Secciones (Códigos)</th>
                                    <th style="text-align: center;">Docentes</th>
                                    <th style="text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                    <div id="mensaje-inicial" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Utilice los filtros para visualizar las asignaturas</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($docentes) && isset($secciones))
    @include('modals.asignaturas.create', [
        'docentes' => $docentes,
        'secciones' => $secciones
    ])
    @include('modals.asignaturas.edit', [
        'docentes' => $docentes,
        'secciones' => $secciones
    ])
@else
    <div class="alert alert-warning" role="alert">
      <strong>Advertencia:</strong> Datos para modales (docentes/secciones) no fueron cargados correctamente. Algunas funcionalidades podrían no estar disponibles.
    </div>
@endif

@include('modals.asignaturas.show')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Asegúrate que jQuery y DataTables estén cargados, preferiblemente en tu layout principal o antes de este script --}}
{{-- Ejemplo:
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
--}}

<script>
// Funciones globales reutilizables para modales (CREAR Y EDITAR)
function eliminarBloqueCarga(btn) {
    const bloque = btn.closest('.carga-horaria-block');
    if (!bloque) return;
    const container = bloque.parentElement;
    if (!container) return;
    const bloquesRestantes = container.querySelectorAll('.carga-horaria-block').length;

    if (bloquesRestantes <= 1) {
        Swal.fire('Atención', 'Debe mantener al menos un bloque de carga horaria.', 'warning');
        return;
    }
    bloque.remove();
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: 'Bloque eliminado',
        showConfirmButton: false,
        timer: 1500
    });
}

function filtrarOpciones(selectId, busqueda, mantenerSeleccionadasVisibles = false) {
    const select = document.getElementById(selectId);
    const noResultsDiv = document.getElementById(`${selectId}NoResults`);
    if (!select) return;

    const opciones = Array.from(select.options);
    let resultadosVisibles = 0;
    busqueda = busqueda.toLowerCase().trim();

    opciones.forEach(option => {
        if (option.value === "") { // Omitir la opción "Seleccione..."
            return;
        }
        const textoBusqueda = (option.dataset.busqueda || option.text).toLowerCase();
        const coincide = textoBusqueda.includes(busqueda);

        if (coincide) {
            option.style.display = 'block';
            resultadosVisibles++;
        } else {
            option.style.display = (mantenerSeleccionadasVisibles && option.selected) ? 'block' : 'none';
            if (mantenerSeleccionadasVisibles && option.selected) resultadosVisibles++;
        }
    });

    select.style.display = 'block';
    if (noResultsDiv) {
        noResultsDiv.style.display = (resultadosVisibles === 0 && busqueda !== '') ? 'block' : 'none';
    }
    select.size = Math.max(1, Math.min(resultadosVisibles || 1, 5)); // Asegurar al menos 1 si hay resultados, o 1 si no hay búsqueda
}
</script>

<script>
// Script para la lógica de la página de Asignaturas (filtros, DataTables)
document.addEventListener('DOMContentLoaded', function() {
    const turnoSelect = document.getElementById('turno');
    const semestreSelect = document.getElementById('semestre');
    const mensajeInicial = document.getElementById('mensaje-inicial');
    const tablaAsignaturasEl = document.getElementById('tabla-asignaturas');

    if (tablaAsignaturasEl) $(tablaAsignaturasEl).hide();
    if (mensajeInicial) $(mensajeInicial).show();

    turnoSelect.addEventListener('change', function() {
        const turnoId = this.value;
        semestreSelect.innerHTML = '<option value="">Cargando...</option>'; // Feedback visual
        semestreSelect.disabled = true;

        if (!turnoId) {
            semestreSelect.innerHTML = '<option value="">Seleccione turno</option>';
            return;
        }
        
        fetch(`{{ url('/api/semestres-por-turno') }}/${turnoId}`)
            .then(response => {
                if (!response.ok) throw new Error(`Error HTTP ${response.status}: ${response.statusText}`);
                return response.json();
            })
            .then(data => {
                semestreSelect.innerHTML = '<option value="">Seleccione semestre...</option>';
                if (data && data.length > 0) {
                    data.forEach(semestre => {
                        const option = new Option(`Semestre ${semestre.numero || semestre.nombre || semestre.id_semestre || semestre.id}`, semestre.id_semestre || semestre.id);
                        semestreSelect.add(option);
                    });
                    semestreSelect.disabled = false;
                } else {
                     semestreSelect.innerHTML = '<option value="">No hay semestres para este turno</option>';
                }
            })
            .catch(error => {
                console.error('Error cargando semestres:', error);
                semestreSelect.innerHTML = '<option value="">Error al cargar</option>';
                Swal.fire('Error', `No se pudieron cargar los semestres: ${error.message}`, 'error');
            });
    });

    const table = $("#tabla-asignaturas").DataTable({
        pageLength: 10,
        responsive: true,
        autoWidth: false,
        language: {
            emptyTable: "No hay asignaturas para los filtros seleccionados.",
            info: "Mostrando _START_ a _END_ de _TOTAL_ asignaturas",
            infoEmpty: "Mostrando 0 asignaturas",
            infoFiltered: "(filtradas de _MAX_ registros totales)",
            lengthMenu: "Mostrar _MENU_ registros",
            search: "Buscar en tabla:",
            zeroRecords: "No se encontraron asignaturas que coincidan con la búsqueda",
            paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" }
        },
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + 
             "<'row'<'col-sm-12'tr>>" + 
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
             "<'row'<'col-sm-12 mt-2'B>>", // Posición de botones
        columns: [
            { data: '0', className: 'text-center' },
            { data: '1', className: 'text-center' },
            { data: '2' },
            { data: '3', className: 'text-center', render: function(data) { return data || '<span class="badge bg-secondary">N/A</span>'; }},
            { data: '4', className: 'text-center', render: function(data) { return data || '<span class="badge bg-secondary">N/A</span>'; }},
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    const asignaturaId = row['1'];
                    const name = row['2'];
                    // Asegurarse que los _data existen en el objeto row devuelto por el AJAX
                    const docentesJson = JSON.stringify(row.docentes_data || []);
                    const seccionesJson = JSON.stringify(row.secciones_data || []);
                    const cargaHorariaJson = JSON.stringify(row.carga_horaria_data || []);
                    const seccionesDetalleJson = JSON.stringify(row.secciones_detalle_data || []);

                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm btn-ver"
                                    data-bs-toggle="modal" data-bs-target="#mostrarModal"
                                    data-asignatura-id="${asignaturaId}"
                                    data-name="${encodeURIComponent(name)}"
                                    data-docentes='${docentesJson}'
                                    data-secciones='${seccionesJson}'
                                    data-secciones-detalle='${seccionesDetalleJson}'
                                    data-carga-horaria='${cargaHorariaJson}'
                                    title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-sm btn-editar"
                                    data-bs-toggle="modal" data-bs-target="#editarModal"
                                    data-asignatura-id="${asignaturaId}"
                                    data-name="${encodeURIComponent(name)}"
                                    data-docentes='${docentesJson}'
                                    data-secciones='${seccionesJson}'
                                    data-carga-horaria='${cargaHorariaJson}'
                                    title="Editar Asignatura">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar"
                                    data-id="${asignaturaId}" title="Eliminar Asignatura">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                }
            }
        ],
        order: [[1, 'asc']],
        buttons: [
            { extend: 'print', text: '<i class="fas fa-print me-1"></i>Imprimir', exportOptions: { columns: [0, 1, 2, 3, 4] }, className: 'btn btn-outline-secondary btn-sm' },
            { extend: 'pdf', text: '<i class="fas fa-file-pdf me-1"></i>PDF', exportOptions: { columns: [0, 1, 2, 3, 4] }, className: 'btn btn-outline-danger btn-sm' },
            { extend: 'excel', text: '<i class="fas fa-file-excel me-1"></i>Excel', exportOptions: { columns: [0, 1, 2, 3, 4] }, className: 'btn btn-outline-success btn-sm' }
        ]
    });

    @if(session('alert'))
        Swal.fire({
            icon: "{{ session('alert.icon', 'info') }}",
            title: "{{ session('alert.title', 'Notificación') }}",
            text: "{{ session('alert.text', session('alert.message', '')) }}",
            timer: parseInt("{{ session('alert.timer', 3000) }}"),
            showConfirmButton: {{ session('alert.showConfirmButton', 'false') === 'true' ? 'true' : 'false' }}
        });
    @endif

    function cargarDatos(idCarrera, idTurno, idSemestre) {
        Swal.fire({ title: 'Cargando Asignaturas...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: "{{ route('asignatura.filtrar') }}",
            method: 'GET',
            data: { carrera_id: idCarrera, id_turno: idTurno, id_semestre: idSemestre, _token: "{{ csrf_token() }}" },
            success: function(response) {
                Swal.close();
                table.clear();
                if (response && Array.isArray(response) && response.length > 0) {
                    table.rows.add(response).draw();
                    $(tablaAsignaturasEl).fadeIn();
                    $(mensajeInicial).hide();
                } else {
                    table.draw(); // Para mostrar el mensaje de "emptyTable" o "zeroRecords"
                    $('#modalNoResultados').modal('show');
                    $(tablaAsignaturasEl).hide(); // Opcional: podrías mostrar la tabla vacía
                    $(mensajeInicial).fadeIn();
                }
            },
            error: function(xhr) {
                Swal.close();
                console.error("Error AJAX en cargarDatos:", xhr.responseText);
                Swal.fire('Error Inesperado', 'No se pudieron cargar las asignaturas. Revise la consola para más detalles.', 'error');
                $(tablaAsignaturasEl).hide();
                $(mensajeInicial).fadeIn();
            }
        });
    }

    $('#filtrar-datos').click(function() {
        const idCarrera = $('#carrera').val();
        const idTurno = $('#turno').val();
        const idSemestre = $('#semestre').val();
        if (!idCarrera || !idTurno || !idSemestre) {
            Swal.fire('Campos Requeridos', 'Por favor, seleccione Carrera, Turno y Semestre.', 'warning');
            return;
        }
        cargarDatos(idCarrera, idTurno, idSemestre);
    });

    $('#reset-filtros').click(function() {
        $('#carrera').val('');
        $('#turno').val('');
        $('#semestre').val('').prop('disabled', true).html('<option value="">Seleccione turno</option>');
        table.clear().draw();
        $(tablaAsignaturasEl).hide();
        $(mensajeInicial).fadeIn(300);
    });

    $('#tabla-asignaturas tbody').on('click', '.btn-eliminar', function() {
        const asignaturaId = $(this).data('id');
        Swal.fire({
            title: '¿Confirmar Eliminación?',
            text: `La asignatura con código "${asignaturaId}" será eliminada permanentemente.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = `{{ url('/asignaturas') }}/${asignaturaId}`;
                deleteForm.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(deleteForm);
                deleteForm.submit();
            }
        });
    });
});
</script>

{{-- SCRIPT DE MODALES (CREACIÓN Y EDICIÓN) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Lógica para MODAL DE CREACIÓN ---
    const registroModalEl = document.getElementById('registroModal');
    if (registroModalEl) {
        let bloqueIndexCrear = 0;
        let initialLoadCrear = true;
        const cargaHorariaContainerCrear = document.getElementById('cargaHorariaContainer');
        const cargaHorariaTemplateCrear = document.getElementById('cargaHorariaTemplate');

        function agregarBloqueCargaCrearFn(showToast = true) {
            if (!cargaHorariaContainerCrear || !cargaHorariaTemplateCrear) return;
            const html = cargaHorariaTemplateCrear.innerHTML.replace(/__INDEX__/g, bloqueIndexCrear);
            cargaHorariaContainerCrear.insertAdjacentHTML('beforeend', html);
            bloqueIndexCrear++;
            if (showToast && !initialLoadCrear) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Nuevo bloque agregado', showConfirmButton: false, timer: 1500 });
            }
        }
        window.agregarBloqueCarga = agregarBloqueCargaCrearFn; // Exponer para el botón HTML

        if (!{{ is_array(old('carga_horaria')) && count(old('carga_horaria')) > 0 ? 'true' : 'false' }}) {
           if(cargaHorariaContainerCrear && cargaHorariaContainerCrear.children.length === 0) {
             agregarBloqueCargaCrearFn(false);
           }
        } else { // Repoblar desde old() si hay errores de validación en CREACIÓN
            @if(is_array(old('carga_horaria')))
                @foreach(old('carga_horaria') as $cargaDataOld)
                    agregarBloqueCargaCrearFn(false);
                    const ultimoBloqueCrear = cargaHorariaContainerCrear.lastElementChild;
                    if(ultimoBloqueCrear) {
                        const tipoSelectOld = ultimoBloqueCrear.querySelector('.tipo-select');
                        const horasSelectOld = ultimoBloqueCrear.querySelector('.horas-select');
                        if(tipoSelectOld) tipoSelectOld.value = "{{ $cargaDataOld['tipo'] ?? '' }}";
                        if(horasSelectOld) horasSelectOld.value = "{{ $cargaDataOld['horas_academicas'] ?? '' }}";
                    }
                @endforeach
            @endif
            bloqueIndexCrear = {{ is_array(old('carga_horaria')) ? count(old('carga_horaria')) : 0 }};
        }
        initialLoadCrear = false;

        const formAsignaturaCrear = document.getElementById('formAsignatura');
        if (formAsignaturaCrear) {
            formAsignaturaCrear.addEventListener('submit', function(e) {
                // Tu lógica de validación JS para el modal de creación
                // Ejemplo básico:
                let validCrear = true;
                const errorMessagesCrear = [];
                document.querySelectorAll('#registroModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));

                if (!formAsignaturaCrear.querySelector('#asignatura_id').value.trim()) { validCrear = false; errorMessagesCrear.push('Código de asignatura es requerido.'); formAsignaturaCrear.querySelector('#asignatura_id').classList.add('is-invalid');}
                if (!formAsignaturaCrear.querySelector('#name').value.trim()) { validCrear = false; errorMessagesCrear.push('Nombre de asignatura es requerido.'); formAsignaturaCrear.querySelector('#name').classList.add('is-invalid');}
                if (formAsignaturaCrear.querySelector('#docentes').selectedOptions.length === 0) { validCrear = false; errorMessagesCrear.push('Seleccione al menos un docente.'); formAsignaturaCrear.querySelector('#docentes').classList.add('is-invalid');}
                if (formAsignaturaCrear.querySelector('#secciones').selectedOptions.length === 0) { validCrear = false; errorMessagesCrear.push('Seleccione al menos una sección.'); formAsignaturaCrear.querySelector('#secciones').classList.add('is-invalid');}
                
                const bloquesCrear = cargaHorariaContainerCrear.querySelectorAll('.carga-horaria-block');
                if (bloquesCrear.length === 0) {
                    validCrear = false; errorMessagesCrear.push('Debe agregar al menos un bloque de carga horaria.');
                } else {
                    let cargaCompletaCrear = true;
                    bloquesCrear.forEach(b => {
                        if(!b.querySelector('.tipo-select').value) { b.querySelector('.tipo-select').classList.add('is-invalid'); cargaCompletaCrear = false;}
                        if(!b.querySelector('.horas-select').value) { b.querySelector('.horas-select').classList.add('is-invalid'); cargaCompletaCrear = false;}
                    });
                    if(!cargaCompletaCrear) { validCrear = false; errorMessagesCrear.push('Complete tipo y horas en cada bloque de carga horaria.');}
                }

                if(!validCrear){
                    e.preventDefault();
                    Swal.fire('Error en Formulario (Creación)', [...new Set(errorMessagesCrear)].join('<br>'), 'error');
                } else {
                    formAsignaturaCrear.querySelector('button[type="submit"]').disabled = true;
                    formAsignaturaCrear.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registrando...';
                }
            });
        }
        @if ($errors->any() && (session('open_modal_create_asignatura') || $errors->hasBag('default')) && old('form_type') === 'create_asignatura')
            $(document).ready(function() { $('#registroModal').modal('show'); });
        @endif
    }

    // --- Lógica para MODAL DE EDICIÓN ---
    const editarModalEl = document.getElementById('editarModal');
    if (editarModalEl) {
        let bloqueIndexEditarGlobal = 0; // Renombrado para evitar conflicto
        const cargaHorariaContainerEditar = document.getElementById('cargaHorariaContainerEditar');
        const cargaHorariaTemplateEditar = document.getElementById('cargaHorariaTemplateEditar');
        const formEditarAsignatura = document.getElementById('formEditarAsignatura');

        window.agregarBloqueCargaEditarFn = function(tipo = '', horas = '', showToast = true) { // Renombrado
            if (!cargaHorariaContainerEditar || !cargaHorariaTemplateEditar) return;
            const html = cargaHorariaTemplateEditar.innerHTML
                .replace(/__INDEX__/g, bloqueIndexEditarGlobal)
                .replace(/data-error-template-tipo="__INDEX__"/g, `data-error-tipo="${bloqueIndexEditarGlobal}"`)
                .replace(/data-error-template-horas="__INDEX__"/g, `data-error-horas="${bloqueIndexEditarGlobal}"`);
            cargaHorariaContainerEditar.insertAdjacentHTML('beforeend', html);
            const nuevoBloque = cargaHorariaContainerEditar.lastElementChild;
            if (tipo && nuevoBloque.querySelector(`.tipo-select`)) nuevoBloque.querySelector(`.tipo-select`).value = tipo;
            if (horas && nuevoBloque.querySelector(`.horas-select`)) nuevoBloque.querySelector(`.horas-select`).value = horas;
            bloqueIndexEditarGlobal++;
            if (showToast) {
                 Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Nuevo bloque agregado', showConfirmButton: false, timer: 1500 });
            }
        }
        // Exponer para el botón HTML del modal de edición si es necesario (aunque ya se llama internamente)
         window.agregarBloqueCargaEditar = window.agregarBloqueCargaEditarFn;


        editarModalEl.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (!button || !button.dataset) return;

            formEditarAsignatura.action = `{{ url('asignaturas') }}/${button.dataset.asignaturaId}`;
            formEditarAsignatura.querySelector('#asignatura_id_editar').value = button.dataset.asignaturaId;
            formEditarAsignatura.querySelector('#name_editar').value = decodeURIComponent(button.dataset.name || '');

            ['docentes_editar', 'secciones_editar'].forEach(selectId => {
                const select = formEditarAsignatura.querySelector(`#${selectId}`);
                if (!select) return;
                const dataKey = selectId.replace('_editar', '');
                let selectedValues = [];
                try { selectedValues = JSON.parse(button.dataset[dataKey] || '[]'); } catch (e) { console.error(`Error parsing ${dataKey}:`, e); }
                Array.from(select.options).forEach(opt => opt.selected = selectedValues.includes(opt.value));
                const searchInput = document.getElementById(`buscar${selectId.charAt(0).toUpperCase() + selectId.slice(1).replace('_editar', '')}Editar`);
                if(searchInput) searchInput.value = '';
                filtrarOpciones(selectId, '', true);
            });

            if(cargaHorariaContainerEditar) cargaHorariaContainerEditar.innerHTML = '';
            bloqueIndexEditarGlobal = 0;
            let cargasHorariasData = [];
            try { cargasHorariasData = JSON.parse(button.dataset.cargaHoraria || '[]');} catch (e) { console.error("Error parsing carga_horaria data:", e); }
            if (cargasHorariasData.length > 0) {
                cargasHorariasData.forEach(carga => window.agregarBloqueCargaEditarFn(carga.tipo, carga.horas_academicas, false));
            } else {
                window.agregarBloqueCargaEditarFn('', '', false);
            }
        });

        editarModalEl.addEventListener('hidden.bs.modal', function () {
            formEditarAsignatura.reset();
            if(cargaHorariaContainerEditar) cargaHorariaContainerEditar.innerHTML = '';
            bloqueIndexEditarGlobal = 0;
            ['docentes_editar', 'secciones_editar'].forEach(selectId => {
                const select = formEditarAsignatura.querySelector(`#${selectId}`);
                if (select) {
                    Array.from(select.options).forEach(opt => opt.selected = false);
                    const searchInput = document.getElementById(`buscar${selectId.charAt(0).toUpperCase() + selectId.slice(1).replace('_editar','') }Editar`);
                    if(searchInput) searchInput.value = '';
                    filtrarOpciones(selectId, '', true);
                }
            });
            formEditarAsignatura.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            const errorDivClient = formEditarAsignatura.querySelector('#cargaHorariaEditarErrorClient');
            if(errorDivClient) errorDivClient.textContent = '';
            if(cargaHorariaContainerEditar) cargaHorariaContainerEditar.querySelectorAll('[data-error-tipo], [data-error-horas]').forEach(el => el.textContent = '');
        });

        if (formEditarAsignatura) {
            formEditarAsignatura.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitButton = formEditarAsignatura.querySelector('#submitButtonEditar');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                formEditarAsignatura.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                const errorDivClient = formEditarAsignatura.querySelector('#cargaHorariaEditarErrorClient');
                if(errorDivClient) errorDivClient.textContent = '';
                 if(cargaHorariaContainerEditar) cargaHorariaContainerEditar.querySelectorAll('[data-error-tipo], [data-error-horas]').forEach(el => el.textContent = '');

                let valid = true; const errorMessages = [];
                if (!formEditarAsignatura.querySelector('#name_editar').value.trim()) { valid = false; errorMessages.push('El nombre es requerido.'); formEditarAsignatura.querySelector('#name_editar').classList.add('is-invalid');}
                if (formEditarAsignatura.querySelector('#docentes_editar').selectedOptions.length === 0) { valid = false; errorMessages.push('Seleccione al menos un docente.'); formEditarAsignatura.querySelector('#docentes_editar').classList.add('is-invalid');}
                if (formEditarAsignatura.querySelector('#secciones_editar').selectedOptions.length === 0) { valid = false; errorMessages.push('Seleccione al menos una sección.'); formEditarAsignatura.querySelector('#secciones_editar').classList.add('is-invalid');}
                const bloquesCarga = cargaHorariaContainerEditar.querySelectorAll('.carga-horaria-block');
                if (bloquesCarga.length === 0) {
                    valid = false; errorMessages.push('Debe agregar al menos un bloque de carga horaria.');
                    if(errorDivClient) errorDivClient.textContent = 'Agregue al menos un bloque.';
                } else {
                    let cargaHorariaCompleta = true;
                    bloquesCarga.forEach((bloque) => {
                        const tipoSel = bloque.querySelector('.tipo-select'); const horasSel = bloque.querySelector('.horas-select');
                        if (!tipoSel.value) { valid = false; cargaHorariaCompleta = false; tipoSel.classList.add('is-invalid'); }
                        if (!horasSel.value) { valid = false; cargaHorariaCompleta = false; horasSel.classList.add('is-invalid'); }
                    });
                    if (!cargaHorariaCompleta) errorMessages.push('Complete todos los campos de carga horaria.');
                }
                if (!valid) {
                    Swal.fire({ icon: 'error', title: 'Error en Formulario (Edición)', html: [...new Set(errorMessages)].join('<br>') });
                    submitButton.disabled = false; submitButton.innerHTML = originalButtonText; return;
                }
                this.submit();
            });
        }

        @if ($errors->any() && session('open_edit_modal_id') && old('asignatura_id') == session('open_edit_modal_id') && ($errors->hasBag('update') || !$errors->default->isEmpty()) && old('form_type') === 'edit_asignatura')
        $(document).ready(function() {
            if (!$('#editarModal').hasClass('show')) {
                const fakeButtonForEdit = { dataset: {
                        asignaturaId: "{{ old('asignatura_id', session('open_edit_modal_id')) }}",
                        name: "{{ old('name', '') }}",
                        docentes: JSON.stringify(@json(old('docentes', []))),
                        secciones: JSON.stringify(@json(old('secciones', []))),
                        cargaHoraria: JSON.stringify(@json(old('carga_horaria', [])))
                }};
                $(editarModalEl).trigger(new $.Event('show.bs.modal', { relatedTarget: fakeButtonForEdit }));
                $('#editarModal').modal('show');
                setTimeout(function() {
                    @if(is_array(old('carga_horaria')))
                        @foreach(old('carga_horaria') as $index => $cargaData)
                            const bloqueError = cargaHorariaContainerEditar.children[{{ $index }}];
                            if(bloqueError) {
                                const errorBag = "{{ $errors->hasBag('update') ? 'update' : 'default' }}";
                                @if($errors->getBag($errorBag)->has("carga_horaria.$index.tipo"))
                                    bloqueError.querySelector('.tipo-select').classList.add('is-invalid');
                                    $(bloqueError.querySelector('[data-error-tipo="{{$index}}"]')).text("{{ $errors->getBag($errorBag)->first("carga_horaria.$index.tipo") }}");
                                @endif
                                @if($errors->getBag($errorBag)->has("carga_horaria.$index.horas_academicas"))
                                    bloqueError.querySelector('.horas-select').classList.add('is-invalid');
                                    $(bloqueError.querySelector('[data-error-horas="{{$index}}"]')).text("{{ $errors->getBag($errorBag)->first("carga_horaria.$index.horas_academicas") }}");
                                @endif
                            }
                        @endforeach
                    @endif
                }, 200);
            }
        });
        @endif
    }
     // Lógica para el modal de SHOW (mostrarModal)
    const mostrarModalEl = document.getElementById('mostrarModal');
    if (mostrarModalEl) {
        mostrarModalEl.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (!button || !button.dataset) return;

            // Poblar campos del modal de visualización
            // Ejemplo: document.getElementById('show_asignatura_id').textContent = button.dataset.asignaturaId;
            // document.getElementById('show_name').textContent = decodeURIComponent(button.dataset.name || '');
            
            // Docentes:
            const docentesList = document.getElementById('show_docentes_list'); // Asegúrate que este elemento exista en tu modal de show
            if (docentesList) {
                docentesList.innerHTML = ''; // Limpiar
                try {
                    const docentesData = JSON.parse(button.dataset.docentes || '[]');
                    if (docentesData.length > 0) {
                        // Necesitas tener los nombres de los docentes, no solo los IDs.
                        // El dataset.docentes idealmente debería ser un array de objetos [{id: '123', name: 'Prof. X'}]
                        // o buscar los nombres desde la lista completa de $docentes disponibles en la página.
                        // Por ahora, mostrará los IDs si solo tienes IDs.
                        // Para una mejor UX, tu AJAX debería devolver nombres en 'docentes_data' o pasar un array de objetos.
                        // Si 'row.docentes_data' ya tiene los nombres, mejor. Si no, esto es un placeholder:
                        docentesData.forEach(docId => {
                            const docenteOption = document.querySelector(`#docentes_editar option[value="${docId}"]`); // Intenta obtener nombre de select de edición
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.textContent = docenteOption ? docenteOption.textContent : `ID: ${docId}`;
                            docentesList.appendChild(li);
                        });
                    } else {
                        docentesList.innerHTML = '<li class="list-group-item">No asignados</li>';
                    }
                } catch (e) { console.error("Error parsing docentes for show modal:", e); docentesList.innerHTML = '<li class="list-group-item text-danger">Error al cargar</li>';}
            }

            // Secciones (Detalle):
            const seccionesList = document.getElementById('show_secciones_list'); // Asegúrate que este elemento exista
            if (seccionesList) {
                seccionesList.innerHTML = '';
                try {
                    const seccionesDetalleData = JSON.parse(button.dataset.seccionesDetalle || '[]');
                     if (seccionesDetalleData.length > 0) {
                        seccionesDetalleData.forEach(detalle => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.textContent = detalle;
                            seccionesList.appendChild(li);
                        });
                    } else {
                        seccionesList.innerHTML = '<li class="list-group-item">No asignadas</li>';
                    }
                } catch (e) { console.error("Error parsing secciones-detalle for show modal:", e); seccionesList.innerHTML = '<li class="list-group-item text-danger">Error al cargar</li>';}
            }

            // Carga Horaria:
            const cargaHorariaList = document.getElementById('show_carga_horaria_list'); // Asegúrate que este elemento exista
             if (cargaHorariaList) {
                cargaHorariaList.innerHTML = '';
                 try {
                    const cargaHorariaDataShow = JSON.parse(button.dataset.cargaHoraria || '[]');
                    if (cargaHorariaDataShow.length > 0) {
                        cargaHorariaDataShow.forEach(carga => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.textContent = `${carga.tipo.charAt(0).toUpperCase() + carga.tipo.slice(1)}: ${carga.horas_academicas}h`;
                            cargaHorariaList.appendChild(li);
                        });
                    } else {
                        cargaHorariaList.innerHTML = '<li class="list-group-item">No definida</li>';
                    }
                } catch (e) { console.error("Error parsing carga-horaria for show modal:", e); cargaHorariaList.innerHTML = '<li class="list-group-item text-danger">Error al cargar</li>';}
            }
        });
    }
});
</script>
@endpush