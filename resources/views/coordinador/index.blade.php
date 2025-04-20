@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-user-tie mr-2"></i>Listado de Coordinadores
            </h3>
        </div>
    </div>

    <!-- Tabla de coordinadores -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Coordinadores Registrados
                    </h4>
                    <a href="#" class="btn btn-success ms-auto text-dark"
                    data-bs-toggle="modal" data-bs-target="#registroModal">
                     <i class="fas fa-plus mr-1"></i>Nuevo Coordinador
                 </a>
                </div>
                <div class="card-body">
                    <table id="tabla-coordinadores" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th style="text-align: center">Cédula</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Email</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td style="text-align: center"></td>
                                <td style="text-align: center">{{ $usuario->cedula }}</td>
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-id="{{ $usuario->id }}"
                                            data-name="{{ $usuario->name }}"
                                            data-cedula="{{ $usuario->cedula }}"
                                            data-email="{{ $usuario->email }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal"
                                            data-id="{{ $usuario->id }}"
                                            data-name="{{ $usuario->name }}"
                                            data-cedula="{{ $usuario->cedula }}"
                                            data-email="{{ $usuario->email }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                         <!-- Botón para Eliminar -->
                                         <button class="btn btn-danger btn-sm btn-eliminar"
                                            data-id="{{ $usuario->cedula }}"
                                            data-name="{{ $usuario->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                    <i class="fas fa-plus-circle mr-2"></i>Registrar Nuevo Coordinador
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('coordinador.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="cedula" class="form-label">Cédula</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input id="cedula" type="text" class="form-control @error('cedula') is-invalid @enderror"
                                name="cedula" placeholder="Cédula" value="{{ old('cedula') }}" required autofocus>
                        </div>
                        @error('cedula')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" placeholder="Nombre" value="{{ old('name') }}" required>
                        </div>
                        @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" placeholder="Contraseña" required>
                        </div>
                        @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password-confirm" class="form-label">Confirmar Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" placeholder="Confirmar Contraseña" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Registrar Coordinador
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
                    <i class="fas fa-info-circle mr-2"></i>Detalles del Coordinador
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="fw-bold">Nombre:</label>
                    <p id="modalNombre" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Cédula:</label>
                    <p id="modalCedula" class="form-control-plaintext"></p>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Email:</label>
                    <p id="modalEmail" class="form-control-plaintext"></p>
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
                    <i class="fas fa-edit mr-2"></i>Editar Coordinador
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="cedula_editar" class="form-label">Cédula</label>
                        <input type="text" class="form-control" name="cedula" id="cedula_editar" readonly required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name_editar" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email_editar" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email_editar" required>
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
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - COORDINADORES',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                doc.content[1].text = 'REPORTE DE COORDINADORES';
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

                doc.content[2].table.widths = ['auto', 'auto', '*', '*']; // Eliminada columna de acciones
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Inicializar DataTables
        const table = $("#tabla-coordinadores").DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay coordinadores registrados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ coordinadores",
                infoEmpty: "Mostrando 0 coordinadores",
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
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Excluye columna de acciones (4)
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
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - COORDINADORES</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE COORDINADORES</h2>' +
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
                        columns: [0, 1, 2, 3] // Excluye columna de acciones (4)
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Coordinadores Registrados',
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Excluye columna de acciones (4)
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
                { targets: [1, 4], className: 'text-center' }
            ]
        });

        // Confirmación de eliminación con SweetAlert
        $('.btn-eliminar').on('click', function() {
            const coordinadorId = $(this).data('id');
            const coordinadorName = $(this).data('name');

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¡Vas a eliminar al coordinador "${coordinadorName}"!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear y enviar el formulario de eliminación
                    const form = $(`<form method="POST" action="/coordinadores/${coordinadorId}">`);
                    form.append('@csrf');
                    form.append('@method("DELETE")');
                    $('body').append(form);
                    form.submit();
                }
            });
        });

        // Script para llenar el modal de visualización
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const name = button.data('name');
            const cedula = button.data('cedula');
            const email = button.data('email');

            const modal = $(this);
            modal.find('#modalNombre').text(name);
            modal.find('#modalCedula').text(cedula);
            modal.find('#modalEmail').text(email);
        });

        // Script para llenar el modal de edición
        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const name = button.data('name');
            const cedula = button.data('cedula');
            const email = button.data('email');

            const modal = $(this);
            modal.find('#cedula_editar').val(cedula);
            modal.find('#name_editar').val(name);
            modal.find('#email_editar').val(email);
            modal.find('#formEditar').attr('action', '/coordinadores/' + cedula);
        });
    });
</script>
@endpush
@endsection
