@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Gestión de Secciones</h1>

    <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearSeccionModal">
        <i class="fas fa-plus-circle"></i> Agregar Sección
    </button>

    <!-- Mensajes de éxito/error -->
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

    <!-- Tabla de secciones -->
    <div class="card shadow">
        <div class="card-body">
            <table id="secciones-table" class="table table-hover" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Aula</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($secciones as $seccion)
                    <tr>
                        <td>{{ $seccion->id }}</td>
                        <td>{{ $seccion->nombre }}</td>
                        <td>{{ $seccion->aula->nombre }}</td>
                        <td>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-warning btn-editar" 
                data-id="{{ $seccion->id }}"
                title="Editar">
            <i class="fas fa-edit"></i>
        </button>
        
        <form action="{{ route('secciones.destroy', $seccion->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="btn btn-sm btn-danger" 
                    title="Eliminar"
                    onclick="return confirm('¿Estás seguro?')">
                <i class="fas fa-trash-alt"></i>
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

<!-- Modal para crear sección -->
<div class="modal fade" id="crearSeccionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nueva Sección</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('secciones.store') }}" method="POST" id="crearSeccionForm">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la sección</label>
                        <input type="text"
                            class="form-control @error('nombre') is-invalid @enderror"
                            name="nombre"
                            id="nombre"
                            value="{{ old('nombre') }}"
                            required>
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="aula_id" class="form-label">Seleccionar Aula</label>
                        <select class="form-select @error('aula_id') is-invalid @enderror"
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
                        @error('aula_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal de Edición -->
<div class="modal fade" id="editarSeccionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Editar Sección</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editarSeccionForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre de la sección</label>
                        <input type="text"
                            class="form-control"
                            name="nombre"
                            id="edit_nombre"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_aula_id" class="form-label">Seleccionar Aula</label>
                        <select class="form-select"
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
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>


@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#secciones-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            order: [[0, 'desc']]
        });

        // Código para editar
        $('.btn-editar').click(function() {
            const seccionId = $(this).data('id');
            $.get(`/secciones/${seccionId}/edit`, function(data) {
                $('#edit_nombre').val(data.seccion.nombre);
                $('#edit_aula_id').val(data.seccion.aula_id);
                $('#editarSeccionForm').attr('action', `/secciones/${seccionId}`);
                $('#editarSeccionModal').modal('show');
            });
        });

        // Envío del formulario de edición
        $('#editarSeccionForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function() {
                    location.reload();
                }
            });
        });
    });
</script>
@endpush