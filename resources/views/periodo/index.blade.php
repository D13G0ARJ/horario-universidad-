{{-- filepath: c:\Users\Alexa\OneDrive\Escritorio\horario-universidad-\resources\views\periodo\index.blade.php --}}
@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Gestión de Períodos Académicos</h1>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Períodos Registrados</h3>
                <div class="card-tools">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registroModal">
                        Nuevo Período
                    </a>
                </div>
            </div>
            <div class="card-body col-12" style="width: 100%;">
                <table class="table table-bordered table-striped table-hover w-100" id="tabla">
                    <thead>
                        <tr>
                            <th>
                                <center>Nro</center>
                            </th>
                            <th>
                                <center>Nombre</center>
                            </th>
                            <th>
                                <center>Fecha de Inicio</center>
                            </th>
                            <th>
                                <center>Fecha de Fin</center>
                            </th>
                            <th>
                                <center>Acciones</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $contador = 0; @endphp

                        @foreach ($periodos as $periodo)
                        @php $contador++; @endphp
                        <tr>
                            <td>{{ $contador }}</td>
                            <td>{{ $periodo->nombre }}</td>
                            <td>{{ $periodo->fecha_inicio }}</td>
                            <td>{{ $periodo->fecha_fin }}</td>
                            <td style="text-align: center;">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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

<!-- Modal de registro -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registroModalLabel">Registrar Nuevo Período</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('periodo.store') }}">
                    @csrf

                    <!-- Campo de Nombre -->
                    <div class="form-group mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input id="nombre" type="text" class="form-control" name="nombre" placeholder="Nombre del período" required>
                    </div>

                    <!-- Campo de Fecha de Inicio -->
                    <div class="form-group mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <input id="fecha_inicio" type="date" class="form-control" name="fecha_inicio" required>
                    </div>

                    <!-- Campo de Fecha de Fin -->
                    <div class="form-group mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                        <input id="fecha_fin" type="date" class="form-control" name="fecha_fin" required>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Registrar Período</button>
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
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Período</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre:</label>
                    <p id="modalNombre"></p>
                </div>
                <div class="form-group">
                    <label>Fecha de Inicio:</label>
                    <p id="modalFechaInicio"></p>
                </div>
                <div class="form-group">
                    <label>Fecha de Fin:</label>
                    <p id="modalFechaFin"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Período</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="form-group mb-3">
                        <label for="nombre_editar" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre_editar" required>
                    </div>

                    <!-- Fecha de Inicio -->
                    <div class="form-group mb-3">
                        <label for="fecha_inicio_editar" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio_editar" required>
                    </div>

                    <!-- Fecha de Fin -->
                    <div class="form-group mb-3">
                        <label for="fecha_fin_editar" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin_editar" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mostrarModal = document.getElementById('mostrarModal');
        mostrarModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const nombre = button.getAttribute('data-nombre');
            const fechaInicio = button.getAttribute('data-fecha_inicio');
            const fechaFin = button.getAttribute('data-fecha_fin');

            mostrarModal.querySelector('#modalNombre').textContent = nombre;
            mostrarModal.querySelector('#modalFechaInicio').textContent = fechaInicio;
            mostrarModal.querySelector('#modalFechaFin').textContent = fechaFin;
        });

        const editarModal = document.getElementById('editarModal');
        editarModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const fechaInicio = button.getAttribute('data-fecha_inicio');
            const fechaFin = button.getAttribute('data-fecha_fin');

            const form = document.getElementById('formEditar');
            form.action = `/periodos/${id}`;

            document.getElementById('nombre_editar').value = nombre;
            document.getElementById('fecha_inicio_editar').value = fechaInicio;
            document.getElementById('fecha_fin_editar').value = fechaFin;
        });
    });
</script>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabla').DataTable({
            responsive: true,
            autoWidth: false,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    className: 'text-center',
                    orderable: false
                },
                {
                    targets: [1, 2, 3],
                    className: 'text-center'
                },
                {
                    targets: 4,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '140px'
                }
            ],
            initComplete: function() {
                $('.dataTables_filter input')
                    .addClass('form-control form-control-sm  text-black')
                    .attr('placeholder', 'Buscar...');

                $('.dataTables_length select')
                    .addClass('form-select form-select-sm  text-black');

                $('.dataTables_paginate').addClass('mt-3');
            }
        });
    });
</script>

@endpush

@endsection