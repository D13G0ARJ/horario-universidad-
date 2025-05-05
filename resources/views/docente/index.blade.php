
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

    @if($errors->any())
    <div class="alert alert-danger" id="autoCloseAlert"> 
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('autoCloseAlert').style.display = 'none';
        }, 3000);
    </script>
    @endif

    <!-- Tabla de docentes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Docentes Registrados
                    </h4>
                    <a href="#" class="btn btn-success ms-auto text-dark"
                        data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="fas fa-plus mr-1"></i>Nuevo Docente
                    </a>
                </div>
                <div class="card-body">
                    <table id="tabla-docentes" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">Cédula</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Correo</th>
                                <th style="text-align: center">Teléfono</th>
                                <th style="text-align: center">Dedicación</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($docentes as $docente)
                            <tr>
                                <td style="text-align: center">{{ $docente->cedula_doc }}</td>
                                <td>{{ $docente->name }}</td>
                                <td>{{ $docente->email }}</td>
                                <td>{{ $docente->telefono }}</td>
                                <td style="text-align: center">{{ $docente->dedicacion->dedicacion ?? 'Sin dedicación' }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-cedula="{{ $docente->cedula_doc }}"
                                            data-name="{{ $docente->name }}"
                                            data-email="{{ $docente->email }}"
                                            data-telefono="{{ $docente->telefono }}"
                                            data-dedicacion="{{ $docente->dedicacion->dedicacion ?? 'Sin dedicación' }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal"
                                            data-cedula="{{ $docente->cedula_doc }}"
                                            data-name="{{ $docente->name }}"
                                            data-email="{{ $docente->email }}"
                                            data-telefono="{{ $docente->telefono }}"
                                            data-dedicacion="{{ $docente->dedicacion->dedicacion ?? '' }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <button class="btn btn-danger btn-sm btn-eliminar"
                                            data-id="{{ $docente->cedula_doc }}"
                                            data-name="{{ $docente->name }}">
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

<!-- Modals -->
@include('modals.docentes.create')
@include('modals.docentes.show')
@include('modals.docentes.edit')

@push('scripts')
<script>
    $(document).ready(function() {
        // Configuración del DataTable
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
            buttons: [{
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    autoPrint: true,
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">REPORTE DE DOCENTES</h3>' +
                                '</div>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    className: 'btn btn-success mr-2'
                },
            ],
            columnDefs: [{
                targets: [0, 3, 4],
                className: 'text-center'
            }]
        });

        // Confirmación de eliminación con SweetAlert
        $('.btn-eliminar').on('click', function() {
            const docenteId = $(this).data('id');
            const docenteName = $(this).data('name');

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¡Vas a eliminar al docente "${docenteName}"!`,
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
                    const form = $(`<form method="POST" action="/docentes/${docenteId}">`);
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
            const modal = $(this);
            modal.find('#modalCedula').text(button.data('cedula'));
            modal.find('#modalName').text(button.data('name'));
            modal.find('#modalEmail').text(button.data('email'));
            modal.find('#modalTelefono').text(button.data('telefono'));
            modal.find('#modalDedicacion').text(button.data('dedicacion'));

            var docenteId = button.data('cedula');

            $.get(`/api/docentes/${docenteId}/asignaturas`, function(response) {
                const asignaturasContainer = $('#asignaturasContainer');
        
                    if (response.asignaturas && response.asignaturas.length > 0) {
                        const listaAsignaturas = response.asignaturas
                        .map(asig => 
                            `<dt class="col-sm-4">${asig.asignatura_id}</dt>
                            <dd class="col-sm-8">${asig.name}</dd>
                            `
                        )
                        .join('');
                        asignaturasContainer.html(`<ul class="list-group">${listaAsignaturas}</ul>`);
                    } else {
                        asignaturasContainer.html('<div class="alert alert-warning">No tiene asignaturas asociadas</div>');
                    }
                }).fail(function() {
                    $('#asignaturasContainer').html('<div class="alert alert-danger">Error al cargar asignaturas</div>');
                });
        });

        // Script para llenar el modal de edición
        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            modal.find('#cedula_editar').val(button.data('cedula'));
            modal.find('#name_editar').val(button.data('name'));
            modal.find('#email_editar').val(button.data('email'));
            modal.find('#telefono_editar').val(button.data('telefono'));
            modal.find('#dedicacion_editar').val(button.data('dedicacion'));
            modal.find('#formEditar').attr('action', '/docentes/' + button.data('cedula'));
        });
    });
</script>
@endpush
@endsection