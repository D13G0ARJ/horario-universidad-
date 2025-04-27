
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
                <a href="#" class="btn btn-success ms-auto text-white"
                    data-bs-toggle="modal" data-bs-target="#agregarHorarioModal">
                    <i class="fas fa-plus mr-1"></i>Nuevo Horario
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

<!-- Modal para agregar un nuevo horario -->
<div class="modal fade" id="agregarHorarioModal" tabindex="-1" aria-labelledby="agregarHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xxl"> <!-- Modal extra grande -->
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-3">
                <h2 class="modal-title fs-4" id="agregarHorarioModalLabel">Agregar Nuevo Horario</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('horario.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Fila de campos del formulario -->
                    <div class="row g-3 mb-4"> <!-- g-3 para más espacio entre columnas -->
                        <!-- Periodo -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="periodo" class="form-label fw-bold">Periodo</label>
                            <select id="periodo" name="periodo_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Carrera -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="carrera" class="form-label fw-bold">Carrera</label>
                            <select id="carrera" name="carrera_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}">{{ $carrera->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Semestre -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="semestre" class="form-label fw-bold">Semestre</label>
                            <select id="semestre" name="semestre_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($semestres as $semestre)
                                    <option value="{{ $semestre->id_semestre }}">Semestre {{ $semestre->numero }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Turno -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="turno" class="form-label fw-bold">Turno</label>
                            <select id="turno" name="turno_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Sección -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label for="seccion" class="form-label fw-bold">Sección</label>
                            <select id="seccion" name="seccion_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($secciones as $seccion)
                                    <option value="{{ $seccion->codigo_seccion }}">{{ $seccion->codigo_seccion }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Botón de búsqueda -->
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary btn-sm w-100" id="buscarHorarios">
                                <i class="fas fa-search me-1"></i> Buscar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Calendario - Ahora con más espacio -->
                    <div class="card border-0 shadow-sm mt-2">
                        <div class="card-body p-3">
                            <div class="calendar" style="min-height: 500px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Guardar Horario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Estilos personalizados para la modal extra grande */
    .modal-xxl {
        max-width: 150%;
    }
    
    @media (min-width: 1900px) {
        .modal-xxl {
            max-width: 1700px;
        }
    }
    
    /* Estilos para los selects */
    #agregarHorarioModal .form-select-sm {
        padding: 0.35rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Calendario más grande */
    #agregarHorarioModal .calendar {
        width: 150%;
        height: 150%;
        min-height: 500px;
        border-radius: 0.5rem;
    }
    
    /* Mejor espaciado para las etiquetas */
    #agregarHorarioModal .form-label {
        margin-bottom: 0.50rem;
        font-size: 0.85rem;
    }
</style>


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