@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <h1>Listado de Carreras</h1>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Carreras Registradas</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registroModal">
                            Nueva carrera
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card shadow">
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover w-100" id="tabla_carrera">
                                <thead>
                                    <tr>
                                        <th>
                                            <center>Nro</center>
                                        </th>
                                        <th>
                                            <center>Código</center>
                                        </th>
                                        <th>
                                            <center>Nombre</center>
                                        </th>
                                        <th>
                                            <center>Acciones</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @foreach ($carreras as $carrera)
                                    <tr>
                                        <td></td>
                                        <td>{{ $carrera->code }}</td>
                                        <td>{{ $carrera->name }}</td>
                                        <td style="text-align: center;">
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <!-- Botón para Mostrar -->
                                                <button class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#mostrarModal"
                                                    data-id="{{ $carrera->id }}"
                                                    data-name="{{ $carrera->name }}"
                                                    data-code="{{ $carrera->code }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Botón para Editar -->
                                                <button class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editarModal"
                                                    data-id="{{ $carrera->id }}"
                                                    data-name="{{ $carrera->name }}"
                                                    data-code="{{ $carrera->code }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>

                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('carrera.destroy', $carrera->code) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Estás seguro?')">
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
    </div>

    <!-- Modal de registro -->
    <!-- Modal -->
    <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-registro" id="registroModalLabel">Registrar Nueva Carrera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de registro -->
                    <form method="POST" action="{{ route('carrera.store') }}">
                        @csrf

                        <!-- Campo de Código -->
                        <div class="form-group mb-3">
                            <label for="code" class="form-label text-secondary small">Cédula</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-id-card small"></i>
                                </span>
                                <input id="code" type="text"
                                    class="form-control form-control-md @error('code') is-invalid @enderror"
                                    name="code" placeholder="Código" value="{{ old('code') }}" required autofocus>
                            </div>
                            @error('code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo de Nombre -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label text-secondary small">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-user small"></i>
                                </span>
                                <input id="name" type="text"
                                    class="form-control form-control-md @error('name') is-invalid @enderror"
                                    name="name" placeholder="Nombre" value="{{ old('name') }}" required>
                            </div>
                            @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botón de Registro -->
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                                <i class="fas fa-user-plus me-2 small"></i>Registrar carrera
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
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la carrera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Código:</label>
                        <p id="modalCode"></p>
                    </div>
                    <div class="form-group">
                        <label>Nombre:</label>
                        <p id="modalName"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para llenar el modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mostrarModal = document.getElementById('mostrarModal');
            mostrarModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const code = button.getAttribute('data-code');
                const name = button.getAttribute('data-name');

                // Actualizar los campos del modal
                mostrarModal.querySelector('#modalCode').textContent = code;
                mostrarModal.querySelector('#modalName').textContent = name;
            });
        });
    </script>

    <!-- Modal para Editar -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Carrera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('carrera.update', $carrera->code) }}" id="formEditar">
                        @csrf
                        @method('PUT')

                        <!-- Código (solo lectura) -->
                        <div class="form-group mb-3">
                            <label for="code_editar" class="form-label">Código</label>
                            <input type="text"
                                class="form-control"
                                name="code"
                                id="code_editar"
                                readonly required> <!-- Bloquea la edición -->
                            @error('code')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div class="form-group mb-3">
                            <label for="name_editar" class="form-label">Nombre</label>
                            <input type="text"
                                class="form-control"
                                name="name"
                                id="name_editar"
                                required>
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
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
            const editarModal = document.getElementById('editarModal');
            editarModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Obtener datos del botón
                const code = button.getAttribute('data-code');
                const name = button.getAttribute('data-name');

                // Actualizar el formulario
                const form = document.getElementById('formEditar');
                form.action = `/carreras/${code}`; // Actualizar la URL de la acción

                // Llenar los campos
                document.getElementById('code_editar').value = code;
                document.getElementById('name_editar').value = name;
            });
        });
    </script>




    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#tabla_carrera').DataTable({
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
                        targets: [1, 2],
                        className: 'text-center'
                    },
                    {
                        targets: 3,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '120px'
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