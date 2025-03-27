@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-layer-group mr-2"></i>Gestión de Secciones
            </h3>
        </div>
    </div>

    <!-- Tabla de secciones -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Secciones Registradas
                    </h4>
                    <a href="#" class="btn btn-light ms-auto text-dark"
                    data-bs-toggle="modal" data-bs-target="#registroModal">
                     <i class="fas fa-plus mr-1"></i>Nueva Sección
                 </a>
                </div>
                <div class="card-body">
                    <table id="tabla-secciones" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Aula</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($secciones as $seccion)
                            <tr>
                                <td style="text-align: center"></td>
                                <td>{{ $seccion->nombre }}</td>
                                <td>{{ $seccion->aula->nombre }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarSeccionModal"
                                            data-nombre="{{ $seccion->nombre }}"
                                            data-aula="{{ $seccion->aula->nombre }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarSeccionModal"
                                            data-id="{{ $seccion->id }}"
                                            data-nombre="{{ $seccion->nombre }}"
                                            data-aula_id="{{ $seccion->aula_id }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <form action="{{ route('secciones.destroy', $seccion->id) }}" method="POST">
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

<!-- Modal de Registro -->
<div class="modal fade" id="crearSeccionModal" tabindex="-1" aria-labelledby="crearSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="crearSeccionModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Registrar Nueva Sección
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('secciones.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                                name="nombre" placeholder="Nombre de la sección" value="{{ old('nombre') }}" required>
                        </div>
                        @error('nombre')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="aula_id" class="form-label">Aula</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <select class="form-select @error('aula_id') is-invalid @enderror" name="aula_id" required>
                                <option value="">Seleccione un aula</option>
                                @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}" {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
                                    {{ $aula->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('aula_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Registrar Sección
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Mostrar -->
<div class="modal fade" id="mostrarSeccionModal" tabindex="-1" aria-labelledby="mostrarSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i>Detalles de la Sección
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="fw-bold">Nombre:</label>
                    <p id="modalSeccionNombre" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Aula:</label>
                    <p id="modalSeccionAula" class="form-control-plaintext"></p>
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
<div class="modal fade" id="editarSeccionModal" tabindex="-1" aria-labelledby="editarSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Editar Sección
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditarSeccion">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="edit_id">

                    <div class="form-group mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_aula_id" class="form-label">Aula</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <select class="form-select" name="aula_id" id="edit_aula_id" required>
                                @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}">{{ $aula->nombre }}</option>
                                @endforeach
                            </select>
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
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - SECCIONES',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                doc.content[1].text = 'REPORTE DE SECCIONES';
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

                doc.content[2].table.widths = ['auto', '*', '*', 'auto'];
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Inicializar DataTables
        const table = $("#tabla-secciones").DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay secciones registradas",
                info: "Mostrando _START_ a _END_ de _TOTAL_ secciones",
                infoEmpty: "Mostrando 0 secciones",
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
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<img src="{{ asset('images/logo.jpg') }}" style="height: 80px; margin-bottom: 10px;"/>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - SECCIONES</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE SECCIONES</h2>' +
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
                    title: 'Secciones Registradas',
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
                { targets: 3, className: 'text-center' }
            ]
        });

        // Script para llenar el modal de visualización
        $('#mostrarSeccionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const nombre = button.data('nombre');
            const aula = button.data('aula');

            const modal = $(this);
            modal.find('#modalSeccionNombre').text(nombre);
            modal.find('#modalSeccionAula').text(aula);
        });

        // Script para llenar el modal de edición
        $('#editarSeccionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const nombre = button.data('nombre');
            const aula_id = button.data('aula_id');

            const modal = $(this);
            modal.find('#edit_id').val(id);
            modal.find('#edit_nombre').val(nombre);
            modal.find('#edit_aula_id').val(aula_id);
            modal.find('#formEditarSeccion').attr('action', '/secciones/' + id);
        });
    });
</script>
@endpush
@endsection
