@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-calendar-alt mr-2"></i>Gestión de Períodos Académicos
            </h3>
        </div>
    </div>

    <!-- Tabla de períodos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Periodos Registrados
                    </h4>
                    <a href="#" class="btn btn-light ms-auto text-dark"
                    data-bs-toggle="modal" data-bs-target="#registroModal">
                     <i class="fas fa-plus mr-1"></i>Nuevo Período
                 </a>
                </div>
                <div class="card-body">
                    <table id="tabla-periodos" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Fecha de Inicio</th>
                                <th style="text-align: center">Fecha de Fin</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 0; @endphp
                            @foreach($periodos as $periodo)
                            @php $contador++; @endphp
                            <tr>
                                <td style="text-align: center">{{ $contador }}</td>
                                <td>{{ $periodo->nombre }}</td>
                                <td style="text-align: center">{{ $periodo->fecha_inicio }}</td>
                                <td style="text-align: center">{{ $periodo->fecha_fin }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-id="{{ $periodo->id }}"
                                            data-nombre="{{ $periodo->nombre }}"
                                            data-fecha_inicio="{{ $periodo->fecha_inicio }}"
                                            data-fecha_fin="{{ $periodo->fecha_fin }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal"
                                            data-id="{{ $periodo->id }}"
                                            data-nombre="{{ $periodo->nombre }}"
                                            data-fecha_inicio="{{ $periodo->fecha_inicio }}"
                                            data-fecha_fin="{{ $periodo->fecha_fin }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <form action="{{ route('periodo.destroy', $periodo->id) }}" method="POST">
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
                    <i class="fas fa-plus-circle mr-2"></i>Registrar Nuevo Período
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('periodo.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                                name="nombre" placeholder="Nombre del período" value="{{ old('nombre') }}" required>
                        </div>
                        @error('nombre')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                            <input id="fecha_inicio" type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                        </div>
                        @error('fecha_inicio')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                            <input id="fecha_fin" type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                        </div>
                        @error('fecha_fin')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Registrar Período
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
                    <i class="fas fa-info-circle mr-2"></i>Detalles del Período
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="fw-bold">Nombre:</label>
                    <p id="modalNombre" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Fecha de Inicio:</label>
                    <p id="modalFechaInicio" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Fecha de Fin:</label>
                    <p id="modalFechaFin" class="form-control-plaintext"></p>
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
                    <i class="fas fa-edit mr-2"></i>Editar Período
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="nombre_editar" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control" name="nombre" id="nombre_editar" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="fecha_inicio_editar" class="form-label">Fecha de Inicio</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio_editar" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="fecha_fin_editar" class="form-label">Fecha de Fin</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin_editar" required>
                        </div>
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
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - PERÍODOS ACADÉMICOS',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                doc.content[1].text = 'REPORTE DE PERÍODOS ACADÉMICOS';
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

                // Ajustar para excluir la columna de acciones (última columna)
                doc.content[2].table.widths = ['auto', '*', 'auto', 'auto']; // Eliminar el último 'auto'
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Inicializar DataTables
        const table = $("#tabla-periodos").DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay períodos registrados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ períodos",
                infoEmpty: "Mostrando 0 períodos",
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
                        columns: [0, 1, 2, 3] // Excluir la última columna (acciones)
                    },
                    customize: function(win) {
                        // Eliminar la columna de acciones del DOM antes de imprimir
                        $(win.document.body).find('th:nth-child(5), td:nth-child(5)').remove();

                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<img src="{{ asset("images/logo.jpg") }}" style="height: 80px; margin-bottom: 10px;"/>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - PERÍODOS ACADÉMICOS</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE PERÍODOS ACADÉMICOS</h2>' +
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
                        columns: [0, 1, 2, 3] // Excluir la última columna (acciones)
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Períodos Académicos Registrados',
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Excluir la última columna (acciones)
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
                {
                    targets: [2, 3, 4],
                    className: 'text-center'
                },
                {
                    targets: -1, // Columna de acciones
                    visible: true, // Visible en la tabla
                    exportable: false, // No se exporta
                    printable: false // No se imprime
                }
            ]
        });

        // Scripts para modales (sin cambios)
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const nombre = button.data('nombre');
            const fechaInicio = button.data('fecha_inicio');
            const fechaFin = button.data('fecha_fin');

            const modal = $(this);
            modal.find('#modalNombre').text(nombre);
            modal.find('#modalFechaInicio').text(fechaInicio);
            modal.find('#modalFechaFin').text(fechaFin);
        });

        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const nombre = button.data('nombre');
            const fechaInicio = button.data('fecha_inicio');
            const fechaFin = button.data('fecha_fin');

            const modal = $(this);
            modal.find('#nombre_editar').val(nombre);
            modal.find('#fecha_inicio_editar').val(fechaInicio);
            modal.find('#fecha_fin_editar').val(fechaFin);
            modal.find('#formEditar').attr('action', '/periodos/' + id);
        });
    });
</script>
@endpush
@endsection
