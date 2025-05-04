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
@include('modals.asignaturas.edit')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const turnoSelect = document.getElementById('turno');
    const semestreSelect = document.getElementById('semestre');

    turnoSelect.addEventListener('change', function() {
        const turnoId = this.value;
        
        // Resetear semestre
        semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
        semestreSelect.disabled = true;
        if (!turnoId) return;

        // Mostrar carga
        semestreSelect.disabled = true;
        const loadingOption = new Option('Cargando semestres...', '');
        loadingOption.disabled = true;
        semestreSelect.add(loadingOption);

        // Hacer petición AJAX
        fetch(`/api/semestres-por-turno/${turnoId}`)
            .then(response => {
                if (!response.ok) throw new Error('Error al cargar semestres');
                return response.json();
            })
            .then(data => {
                // Limpiar select
                semestreSelect.innerHTML = '<option value="">Seleccione...</option>';
                
                // Agregar opciones
                data.forEach(semestre => {
                    const textoMostrado = `Semestre ${semestre.numero}`;
                    const option = new Option(textoMostrado, semestre.id);
                    semestreSelect.add(option);
                });
                
                semestreSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                semestreSelect.innerHTML = '<option value="">Error al cargar</option>';
                semestreSelect.disabled = false;
            });
    });
});
</script>

<script>
    $(document).ready(function() {
        // Configuración del PDF
        const pdfConfig = {
            customize: function(doc) {
                doc.pageMargins = [40, 80, 40, 60];
                doc.content.splice(0, 0, {
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - ASIGNATURAS',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                doc.content[1].text = 'REPORTE DE ASIGNATURAS';
                doc.content[1].alignment = 'center';
                doc.content[1].fontSize = 12;
                doc.content[1].margin = [0, 0, 0, 10];

                doc['footer'] = function(currentPage, pageCount) {
                    return {
                        text: 'Página ' + currentPage.toString() + ' de ' + pageCount,
                        alignment: 'center',
                        fontSize: 8,
                        margin: [40, 10, 40, 20]
                    };
                };

                doc.content[2].table.widths = ['auto', 'auto', '*', 'auto', 'auto'];
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Configuración DataTables
        const table = $("#tabla-asignaturas").DataTable({
            pageLength: 10,
            responsive: true,
            autoWidth: false,
            lengthChange: true,
            language: {
                emptyTable: "No hay asignaturas registradas",
                info: "Mostrando _START_ a _END_ de _TOTAL_ asignaturas",
                infoEmpty: "Mostrando 0 asignaturas",
                infoFiltered: "(filtradas de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            dom: 'Bfrtip',
            columns: [
                { data: 0, className: 'text-center' }, // N°
                { data: 1, className: 'text-center' }, // Código
                { data: 2, className: 'text-center' }, // Nombre
                { 
                    data: 3, 
                    className: 'text-center', 
                    title: 'Sección',
                    render: function(data, type, row) {
                        if (!data) return '<span class="badge bg-secondary">Sin asignar</span>';
                    return `
                        <span class="badge bg-primary">
                            <i class="fas fa-layer-group me-2"></i>${data}
                        </span>
                    `;
                    }
                 }, // Secciones
                { 
                    data: 4, 
                    className: 'text-center', 
                    title: 'Docente',
                    render: function(data, type, row) {
                        if (!data) return '<span class="badge bg-secondary">Sin asignar</span>';
                    return `
                        <span class="badge bg-info text-dark">
                            <i class="fas fa-user-tie me-2"></i>${data}
                        </span>
                    `;
                    }
                 },  // Docentes
                { 
                    data: null,
                    title: 'Acciones',
                    render: function(data, type, row) {
                    // Obtener docentes y secciones si están en la respuesta
                    const docentes = row.docentes ? JSON.stringify(row.docentes) : '[]';
                    const secciones = row.secciones ? JSON.stringify(row.secciones) : '[]';
        
                    return `
                        <div class="btn-group" role="group">
                        <!-- Botón Ver -->
                            <button class="btn btn-info btn-sm btn-ver"
                                data-bs-toggle="modal"
                                data-bs-target="#mostrarModal"
                                data-asignatura_id="${row[1]}"  // ID de la asignatura
                                data-name="${row[2]}"           // Nombre de la asignatura
                                data-docentes='${docentes}'
                                data-secciones='${secciones}'>
                            <i class="fas fa-eye"></i>
                            </button>

                        <!-- Botón Editar -->
                            <button class="btn btn-success btn-sm btn-editar"
                                data-bs-toggle="modal"
                                data-bs-target="#editarModal"
                                data-asignatura_id="${row[1]}"
                                data-name="${row[2]}"
                                data-docentes='${docentes}'
                                data-secciones='${secciones}'>
                            <i class="fas fa-pencil-alt"></i>
                            </button>

                        <!-- Botón Eliminar -->
                            <button class="btn btn-danger btn-sm btn-eliminar" data-id="${row[1]}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        `;
                    },
                orderable: false,
                searchable: false
                }
            ],
            columnDefs: [
                { width: 'auto', targets: 0 },  // N°
                { width: 'auto', targets: 1 },  // Código
                { width: '*', targets: 2 },  // Nombre
                { width: 'auto', targets: 3 },  // Secciones
                { width: 'auto', targets: 4 },   // Docentes
                { width: 'auto', targets: 5 }   // Acciones
            ],
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<img src="{{ asset("images/logo.jpg") }}" style="height: 80px; margin-bottom: 10px;"/>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - ASIGNATURAS</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE ASIGNATURAS</h2>' +
                                '</div>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');

                        $(win.document.body).append(
                            '<div style="text-align: center; margin-top: 20px; font-size: 8pt;">' +
                            '<p>Generado el: ' + new Date().toLocaleDateString('es-VE') + '</p>' +
                            '</div>'
                        );
                    },
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                    customize: pdfConfig.customize,
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Asignaturas Registradas',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    className: 'btn btn-success mr-2'
                }
            ],
            columnDefs: [
                { targets: [0, 1, 3, 4, 5], className: 'text-center' },
                { targets: -1, orderable: false, searchable: false }
            ],
            order: [[1, 'asc']]
        });

        // SweetAlerts para notificaciones
      

        // Confirmación eliminación
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: '¿Eliminar Asignatura?',
                text: "¡Se eliminarán todas las secciones y relaciones asociadas!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        (session('alert'))
            Swal.fire({
                icon: '{{ session("alert")["type"] ?? "info" }}',
                title: '{{ session("alert")["title"] ?? "Notificación" }}',
                text: '{{ session("alert")["message"] ?? "" }}',
                timer: 3000,
                showConfirmButton: false
            });

        // Handlers para modals
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            modal.find('#modalCode').text(button.data('asignatura_id'));
            modal.find('#modalName').text(button.data('name'));
            modal.find('#modalDocentes').text(button.data('docentes'));
            modal.find('#modalSecciones').text(button.data('secciones'));
        });

        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            const docentes = JSON.parse(button.data('docentes'));
            const secciones = JSON.parse(button.data('secciones'));

            modal.find('#asignatura_id_editar').val(button.data('asignatura_id'));
            modal.find('#name_editar').val(button.data('name'));
            modal.find('#docentes_editar').val(docentes).trigger('change');
            modal.find('#secciones_editar').val(secciones).trigger('change');
            modal.find('#formEditar').attr('action', '/asignaturas/' + button.data('asignatura_id'));
        });
    });
</script>

<script>
            // Función para cargar datos via AJAX
            async function cargarDatos(idCarrera, idTurno, idSemestre) {

            var table = $('#tabla-asignaturas').DataTable();
            
            try {
                const response = await $.ajax({
                    url: '/asignatura/filtrar',
                    method: 'GET',
                    data: { 
                        carrera_id: idCarrera, 
                        id_turno: idTurno,
                        id_semestre: idSemestre 
                    }
                });

                if (response.length > 0) {
                    table.clear().rows.add(response).draw();
                    $('#tabla-asignaturas').fadeIn(500);
                    $('#mensaje-inicial').hide();
                } else {
                    $('#modalNoResultados').modal('show');
                    $('#tabla-asignaturas').hide();
                    $('#mensaje-inicial').show();
                }
            } catch (error) {
                console.error('Error:', error);
                toastr.error('Error al cargar los datos');
            }
        }

        // Eventos
        $('#filtrar-datos').click(function() {
            const idCarrera = $('#carrera').val();
            const idTurno = $('#turno').val();
            const idSemestre = $('#semestre').val();

            if (!idCarrera || !idTurno || !idSemestre) {
                toastr.error('Debe seleccionar todos los campos');
                return;
            }

            cargarDatos(idCarrera, idTurno, idSemestre);
        });

        $('#reset-filtros').click(function() {

            var table = $('#tabla-asignaturas').DataTable();

            $('#carrera, #turno, #semestre').val();
            table.clear().draw();
            $('#tabla-asignaturas').hide();
            $('#mensaje-inicial').fadeIn(500);
        });

</script>

<script>
    $(document).on('click', '.btn-eliminar', function() {
    const id = $(this).data('id');
    if (confirm('¿Estás seguro de eliminar esta asignatura?')) {
        $.ajax({
            url: `/asignatura/${id}`,
            method: 'POST',
            data: {
                _method: 'DELETE',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                table.ajax.reload(); // Recargar la tabla
                toastr.success('Asignatura eliminada correctamente');
            },
            error: function() {
                toastr.error('Error al eliminar la asignatura');
            }
        });
    }
});
</script>
@endpush