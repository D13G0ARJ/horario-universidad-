@extends('layouts.admin')

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

    <!-- Tabla de asignaturas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Asignaturas Registradas
                    </h4>
                    <a href="#" class="btn btn-light ms-auto text-dark"
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
                            @foreach($asignaturas as $asignatura)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $asignatura->asignatura_id }}</td>
                                <td>{{ $asignatura->name }}</td>
                                <td style="text-align: center">
                                    @foreach($asignatura->secciones as $seccion)
                                    <span class="badge bg-success">{{ $seccion->codigo_seccion }}</span>
                                    @endforeach
                                </td>
                                <td style="text-align: center">
                                    @foreach($asignatura->docentes as $docente)
                                    <span class="badge bg-info">{{ $docente->name }}</span>
                                    @endforeach
                                </td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-asignatura_id="{{ $asignatura->asignatura_id }}"
                                            data-name="{{ $asignatura->name }}"
                                            data-docentes="{{ $asignatura->docentes->pluck('name')->toJson() }}"
                                            data-secciones="{{ $asignatura->secciones->pluck('codigo_seccion')->toJson() }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#editarModal"
        data-asignatura_id="{{ $asignatura->asignatura_id }}"
        data-name="{{ $asignatura->name }}"
        data-docentes="{{ $asignatura->docentes->pluck('cedula_doc')->toJson() }}"
        data-secciones="{{ $asignatura->secciones->pluck('codigo_seccion')->toJson() }}">
    <i class="fas fa-pencil-alt"></i>
</button>

                                        <!-- Botón para Eliminar -->
                                        <form action="{{ route('asignatura.destroy', $asignatura->asignatura_id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclusión de modals -->
@include('modals.asignaturas.create', [
'docentes' => $docentes,
'secciones' => $secciones
])
@include('modals.asignaturas.show')
@include('modals.asignaturas.edit')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [{
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
                                '<img src="{{ asset('
                                images / logo.jpg ') }}" style="height: 80px; margin-bottom: 10px;"/>' +
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
                },
            ],
            columnDefs: [{
                    targets: [0, 1, 3, 4, 5],
                    className: 'text-center'
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ]
        });

        // SweetAlerts
        @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('alert'))
        Swal.fire({
            icon: '{{ session("alert")["type"] ?? "info" }}',
            title: '{{ session("alert")["title"] ?? "Notificación" }}',
            text: '{{ session("alert")["message"] ?? "" }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
@endpush

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
@endpush
@endsection