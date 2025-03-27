@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Gestión de Secciones</h1>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Secciones Registradas</h3>
                <div class="card-tools">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearSeccionModal">
                        <i class="fas fa-plus-circle"></i> Nueva Sección
                    </a>
                </div>
            </div>
            <div class="card-body col-12" style="width: 100%;">
                <table class="table table-bordered table-striped table-hover w-100" id="tabla">
                    <thead>
                        <tr>
                            <th><center>Nro</center></th>
                            <th><center>Nombre</center></th>
                            <th><center>Aula</center></th>
                            <th><center>Acciones</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($secciones as $seccion)
                        <tr>
                            <td></td> <!-- Dejamos vacío para DataTables -->
                            <td>{{ $seccion->nombre }}</td>
                            <td>{{ $seccion->aula->nombre }}</td>
                            <td style="text-align: center;">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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

                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('secciones.destroy', $seccion->id) }}" method="POST">
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

<!-- Modal de Registro de Secciones -->
<div class="modal fade" id="crearSeccionModal" tabindex="-1" aria-labelledby="crearSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar Nueva Sección</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('secciones.store') }}">
                    @csrf

                    <!-- Campo de Nombre -->
                    <div class="form-group mb-3">
                        <label for="nombre" class="form-label text-secondary small">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text py-2">
                                <i class="fas fa-tag small"></i>
                            </span>
                            <input id="nombre" type="text"
                                class="form-control form-control-md @error('nombre') is-invalid @enderror"
                                name="nombre" placeholder="Nombre de la sección" value="{{ old('nombre') }}" required>
                        </div>
                        @error('nombre')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campo de Aula -->
                    <div class="form-group mb-3">
                        <label for="aula_id" class="form-label text-secondary small">Aula</label>
                        <div class="input-group">
                            <span class="input-group-text py-2">
                                <i class="fas fa-building small"></i>
                            </span>
                            <select class="form-select form-control-md @error('aula_id') is-invalid @enderror"
                                name="aula_id" required>
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

                    <!-- Botón de Registro -->
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                            <i class="fas fa-save me-2 small"></i>Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Mostrar Sección -->
<div class="modal fade" id="mostrarSeccionModal" tabindex="-1" aria-labelledby="mostrarSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Sección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre:</label>
                    <p id="modalSeccionNombre"></p>
                </div>
                <div class="form-group">
                    <label>Aula:</label>
                    <p id="modalSeccionAula"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Sección -->
<div class="modal fade" id="editarSeccionModal" tabindex="-1" aria-labelledby="editarSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Sección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('secciones.update', '') }}" id="formEditarSeccion">
                    @csrf
                    @method('PUT')

                    <!-- ID oculto -->
                    <input type="hidden" name="id" id="edit_id">

                    <!-- Campo de Nombre -->
                    <div class="form-group mb-3">
                        <label for="edit_nombre" class="form-label text-secondary small">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text py-2">
                                <i class="fas fa-tag small"></i>
                            </span>
                            <input type="text"
                                class="form-control form-control-md"
                                name="nombre"
                                id="edit_nombre"
                                required>
                        </div>
                    </div>

                    <!-- Campo de Aula -->
                    <div class="form-group mb-3">
                        <label for="edit_aula_id" class="form-label text-secondary small">Aula</label>
                        <div class="input-group">
                            <span class="input-group-text py-2">
                                <i class="fas fa-building small"></i>
                            </span>
                            <select class="form-select form-control-md"
                                name="aula_id"
                                id="edit_aula_id"
                                required>
                                @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}">{{ $aula->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2 small"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración de DataTables
        $('#tabla').DataTable({
            pageLength: 5,
            responsive: true,
            autoWidth: false,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            columnDefs: [
                { 
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    orderable: false,
                    searchable: false
                },
                { targets: 3, orderable: false, searchable: false }
            ],
            order: [[1, 'asc']]
        });

        // Script para Mostrar Modal
        const mostrarModal = document.getElementById('mostrarSeccionModal');
        mostrarModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            mostrarModal.querySelector('#modalSeccionNombre').textContent = button.getAttribute('data-nombre');
            mostrarModal.querySelector('#modalSeccionAula').textContent = button.getAttribute('data-aula');
        });

        // Script para Editar Modal
        const editarModal = document.getElementById('editarSeccionModal');
        editarModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const form = document.getElementById('formEditarSeccion');
            
            form.action = `/secciones/${button.getAttribute('data-id')}`;
            document.getElementById('edit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_nombre').value = button.getAttribute('data-nombre');
            document.getElementById('edit_aula_id').value = button.getAttribute('data-aula_id');
        });
    });
</script>
@endpush

@endsection