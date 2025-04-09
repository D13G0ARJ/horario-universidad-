@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-chalkboard-teacher mr-2"></i>Listado de Docentes
            </h3>
        </div>
    </div>

    <!-- Tabla de docentes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Docentes Registrados
                    </h4>
                    <a href="#" class="btn btn-light ms-auto text-dark"
                    data-bs-toggle="modal" data-bs-target="#registroModal">
                     <i class="fas fa-plus mr-1"></i>Nuevo Coordinador
                 </a>
                </div>
                <div class="card-body">
                    <table id="tabla-docentes" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Correo</th>
                                <th style="text-align: center">Teléfono</th>
                                <th style="text-align: center">Asignaturas</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 0; @endphp
                            @foreach($docentes as $docente)
                            @php $contador++; @endphp
                            <tr>
                                <td style="text-align: center">{{ $contador }}</td>
                                <td>{{ $docente->name }}</td>
                                <td>{{ $docente->email }}</td>
                                <td>{{ $docente->phone }}</td>
                                <td>
                                    <ul class="mb-0">
                                        @foreach ($docente->asignaturas as $asignatura)
                                        <li>{{ $asignatura->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-id="{{ $docente->id }}"
                                            data-name="{{ $docente->name }}"
                                            data-email="{{ $docente->email }}"
                                            data-phone="{{ $docente->phone }}"
                                            data-asignaturas="{{ $docente->asignaturas->pluck('name')->join(', ') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal"
                                            data-id="{{ $docente->id }}"
                                            data-name="{{ $docente->name }}"
                                            data-email="{{ $docente->email }}"
                                            data-phone="{{ $docente->phone }}"
                                            data-asignaturas="{{ $docente->asignaturas->pluck('id') }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <form action="{{ route('docente.destroy', $docente->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">
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
                    <i class="fas fa-plus-circle mr-2"></i>Registrar Nuevo Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('docente.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input id="name" type="text" class="form-control" name="name" placeholder="Nombre" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Correo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input id="email" type="email" class="form-control" name="email" placeholder="Correo" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input id="phone" type="text" class="form-control" name="phone" placeholder="Teléfono" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="asignaturas" class="form-label">Asignaturas</label>
                        <select id="asignaturas" class="form-select" name="asignaturas[]" multiple>
                            @foreach ($asignaturas as $asignatura)
                            <option value="{{ $asignatura->id }}">{{ $asignatura->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Registrar Docente
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
                    <i class="fas fa-info-circle mr-2"></i>Detalles del Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="fw-bold">Nombre:</label>
                    <p id="modalName" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Correo:</label>
                    <p id="modalEmail" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Teléfono:</label>
                    <p id="modalPhone" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Asignaturas:</label>
                    <p id="modalAsignaturas" class="form-control-plaintext"></p>
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
                    <i class="fas fa-edit mr-2"></i>Editar Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" name="name" id="name_editar" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email_editar" class="form-label">Correo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email" id="email_editar" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone_editar" class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control" name="phone" id="phone_editar" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="asignaturas_editar" class="form-label">Asignaturas</label>
                        <select id="asignaturas_editar" class="form-select" name="asignaturas[]" multiple>
                            @foreach ($asignaturas as $asignatura)
                            <option value="{{ $asignatura->id }}">{{ $asignatura->name }}</option>
                            @endforeach
                        </select>
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
        // Configuración del PDF
        const pdfConfig = {
            customize: function(doc) {
                doc.pageMargins = [40, 80, 40, 60];
                doc.content.splice(0, 0, {
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - DOCENTES',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                doc.content[1].text = 'REPORTE DE DOCENTES';
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

                doc.content[2].table.widths = ['auto', '*', '*', 'auto', '*', 'auto'];
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Inicializar DataTables
        const table = $("#tabla-docentes").DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay docentes registrados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ docentes",
                infoEmpty: "Mostrando 0 docentes",
                infoFiltered: "(filtrados de _MAX_ registros totales)",
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
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<img src="{{ asset("images/logo.jpg") }}" style="height: 80px; margin-bottom: 10px;"/>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - DOCENTES</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE DOCENTES</h2>' +
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
                    exportOptions: {
                        columns: ':visible'
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
                        columns: ':visible'
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Docentes Registrados',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-success mr-2'
                },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    className: 'text-center',
                    orderable: false
                },
                { targets: [2, 3, 5], className: 'text-center' }
            ]
        });

        // Script para llenar el modal de visualización
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const name = button.data('name');
            const email = button.data('email');
            const phone = button.data('phone');
            const asignaturas = button.data('asignaturas');

            const modal = $(this);
            modal.find('#modalName').text(name);
            modal.find('#modalEmail').text(email);
            modal.find('#modalPhone').text(phone);
            modal.find('#modalAsignaturas').text(asignaturas);
        });

        // Script para llenar el modal de edición
        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const name = button.data('name');
            const email = button.data('email');
            const phone = button.data('phone');
            const asignaturas = button.data('asignaturas').split(',');

            const modal = $(this);
            modal.find('#name_editar').val(name);
            modal.find('#email_editar').val(email);
            modal.find('#phone_editar').val(phone);
            modal.find('#formEditar').attr('action', '/docentes/' + id);

            // Seleccionar las asignaturas del docente
            $('#asignaturas_editar').val(asignaturas).trigger('change');
        });

        // Inicializar select2 para los selects múltiples
        $('#asignaturas, #asignaturas_editar').select2({
            placeholder: "Seleccione asignaturas",
            width: '100%'
        });
    });
</script>
@endpush
@endsection
