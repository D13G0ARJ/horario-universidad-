@extends('layouts.admin')

@section('content')
<div class="container">
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearSeccionModal">
                            <i class="fas fa-plus-circle"></i> Nueva Sección
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card shadow">
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <table class="table table-bordered table-striped table-hover w-100" id="secciones-table">
                                <thead>
                                    <tr>
                                        <th><center>Nro</center></th>
                                        <th><center>Nombre</center></th>
                                        <th><center>Aula</center></th>
                                        <th><center>Acciones</center></th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @foreach ($secciones as $seccion)
                                    <tr>
                                        <td></td>
                                        <td>{{ $seccion->nombre }}</td>
                                        <td>{{ $seccion->aula->nombre }}</td>
                                        <td style="text-align: center;">
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                                <!-- Botón Editar -->
                                                <button class="btn btn-success btn-sm btn-editar" 
                                                    data-id="{{ $seccion->id }}"
                                                    title="Editar">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>

                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('secciones.destroy', $seccion->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Eliminar"
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

    <!-- Modal Crear Sección -->
    <div class="modal fade" id="crearSeccionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Sección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('secciones.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Nombre -->
                        <div class="form-group mb-3">
                            <label for="nombre" class="form-label text-secondary small">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-tag small"></i>
                                </span>
                                <input type="text"
                                    class="form-control form-control-md @error('nombre') is-invalid @enderror"
                                    name="nombre"
                                    id="nombre"
                                    value="{{ old('nombre') }}"
                                    placeholder="Nombre de la sección"
                                    required>
                            </div>
                            @error('nombre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Aula -->
                        <div class="form-group mb-3">
                            <label for="aula_id" class="form-label text-secondary small">Aula</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-building small"></i>
                                </span>
                                <select class="form-select form-control-md @error('aula_id') is-invalid @enderror"
                                    name="aula_id"
                                    id="aula_id"
                                    required>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2 small"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Sección -->
    <div class="modal fade" id="editarSeccionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Sección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editarSeccionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Nombre -->
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

                        <!-- Aula -->
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2 small"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Verificar si ya está inicializada la DataTable
        if (!$.fn.dataTable.isDataTable('#secciones-table')) {
            $('#secciones-table').DataTable({
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
                        .addClass('form-control form-control-sm text-black')
                        .attr('placeholder', 'Buscar...');

                    $('.dataTables_length select')
                        .addClass('form-select form-select-sm text-black');

                    $('.dataTables_paginate').addClass('mt-3');
                }
            });
        }


        // Manejo de edición
        $(document).on('click', '.btn-editar', function() {
            const seccionId = $(this).data('id');
            
            $.ajax({
                url: `/secciones/${seccionId}/edit`,
                method: 'GET',
                success: function(data) {
                    $('#edit_nombre').val(data.seccion.nombre);
                    $('#edit_aula_id').val(data.seccion.aula_id);
                    $('#editarSeccionForm').attr('action', `/secciones/${seccionId}`);
                    $('#editarSeccionModal').modal('show');
                },
                error: function(xhr) {
                    alert('Error al cargar datos');
                }
            });
        });

        $('#editarSeccionForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editarSeccionModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.error);
                }
            });
        });
    });
</script>
@endpush