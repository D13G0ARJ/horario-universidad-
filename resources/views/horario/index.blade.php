
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-calendar-alt mr-2"></i>Gestión de Horarios
            </h3>
        </div>
    </div>

    <!-- Tabla de horarios -->
    <div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="fas fa-list-alt mr-2"></i>Horarios Registrados
                </h4>
                <a href="{{ route('horarios.create') }}" class="btn btn-success ms-auto text-white">
    <i class="fas fa-plus mr-1"></i> Nuevo Horario
</a>
            </div>
                <div class="card-body">
                    <table id="tabla-horarios" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Periodo</th>
                                <th>Carrera</th>
                                <th>Semestre</th>
                                <th>Sección</th>
                                <th>Turno</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($horarios as $horario)
                            <tr>
                                <td>{{ $horario->periodo->nombre ?? 'Sin periodo' }}</td>
                                <td>{{ $horario->carrera->nombre ?? 'Sin carrera' }}</td>
                                <td>{{ $horario->semestre->numero ?? 'Sin semestre' }}</td>
                                <td>{{ $horario->turno->nombre ?? 'Sin turno' }}</td>
                                <td>{{ $horario->seccion->codigo_seccion ?? 'Sin sección' }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Ver -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#verHorarioModal"
                                            data-id="{{ $horario->id }}"
                                            data-periodo="{{ $horario->periodo->nombre ?? 'Sin periodo' }}"
                                            data-carrera="{{ $horario->carrera->nombre ?? 'Sin carrera' }}"
                                            data-semestre="{{ $horario->semestre->numero ?? 'Sin semestre' }}"
                                            data-turno="{{ $horario->turno->nombre ?? 'Sin turno' }}"
                                            data-seccion="{{ $horario->seccion->codigo_seccion ?? 'Sin sección' }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarHorarioModal"
                                            data-id="{{ $horario->id }}"
                                            data-periodo="{{ $horario->periodo->id ?? '' }}"
                                            data-carrera="{{ $horario->carrera->id ?? '' }}"
                                            data-semestre="{{ $horario->semestre->id_semestre ?? '' }}"
                                            data-turno="{{ $horario->turno->id_turno ?? '' }}"
                                            data-seccion="{{ $horario->seccion->codigo_seccion ?? '' }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <button class="btn btn-danger btn-sm btn-eliminar"
                                            data-id="{{ $horario->id }}"
                                            data-periodo="{{ $horario->periodo->nombre ?? 'Sin periodo' }}">
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
@include('modals.horarios.create')

@push('scripts')
<script>
    $(document).ready(function() {
        // Configuración del DataTable
        const table = $("#tabla-horarios").DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay horarios registrados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ horarios",
                infoEmpty: "Mostrando 0 horarios",
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
                                '<h3 style="margin: 5px 0; font-size: 14pt;">REPORTE DE HORARIOS</h3>' +
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
            const horarioId = $(this).data('id');
            const periodoName = $(this).data('periodo');

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¡Vas a eliminar el horario del periodo "${periodoName}"!`,
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
                    const form = $(`<form method="POST" action="/horarios/${horarioId}">`);
                    form.append('@csrf');
                    form.append('@method("DELETE")');
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    });

    
</script>
@endpush
@endsection