@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Listado de Docentes</h1>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Docentes Registrados</h3>
                <div class="card-tools">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registroModal">
                        Nuevo docente
                    </a>
                </div>
            </div>
            <div class="card-body col-12" style="width: 100%;">
                <table class="table table-bordered table-striped table-hover w-100" id="tabla">
                    <thead>
                        <tr>
                            <th><center>Nro</center></th>
                            <th><center>Nombre</center></th>
                            <th><center>Correo</center></th>
                            <th><center>Teléfono</center></th>
                            <th><center>Asignaturas</center></th>
                            <th><center>Acciones</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $contador = 0; @endphp

                        @foreach ($docentes as $docente)
                        @php $contador++; @endphp
                        <tr>
                            <td>{{ $contador }}</td>
                            <td>{{ $docente->name }}</td>
                            <td>{{ $docente->email }}</td>
                            <td>{{ $docente->phone }}</td>
                            <td>
                                <ul>
                                    @foreach ($docente->asignaturas as $asignatura)
                                    <li>{{ $asignatura->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="text-align: center;">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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
                                        data-phone="{{ $docente->phone }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Botón para Eliminar -->
                                    <form action="{{ route('docente.destroy', $docente->id) }}" method="POST">
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
                <h5 class="modal-title" id="registroModalLabel">Registrar Nuevo Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('docente.store') }}">
                    @csrf

                    <!-- Campo de Nombre -->
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input id="name" type="text" class="form-control" name="name" placeholder="Nombre" required>
                    </div>

                    <!-- Campo de Correo -->
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Correo</label>
                        <input id="email" type="email" class="form-control" name="email" placeholder="Correo" required>
                    </div>

                    <!-- Campo de Teléfono -->
                    <div class="form-group mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input id="phone" type="text" class="form-control" name="phone" placeholder="Teléfono" required>
                    </div>

                    <!-- Campo de Asignaturas -->
                    <div class="form-group mb-3">
                        <label for="asignaturas" class="form-label">Asignaturas</label>
                        <select id="asignaturas" class="form-control" name="asignaturas[]" multiple>
                            @foreach ($asignaturas as $asignatura)
                            <option value="{{ $asignatura->id }}">{{ $asignatura->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Registrar Docente</button>
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
                <h5 class="modal-title">Detalles del Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre:</label>
                    <p id="modalName"></p>
                </div>
                <div class="form-group">
                    <label>Correo:</label>
                    <p id="modalEmail"></p>
                </div>
                <div class="form-group">
                    <label>Teléfono:</label>
                    <p id="modalPhone"></p>
                </div>
                <div class="form-group">
                    <label>Asignaturas:</label>
                    <p id="modalAsignaturas"></p>
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
                <h5 class="modal-title">Editar Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name_editar" required>
                    </div>

                    <!-- Correo -->
                    <div class="form-group mb-3">
                        <label for="email_editar" class="form-label">Correo</label>
                        <input type="email" class="form-control" name="email" id="email_editar" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="form-group mb-3">
                        <label for="phone_editar" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="phone" id="phone_editar" required>
                    </div>

                    <!-- Asignaturas -->
                    <div class="form-group mb-3">
                        <label for="asignaturas_editar" class="form-label">Asignaturas</label>
                        <select id="asignaturas_editar" class="form-control" name="asignaturas[]" multiple>
                            @foreach ($asignaturas as $asignatura)
                            <option value="{{ $asignatura->id }}">{{ $asignatura->name }}</option>
                            @endforeach
                        </select>
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
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone');
            const asignaturas = button.getAttribute('data-asignaturas');

            mostrarModal.querySelector('#modalName').textContent = name;
            mostrarModal.querySelector('#modalEmail').textContent = email;
            mostrarModal.querySelector('#modalPhone').textContent = phone;
            mostrarModal.querySelector('#modalAsignaturas').textContent = asignaturas;
        });

        const editarModal = document.getElementById('editarModal');
        editarModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone');

            const form = document.getElementById('formEditar');
            form.action = `/docentes/${id}`;

            document.getElementById('name_editar').value = name;
            document.getElementById('email_editar').value = email;
            document.getElementById('phone_editar').value = phone;
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
            columnDefs: [
                { 
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    className: 'text-center',
                    orderable: false
                },
                { targets: [1,2,3,4], className: 'text-center' },
                { 
                    targets: 5, 
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
