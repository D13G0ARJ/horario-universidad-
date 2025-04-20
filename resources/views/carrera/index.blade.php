
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-graduation-cap mr-2"></i>Listado de Carreras
            </h3>
        </div>
    </div>

    <!-- Tabla de carreras -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Carreras Registradas
                    </h4>
                    <a href="#" class="btn btn-success ms-auto text-dark"
                        data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="fas fa-plus mr-1"></i>Nueva Carrera
                    </a>
                </div>
                <div class="card-body">
                    <table id="tabla-carreras" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th style="text-align: center">Código</th>
                                <th>Nombre</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carreras as $carrera)
                            <tr>
                                <td style="text-align: center"></td>
                                <td style="text-align: center">{{ $carrera->carrera_id }}</td>
                                <td>{{ $carrera->name }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-carrera_id="{{ $carrera->carrera_id }}"
                                            data-name="{{ $carrera->name }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal"
                                            data-carrera_id="{{ $carrera->carrera_id }}"
                                            data-name="{{ $carrera->name }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <form action="{{ route('carrera.destroy', $carrera->carrera_id) }}" method="POST" class="delete-form">
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

<!-- Modals -->
@include('modals.carreras.create')
@include('modals.carreras.show')
@include('modals.carreras.edit')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Configuración del DataTable
        const table = $("#tabla-carreras").DataTable({
            pageLength: 10,
            responsive: true,
            autoWidth: false,
            lengthChange: true,
            language: {
                emptyTable: "No hay carreras registradas",
                info: "Mostrando _START_ a _END_ de _TOTAL_ carreras",
                infoEmpty: "Mostrando 0 carreras",
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
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: 'Reporte de Carreras',
                    exportOptions: {
                        columns: [0, 1, 2]
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
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - CARRERAS</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE CARRERAS</h2>' +
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
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2]
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Carreras Registradas',
                    exportOptions: {
                        columns: [0, 1, 2]
                    },
                    className: 'btn btn-success mr-2'
                }
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
                    targets: [1, 3], 
                    className: 'text-center'
                },
                { 
                    targets: -1,
                    searchable: false,
                    orderable: false
                }
            ]
        });

        

        // Confirmación de eliminación con SweetAlert
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: '¿Eliminar Carrera?',
                text: "¡Esta acción no se puede deshacer!",
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

        // SweetAlert para notificaciones
        @if(session('alert'))
            Swal.fire({
                icon: '{{ session('alert')['type'] }}',
                title: '{{ session('alert')['title'] }}',
                text: '{{ session('alert')['message'] }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
        
        // Handlers para modals
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            modal.find('#modalCode').text(button.data('carrera_id'));
            modal.find('#modalName').text(button.data('name'));
        });

        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            modal.find('#carrera_id_editar').val(button.data('carrera_id'));
            modal.find('#name_editar').val(button.data('name'));
            modal.find('#formEditar').attr('action', '/carreras/' + button.data('carrera_id'));
        });
    });
</script>
@endpush
@endsection