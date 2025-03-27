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
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 0; @endphp
                            @foreach($asignaturas as $asignatura)
                            @php $contador++; @endphp
                            <tr>
                                <td style="text-align: center">{{ $contador }}</td>
                                <td style="text-align: center">{{ $asignatura->code }}</td>
                                <td>{{ $asignatura->name }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-id="{{ $asignatura->id }}"
                                            data-name="{{ $asignatura->name }}"
                                            data-code="{{ $asignatura->code }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal"
                                            data-id="{{ $asignatura->id }}"
                                            data-name="{{ $asignatura->name }}"
                                            data-code="{{ $asignatura->code }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <form action="{{ route('asignatura.destroy', $asignatura->code) }}" method="POST">
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

<!-- Modal de registro -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                    <div class="form-group mb-3">
                        <label for="code" class="form-label">Código</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                                name="code" placeholder="Código" value="{{ old('code') }}" required autofocus>
                        </div>
                        @error('code')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" placeholder="Nombre" value="{{ old('name') }}" required>
                        </div>
                        @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Registrar Asignatura
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Mostrar -->
<div class="modal fade" id="mostrarModal" tabindex="-1" aria-labelledby="mostrarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i>Detalles de la Asignatura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="fw-bold">Código:</label>
                    <p id="modalCode" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Nombre:</label>
                    <p id="modalName" class="form-control-plaintext"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Editar Asignatura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="code_editar" class="form-label">Código</label>
                        <input type="text" class="form-control" name="code" id="code_editar" readonly required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name_editar" required>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Configuración del PDF (sin imagen)
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

                // Ajustar columnas automáticamente al contenido
                doc.content[2].table.widths = 'auto';
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Inicializar DataTables
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
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2] // Excluye columna de acciones (índice 3)
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .find('table')
                            .css('width', 'auto') // Ajustar al contenido
                            .css('max-width', 'none');

                        $(win.document.body).prepend(
                            '<div style="text-align: center; margin-bottom: 20px;">' +
                            '<img src="{{ asset('images/logo.jpg') }}" style="height: 80px; margin-bottom: 10px;"/>' +
                            '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                            '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                            '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                            '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - ASIGNATURAS</h4>' +
                            '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE ASIGNATURAS</h2>' +
                            '</div>'
                        );

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
                        columns: [0, 1, 2] // Excluye columna de acciones (índice 3)
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Asignaturas Registradas',
                    exportOptions: {
                        columns: [0, 1, 2] // Excluye columna de acciones (índice 3)
                    },
                    className: 'btn btn-success mr-2'
                }
            ],
            columnDefs: [
                { orderable: false, targets: [0, 3] },
                { className: 'text-center', targets: [0, 1, 3] }
            ]
        });

        // Script para llenar el modal de visualización (sin cambios)
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const code = button.data('code');
            const name = button.data('name');

            const modal = $(this);
            modal.find('#modalCode').text(code);
            modal.find('#modalName').text(name);
        });

        // Script para llenar el modal de edición (sin cambios)
        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const code = button.data('code');
            const name = button.data('name');

            const modal = $(this);
            modal.find('#code_editar').val(code);
            modal.find('#name_editar').val(name);
            modal.find('#formEditar').attr('action', '/asignaturas/' + code);
        });
    });
</script>
@endpush
@endsection
