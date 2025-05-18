@extends('layouts.admin')
@section('style')
<style>#mensaje-inicial {
    transition: all 0.3s ease;
    background-color: #f8f9fa;
    border-radius: 8px;
}

#mensaje-inicial h4 {
    font-weight: 300;
    letter-spacing: 0.5px;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-book mr-2"></i>Listado de Asignaturas
            </h3>
        </div>
    </div>

    <!-- Modal para mensaje de no resultados -->
    <div class="modal fade" id="modalNoResultados" tabindex="-1" role="dialog" aria-labelledby="modalNoResultadosLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalNoResultadosLabel">
                        <i class="fas fa-exclamation-circle mr-2"></i>Sin resultados
                    </h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
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

    <!-- Filtros de búsqueda mejorados -->

    <!-- Carrera -->
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

        <!-- Turno -->
        <div class="col-md-2">
            <label for="turno" class="form-label">Turno:</label>
            <select id="turno" name="turno_id" class="form-select form-select-lg" required>
                <option value="">Seleccione...</option>
                    @foreach($turnos as $turno)
                        <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                    @endforeach
            </select>
        </div>

        <!-- Semestre -->
        <div class="col-md-3">
            <label for="semestre" class="form-label">Semestre:</label>
            <select id="semestre" name="semestre_id" class="form-select form-select-lg" required disabled>
                <option value="">Seleccione turno</option>
            </select>
        </div>

        <!-- Buscar -->
        <div class="col-md-2 d-flex align-items-end">
            <button id="filtrar-datos" class="btn btn-primary w-100">
                <i class="fas fa-search mr-2"></i>Buscar
            </button>
        </div>

        <!-- Limpiar datos -->
        <div class="col-md-2 d-flex align-items-end">
            <button id="reset-filtros" class="btn btn-outline-secondary w-100">
                <i class="fas fa-broom mr-2"></i>Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Tabla de asignaturas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Asignaturas Registradas
                    </h4>
                    <a href="#" class="btn btn-success ms-auto text-dark"
                        data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="fas fa-plus mr-1"></i>Nueva Asignatura
                    </a>
                </div>
                <div class="card-body">
                    <table id="tabla-asignaturas" class="table table-bordered table-hover">
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

<!-- Inclusión de modals -->
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
    ]))

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
        // Los datos para la tabla provienen de la llamada AJAX en cargarDatos()
        // La estructura de 'row' en la función render es la definida por el método 'filtrar' del controlador:
        // {
        //   '0': item->id (PK),
        //   '1': item->asignatura_id (Código),
        //   '2': item->name (Nombre),
        //   '3': item->secciones->first()?->codigo_seccion,
        //   '4': item->docentes->first()?->name,
        //   'docentes': array de nombres de docentes,
        //   'secciones': array de códigos de sección,
        //   'carga_horaria': objeto {teorica: X, practica: Y, laboratorio: Z}
        // }
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
                    const cargaHorariaData = (row.carga_horaria && typeof row.carga_horaria === 'object') ? JSON.stringify(row.carga_horaria) : '{}';

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
                                data-asignatura_id="${asignaturaCodigo}" 
                                data-name="${nombreAsignatura}"
                                data-docentes='${docentesData}'
                                data-secciones='${seccionesData}'
                                data-carga_horaria='${cargaHorariaData}'
                                data-pk_id="${asignaturaPkId}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar"
                                title="Eliminar Asignatura" 
                                data-id="${asignaturaPkId}">  <i class="fas fa-trash"></i>
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

    // Manejo de eliminación de asignatura
    $(document).on('click', '.btn-eliminar', function() {
        const asignaturaPkId = $(this).data('id'); // Este es el ID primario de la asignatura

        Swal.fire({
            title: '¿Está seguro?',
            text: "¡Esta acción eliminará la asignatura permanentemente y no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, ¡eliminar!',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // La ruta debe ser /asignaturas/{id_primario}
                // El controlador destroy(Asignatura $asignatura) usará este ID para el Route Model Binding.
                const deleteUrl = `/asignaturas/${asignaturaPkId}`; 

                $.ajax({
                    url: deleteUrl,
                    method: 'POST', // Laravel usa POST para _method spoofing
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { _method: 'DELETE' },
                    success: function(response) {
                        // Recargar datos si los filtros están aplicados
                        const carrera = $('#carrera').val();
                        const turno = $('#turno').val();
                        const semestre = $('#semestre').val();
                        
                        if(carrera && turno && semestre) {
                            cargarDatos(carrera, turno, semestre);
                        } else {
                            table.clear().draw();
                            $('#tabla-asignaturas').hide();
                            $('#mensaje-inicial').html('<div class="text-center py-5"><i class="fas fa-search fa-3x text-muted mb-3"></i><h4 class="text-muted">Utilice los filtros para visualizar las asignaturas</h4></div>').show();
                        }
                        
                        Swal.fire({
                            icon: response.alert && response.alert.icon ? response.alert.icon : 'success',
                            title: response.alert && response.alert.title ? response.alert.title : 'Eliminado',
                            text: response.alert && response.alert.text ? response.alert.text : 'La asignatura ha sido eliminada.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al eliminar',
                            text: (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'No se pudo eliminar la asignatura. Verifique la consola.'
                        });
                        console.error("Error en AJAX delete:", xhr.responseText);
                    }
                });
            }
        });
    });

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

    // Script para el modal de edición (como lo tienes en tu archivo original)
    // Asegúrate que 'editarModal' y los IDs de los campos del formulario de edición son correctos
    const editarModalElement = document.getElementById('editarModal');
    if (editarModalElement) {
        editarModalElement.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const form = editarModalElement.querySelector('form'); // Asegúrate que el form tiene un ID o es el único
            
            const asignaturaPkId = button.dataset.pk_id; // ID primario para la URL de update
            const asignaturaCodigo = button.dataset.asignatura_id;
            const name = button.dataset.name;
            const docentes = JSON.parse(button.dataset.docentes || '[]');
            const secciones = JSON.parse(button.dataset.secciones || '[]');
            const cargaHoraria = JSON.parse(button.dataset.carga_horaria || '{}');

            // La URL para actualizar sería algo como /asignaturas/{id_primario_asignatura}
            if(form) form.action = `/asignaturas/${asignaturaPkId}`;
            
            // Poblar campos básicos
            $('#asignatura_id_editar').val(asignaturaCodigo); // El código de la asignatura
            $('#name_editar').val(name);

            // Poblar Select2 para docentes si usas Select2, o un multiselect normal
            const docentesSelect = $('#docentes_editar');
            docentesSelect.val(null).trigger('change'); // Limpiar previas si es Select2
            if (docentes.length > 0) {
                 // Para un multiselect normal:
                docentesSelect.find('option').each(function() {
                    $(this).prop('selected', docentes.includes($(this).text().split(' - ')[1]) || docentes.includes($(this.val()))); // Compara por cédula o nombre completo si es necesario
                });
            }
            // Ajustar visualización del multiselect si es necesario (ej. Select2 o Chosen)
            // $('.select2').select2(); // Si usas select2, reinicializa o actualiza

            // Poblar Select2 para secciones
            const seccionesSelect = $('#secciones_editar');
            seccionesSelect.val(null).trigger('change');
            if (secciones.length > 0) {
                 seccionesSelect.find('option').each(function() {
                    $(this).prop('selected', secciones.includes($(this).val()));
                });
            }

            // Poblar Carga Horaria en el modal de edición
            // Esto es más complejo y depende de cómo esté estructurado tu form de edición para la carga horaria.
            // Similar a como se hace en el modal de creación, necesitarás recrear los bloques.
            // Ejemplo conceptual (debes adaptarlo a tu modal de edición):
            const cargaHorariaContainerEdit = $('#cargaHorariaContainer_editar'); // Suponiendo que tienes un contenedor
            cargaHorariaContainerEdit.empty(); // Limpiar bloques anteriores
            let editIndex = 0;
            for (const tipo in cargaHoraria) {
                if (cargaHoraria.hasOwnProperty(tipo) && cargaHoraria[tipo] > 0) {
                    // Aquí deberías tener una plantilla similar a la de 'create.blade.php' para la carga horaria
                    // y clonarla, llenarla y añadirla al contenedor.
                    // const nuevoBloque = $('#cargaHorariaTemplate_editar').clone().html(); // Suponiendo template
                    // ... reemplazar __INDEX__ con editIndex, seleccionar tipo, horas y añadir
                    // cargaHorariaContainerEdit.append(nuevoBloque);
                    editIndex++;
                }
            }
             if (editIndex === 0 && cargaHorariaContainerEdit.length) { // Si no hay carga horaria, agregar un bloque vacío por defecto
                // agregarBloqueCargaHorariaEditar(); // Función para añadir un bloque en el modal de edición
            }


            // Actualizar datos de carrera, semestre, turno si están en el modal de edición y dependen de la sección
            // actualizarDatosSeccionEditar(); // Si tienes esta función
        });
    }

});
</script>
@endpush