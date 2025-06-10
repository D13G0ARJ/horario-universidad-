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

    /* Agrega aquí cualquier estilo CSS adicional que necesites para la tabla o los botones */
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

    <div class="modal fade" id="modalNoResultados" tabindex="-1" role="dialog" aria-labelledby="modalNoResultadosLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalNoResultadosLabel">
                        <i class="fas fa-exclamation-circle me-2"></i>Sin resultados
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                    @foreach($turnos as $turno)
                        <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                    @endforeach
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
                <i class="fas fa-broom me-2"></i>Limpiar filtros
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
                    <a href="#" class="btn btn-success ms-auto text-dark"
                        data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="fas fa-plus me-1"></i>Nueva Asignatura
                    </a>
                </div>
                <div class="card-body">
                    <table id="tabla-asignaturas" class="table table-bordered table-hover" style="display:none;">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th style="text-align: center">Código</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Secciones</th>
                                <th style="text-align: center">Docentes</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                    <div id="mensaje-inicial" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Utilice los filtros para visualizar las asignaturas</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@isset($docentes) {{-- Verificar que la variable existe --}}
    @include('modals.asignaturas.create', [
        'docentes' => $docentes,
        'secciones' => $secciones
    ])
@endisset

@include('modals.asignaturas.show')
@include('modals.asignaturas.edit', [
        'docentes' => $docentes,
        'secciones' => $secciones
    ])

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const turnoSelect = document.getElementById('turno');
        const semestreSelect = document.getElementById('semestre');

        turnoSelect.addEventListener('change', function() {
            const turnoId = this.value;
            semestreSelect.innerHTML = '<option value="">Seleccione...</option>'; // Opción por defecto
            semestreSelect.disabled = true;
            
            if (!turnoId) {
                semestreSelect.innerHTML = '<option value="">Seleccione turno</option>'; // Mensaje si no hay turno
                return;
            }

            // Muestra "Cargando..."
            const loadingOption = new Option('Cargando semestres...', '');
            loadingOption.disabled = true;
            semestreSelect.add(loadingOption);

            fetch(`/api/semestres-por-turno/${turnoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar semestres. Estado: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    semestreSelect.innerHTML = '<option value="">Seleccione...</option>'; // Limpiar y añadir opción por defecto
                    if (data && data.length > 0) {
                        data.forEach(semestre => {
                            // Asegúrate que 'semestre.id' y 'semestre.numero' existen en la respuesta JSON
                            const option = new Option(`Semestre ${semestre.numero}`, semestre.id_semestre || semestre.id); // Usa el nombre correcto del campo ID
                            semestreSelect.add(option);
                        });
                    } else {
                        semestreSelect.innerHTML = '<option value="">No hay semestres</option>';
                    }
                    semestreSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error en fetch semestres:', error);
                    semestreSelect.innerHTML = '<option value="">Error al cargar</option>';
                    semestreSelect.disabled = false; // Habilitar para que el usuario pueda reintentar
                });
        });
    });
</script>

<script>
$(document).ready(function() {
    // Configuración DataTables
    const table = $("#tabla-asignaturas").DataTable({
        pageLength: 10,
        responsive: true,
        autoWidth: false,
        language: {
            emptyTable: "No hay asignaturas registradas para los filtros seleccionados.",
            info: "Mostrando _START_ a _END_ de _TOTAL_ asignaturas",
            infoEmpty: "Mostrando 0 asignaturas",
            infoFiltered: "(filtradas de _MAX_ registros totales)",
            search: "Buscar:",
            zeroRecords: "No se encontraron asignaturas con los criterios de búsqueda",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        columns: [
            { data: '0', className: 'text-center align-middle' }, // N° (ID PK)
            { data: '1', className: 'text-center align-middle' }, // Código Asignatura
            { data: '2', className: 'align-middle' },       // Nombre Asignatura
            {
                data: '3', // Primera Sección (para visualización en tabla)
                className: 'text-center align-middle',
                render: function(data, type, row) {
                    return data ?
                        `<span class="badge bg-primary"><i class="fas fa-layer-group me-1"></i>${data}</span>` :
                        '<span class="badge bg-secondary">N/A</span>';
                }
            },
            {
                data: '4', // Primer Docente (para visualización en tabla)
                className: 'text-center align-middle',
                render: function(data, type, row) {
                    return data ?
                        `<span class="badge bg-info text-dark"><i class="fas fa-user-tie me-1"></i>${data}</span>` :
                        '<span class="badge bg-secondary">N/A</span>';
                }
            },
            {
                data: null, // Columna de Acciones
                className: 'text-center align-middle',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    const asignaturaPkId = row['0'];      // ID Primario de la asignatura (para la URL de delete)
                    const asignaturaCodigo = row['1'];  // Código de la asignatura
                    const nombreAsignatura = row['2'];     // Nombre de la asignatura

                    // Serializar arrays/objetos para los data attributes, asegurando que existan
                    const docentesData = (row.docentes && Array.isArray(row.docentes)) ? JSON.stringify(row.docentes) : '[]';
                    const seccionesData = (row.secciones && Array.isArray(row.secciones)) ? JSON.stringify(row.secciones) : '[]';
                    
                    // Formatear carga_horaria a un array de objetos para el modal de show y edit
                    const cargaHorariaRaw = row.carga_horaria || {};
                    const cargaHorariaArray = Object.keys(cargaHorariaRaw).map(tipo => ({
                        tipo: tipo,
                        horas_academicas: cargaHorariaRaw[tipo]
                    }));
                    const cargaHorariaData = JSON.stringify(cargaHorariaArray);

                    return `
                        <div class="btn-group" role="group" aria-label="Acciones de asignatura">
                            <button type="button" class="btn btn-info btn-sm btn-ver"
                                title="Ver Detalles"
                                data-bs-toggle="modal"
                                data-bs-target="#mostrarModal"
                                data-asignatura_id="${asignaturaCodigo}"
                                data-name="${nombreAsignatura}"
                                data-docentes='${docentesData}'
                                data-secciones='${seccionesData}'
                                data-carga_horaria='${cargaHorariaData}'>
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <button type="button" class="btn btn-success btn-sm btn-editar"
                                title="Editar Asignatura"
                                data-bs-toggle="modal"
                                data-bs-target="#editarModal"
                                data-asignatura-id="${asignaturaCodigo}" 
                                data-asignatura-name="${nombreAsignatura}"
                                data-asignatura-docentes='${docentesData}'
                                data-asignatura-secciones='${seccionesData}'
                                data-asignatura-carga-horaria='${cargaHorariaData}'>
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            
                            {{-- Botón de Eliminar - Llama a la función JavaScript directamente --}}
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar"
                                title="Eliminar Asignatura"
                                onclick="confirmarEliminarAsignatura('${asignaturaCodigo}', '${nombreAsignatura}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                }
            }
        ],
        columnDefs: [
            { targets: '_all', className: 'align-middle' }, // Centrar verticalmente todas las celdas
            { targets: [0, 1, 3, 4, 5], className: 'text-center align-middle' }, // Asegurar centrado horizontal para estas columnas
            { targets: 5, orderable: false, searchable: false } // Columna de acciones
        ],
        order: [[1, 'asc']], // Ordenar por Código de asignatura (columna con data: '1')
        dom: 'Bfrtip', // Para los botones de exportación
        buttons: [
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i>Imprimir',
                exportOptions: { columns: [0, 1, 2, 3, 4] }, // Columnas N°, Código, Nombre, Sección, Docente
                className: 'btn btn-outline-primary mb-2'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i>PDF',
                exportOptions: { columns: [0, 1, 2, 3, 4] },
                className: 'btn btn-outline-danger mb-2'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i>Excel',
                exportOptions: { columns: [0, 1, 2, 3, 4] },
                className: 'btn btn-outline-success mb-2'
            }
        ]
    });

    // Función para cargar datos en el DataTable
    function cargarDatos(idCarrera, idTurno, idSemestre) {
        $('#tabla-asignaturas').hide(); // Ocultar tabla mientras carga
        $('#mensaje-inicial').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-muted mb-3"></i><h4 class="text-muted">Cargando asignaturas...</h4></div>').show();


        $.ajax({
            url: '{{ route("asignatura.filtrar") }}', // Usar route() para generar la URL
            method: 'GET',
            data: {
                carrera_id: idCarrera,
                id_turno: idTurno,
                id_semestre: idSemestre
            },
            dataType: 'json',
            success: function(response) {
                table.clear();
                if (response && response.length > 0) {
                    table.rows.add(response).draw();
                    $('#tabla-asignaturas').show();
                    $('#mensaje-inicial').hide();
                } else {
                    $('#modalNoResultados').modal('show'); // Usar el modal de no resultados que ya tienes
                    $('#tabla-asignaturas').hide(); // Ocultar la estructura de la tabla
                    $('#mensaje-inicial').html('<div class="text-center py-5"><i class="fas fa-search fa-3x text-muted mb-3"></i><h4 class="text-muted">No se encontraron asignaturas para los filtros seleccionados.</h4></div>').show();

                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar datos:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Carga',
                    text: 'No se pudieron cargar los datos de las asignaturas. Intente de nuevo más tarde.',
                });
                table.clear().draw();
                $('#tabla-asignaturas').hide();
                 $('#mensaje-inicial').html('<div class="text-center py-5"><i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><h4 class="text-danger">Error al cargar las asignaturas.</h4><p class="text-muted">Revise la consola para más detalles.</p></div>').show();
            }
        });
    }

    // Evento para el botón de filtrar
    $('#filtrar-datos').click(function() {
        const idCarrera = $('#carrera').val();
        const idTurno = $('#turno').val();
        const idSemestre = $('#semestre').val();

        let errores = [];
        if (!idCarrera) errores.push('Debe seleccionar una carrera.');
        if (!idTurno) errores.push('Debe seleccionar un turno.');
        if (!idSemestre) errores.push('Debe seleccionar un semestre.');

        if (errores.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                html: errores.join('<br>'),
            });
            return;
        }
        cargarDatos(idCarrera, idTurno, idSemestre);
    });

    // Evento para el botón de limpiar filtros
    $('#reset-filtros').click(function() {
        $('#carrera').val('');
        $('#turno').val('');
        $('#semestre').html('<option value="">Seleccione turno</option>').prop('disabled', true);
        
        table.clear().draw();
        $('#tabla-asignaturas').hide();
        $('#mensaje-inicial').html('<div class="text-center py-5"><i class="fas fa-search fa-3x text-muted mb-3"></i><h4 class="text-muted">Utilice los filtros para visualizar las asignaturas</h4></div>').fadeIn(500);
    });

    // ***** Lógica para la ELIMINACIÓN de Asignaturas (función global) *****
    // Hacemos la función global para que pueda ser llamada desde el 'onclick' del botón
    window.confirmarEliminarAsignatura = function(asignaturaId, asignaturaName) {
        Swal.fire({
            title: '¿Estás seguro?',
            html: `¡No podrás revertir esto!<br>Se eliminará la asignatura <strong>${asignaturaName}</strong> y todas sus relaciones (docentes, secciones, carga horaria).`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', // Color rojo para confirmar
            cancelButtonColor: '#6c757d',  // Color gris para cancelar
            confirmButtonText: 'Sí, ¡eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, procede con la eliminación AJAX
                
                // Muestra una alerta "cargando" mientras se procesa la solicitud
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Por favor, espera.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    // URL: Debe coincidir con tu ruta DELETE de Laravel.
                    // Usamos el `asignaturaId` que se pasa a la función.
                    url: `/asignaturas/${asignaturaId}`, 
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Laravel CSRF token
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'La asignatura ha sido eliminada correctamente.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Recargar la página o actualizar la tabla para reflejar el cambio
                            location.reload(); 
                        });
                    },
                    error: function(xhr) {
                        console.error('Error en la solicitud AJAX de eliminación:', xhr.responseText);
                        let errorMessage = 'Hubo un error al eliminar la asignatura.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = 'La asignatura no fue encontrada o ya ha sido eliminada.';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    };

    // Manejo de alertas de sesión (como las tienes en tu archivo original)
    @if(session('alert'))
        Swal.fire({
            icon: "{{ session('alert.icon', 'info') }}",
            title: "{{ session('alert.title', 'Notificación') }}",
            text: "{{ session('alert.text', '') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Script para el modal de edición
    const editarModalElement = document.getElementById('editarModal');
    if (editarModalElement) {
        editarModalElement.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Botón que disparó el modal
            
            // Obtener datos del botón que abre el modal
            const asignaturaId = button.dataset.asignaturaId;
            const asignaturaName = button.dataset.asignaturaName;
            const asignaturaDocentes = JSON.parse(button.dataset.asignaturaDocentes || '[]');
            const asignaturaSecciones = JSON.parse(button.dataset.asignaturaSecciones || '[]');
            // Asegúrate de que este data-attribute se llama 'data-asignatura-carga-horaria' en tu botón de edición
            const asignaturaCargaHoraria = JSON.parse(button.dataset.asignaturaCargaHoraria || '[]');

            // 1. Precargar campos básicos
            document.getElementById('asignatura_id_editar').value = asignaturaId;
            document.getElementById('name_editar').value = asignaturaName;

            // 2. Actualizar la acción del formulario para apuntar a la ruta de actualización correcta
            const formEditar = document.getElementById('formEditar');
            formEditar.action = `/asignaturas/${asignaturaId}`; // Asegúrate de que esta ruta sea correcta en tus web.php

            // 3. Precargar y seleccionar docentes
            const docentesSelect = document.getElementById('docentes_editar');
            // Deseleccionar todas las opciones primero
            Array.from(docentesSelect.options).forEach(option => option.selected = false);
            // Seleccionar las opciones correspondientes
            asignaturaDocentes.forEach(docenteId => {
                const option = docentesSelect.querySelector(`option[value="${docenteId}"]`);
                if (option) {
                    option.selected = true;
                }
            });
            // La función filtrarOpcionesEditar debería estar definida en edit.blade.php
            if (typeof filtrarOpcionesEditar === 'function') {
                filtrarOpcionesEditar('docentes_editar', ''); // Resetear filtro y mostrar seleccionados
            }


            // 4. Precargar y seleccionar secciones
            const seccionesSelect = document.getElementById('secciones_editar');
            // Deseleccionar todas las opciones primero
            Array.from(seccionesSelect.options).forEach(option => option.selected = false);
            // Seleccionar las opciones correspondientes
            let firstSelectedSeccion = null;
            asignaturaSecciones.forEach(seccionId => {
                const option = seccionesSelect.querySelector(`option[value="${seccionId}"]`);
                if (option) {
                    option.selected = true;
                    if (!firstSelectedSeccion) {
                        firstSelectedSeccion = option; // Guardar la primera sección seleccionada
                    }
                }
            });
            // La función filtrarOpcionesEditar debería estar definida en edit.blade.php
            if (typeof filtrarOpcionesEditar === 'function') {
                filtrarOpcionesEditar('secciones_editar', ''); // Resetear filtro y mostrar seleccionados
            }

            // 5. Precargar campos ocultos de carrera, semestre y turno de la primera sección seleccionada
            if (firstSelectedSeccion) {
                document.getElementById('carrera_id_hidden_editar').value = firstSelectedSeccion.dataset.carreraId;
                document.getElementById('semestre_id_hidden_editar').value = firstSelectedSeccion.dataset.semestreId;
                document.getElementById('turno_id_hidden_editar').value = firstSelectedSeccion.dataset.turnoId;
            } else {
                // Limpiar si no hay secciones seleccionadas
                document.getElementById('carrera_id_hidden_editar').value = '';
                document.getElementById('semestre_id_hidden_editar').value = '';
                document.getElementById('turno_id_hidden_editar').value = '';
            }

            // 6. Precargar bloques de carga horaria
            const cargaHorariaContainer = document.getElementById('cargaHorariaContainerEditar');
            cargaHorariaContainer.innerHTML = ''; // Limpiar bloques existentes
            // NOTA: La función 'agregarBloqueCargaEditar' DEBE estar definida en 'edit.blade.php'
            // o ser globalmente accesible para que esta parte funcione.
            if (typeof agregarBloqueCargaEditar === 'function') {
                if (asignaturaCargaHoraria.length > 0) {
                    asignaturaCargaHoraria.forEach(carga => {
                        agregarBloqueCargaEditar(carga.tipo, carga.horas_academicas);
                    });
                } else {
                    // Si no hay carga horaria, añadir un bloque vacío por defecto
                    agregarBloqueCargaEditar();
                }
            } else {
                console.warn("La función 'agregarBloqueCargaEditar' no está definida. Asegúrate de que esté en 'edit.blade.php' o sea global.");
            }
        });
    }

    // Script para el modal de mostrar (show)
    const mostrarModalElement = document.getElementById('mostrarModal');
    if (mostrarModalElement) {
        mostrarModalElement.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Botón que disparó el modal

            // Obtener datos del botón (usamos data-asignatura_id para consistencia)
            const asignaturaId = button.dataset.asignatura_id; 
            const asignaturaName = button.dataset.name;
            const docentes = JSON.parse(button.dataset.docentes || '[]');
            const secciones = JSON.parse(button.dataset.secciones || '[]');
            // data-carga_horaria ahora es un array de objetos [{tipo: "teorica", horas_academicas: X}]
            const cargaHoraria = JSON.parse(button.dataset.carga_horaria || '[]'); 

            // Corregir IDs de elementos HTML para el modal de mostrar
            document.getElementById('modalShowCode').textContent = asignaturaId;
            document.getElementById('modalShowName').textContent = asignaturaName;

            // Mostrar Docentes
            const docentesList = document.getElementById('modalShowDocentesList');
            const noDocentesMessage = document.getElementById('modalShowNoDocentes');
            docentesList.innerHTML = ''; // Limpiar lista anterior
            if (docentes.length > 0) {
                noDocentesMessage.style.display = 'none';
                docentes.forEach(docenteId => {
                    // Esta parte asume que $docentes->pluck('name', 'cedula_doc') está disponible en el JS.
                    // Si 'docentes' ya trae el nombre directamente, simplifica esto.
                    const docenteEncontrado = {{ Js::from($docentes->pluck('name', 'cedula_doc')) }}[docenteId];
                    const li = document.createElement('li');
                    li.className = 'list-group-item'; // Asegurar clase para estilo
                    li.innerHTML = `<i class="fas fa-user-tie me-2 text-primary"></i>${docenteEncontrado || docenteId}`; // Mostrar nombre si existe, sino ID
                    docentesList.appendChild(li);
                });
            } else {
                noDocentesMessage.style.display = 'block';
            }

            // Mostrar Secciones
            const seccionesList = document.getElementById('modalShowSeccionesList');
            const noSeccionesMessage = document.getElementById('modalShowNoSecciones');
            seccionesList.innerHTML = ''; // Limpiar lista anterior
            if (secciones.length > 0) {
                noSeccionesMessage.style.display = 'none';
                secciones.forEach(seccion => {
                    // Asumimos que 'seccion' ya es el string formateado del render del DataTable
                    const li = document.createElement('li');
                    li.className = 'list-group-item'; // Asegurar clase para estilo
                    li.innerHTML = `<i class="fas fa-door-open me-2 text-success"></i>${seccion}`;
                    seccionesList.appendChild(li);
                });
            } else {
                noSeccionesMessage.style.display = 'block';
            }

            // Mostrar Carga Horaria
            const cargaHorariaList = document.getElementById('modalShowCargaHorariaList');
            const noCargaHorariaMessage = document.getElementById('modalShowNoCargaHoraria');
            cargaHorariaList.innerHTML = ''; // Limpiar lista anterior
            if (cargaHoraria.length > 0) { // CargaHoraria es un array de objetos
                noCargaHorariaMessage.style.display = 'none';
                cargaHoraria.forEach(carga => { // Iterar directamente el array
                    const li = document.createElement('li');
                    li.className = 'list-group-item'; // Asegurar clase para estilo
                    li.textContent = `${carga.tipo.charAt(0).toUpperCase() + carga.tipo.slice(1)}: ${carga.horas_academicas} horas`;
                    cargaHorariaList.appendChild(li);
                });
            } else {
                noCargaHorariaMessage.style.display = 'block';
            }
        });
    }

    // Código JavaScript del modal de creación (create.blade.php)
    // Este bloque no fue modificado, se mantiene como en tu base.
    let bloqueIndex = {{ is_array(old('carga_horaria')) ? count(old('carga_horaria')) : 0 }};
    let initialLoad = true;

    $('#addCargaHorariaBtn').on('click', function() {
        agregarBloqueCarga();
    });

    $('#cargaHorariaContainer').on('click', '.eliminar-carga-horaria', function() {
        $(this).closest('.carga-horaria-block').remove();
    });

    function agregarBloqueCarga(tipo = '', horas = '') {
        const newBlockHtml = `
            <div class="carga-horaria-block mb-2 p-3 border rounded" data-index="${bloqueIndex}">
                <div class="row">
                    <div class="col-md-5 mb-2">
                        <label for="carga_horaria_${bloqueIndex}_tipo" class="form-label small">Tipo de Hora <span class="text-danger">*</span></label>
                        <select class="form-control form-control-sm" name="carga_horaria[${bloqueIndex}][tipo]" id="carga_horaria_${bloqueIndex}_tipo" required>
                            <option value="">Seleccione</option>
                            <option value="teorica" ${tipo === 'teorica' ? 'selected' : ''}>Teórica</option>
                            <option value="practica" ${tipo === 'practica' ? 'selected' : ''}>Práctica</option>
                            <option value="laboratorio" ${tipo === 'laboratorio' ? 'selected' : ''}>Laboratorio</option>
                        </select>
                    </div>
                    <div class="col-md-5 mb-2">
                        <label for="carga_horaria_${bloqueIndex}_horas_academicas" class="form-label small">Horas Académicas <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-sm" name="carga_horaria[${bloqueIndex}][horas_academicas]" id="carga_horaria_${bloqueIndex}_horas_academicas" value="${horas}" min="1" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mb-2">
                        <button type="button" class="btn btn-danger btn-sm w-100 eliminar-carga-horaria">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#cargaHorariaContainer').append(newBlockHtml);
        bloqueIndex++;
    }

    // Cargar bloques antiguos si hay errores de validación
    @if(is_array(old('carga_horaria')) && count(old('carga_horaria')) > 0)
        $(document).ready(function() {
            $('#cargaHorariaContainer').empty(); // Limpiar el bloque por defecto si ya está allí
            @foreach(old('carga_horaria') as $index => $carga)
                agregarBloqueCarga('{{ $carga['tipo'] ?? '' }}', '{{ $carga['horas_academicas'] ?? '' }}');
            @endforeach
        });
    @endif

    // Asegurar que al menos un bloque de carga horaria esté presente en la carga inicial si no hay old data
    // ESTE ES EL BLOQUE QUE CAUSA LA DUPLICACIÓN Y SERÁ ELIMINADO
    /*
    $(document).ready(function() {
        if (initialLoad && !({{ is_array(old('carga_horaria')) && count(old('carga_horaria')) > 0 ? 'true' : 'false' }})) {
            agregarBloqueCarga();
        }
        initialLoad = false;
    });
    */

    // Mantener modal abierto si hay errores
    @if ($errors->any() && session('open_modal'))
        $(document).ready(function() {
            $('#registroModal').modal('show');
            
            // Reindexar bloques si hay errores
            @if(is_array(old('carga_horaria')))
                bloqueIndex = {{ count(old('carga_horaria')) }};
            @endif
        });
    @endif
});
</script>
@endpush
