<!-- Modal para Editar -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Editar Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="cedula_editar" class="form-label">Cédula</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control" name="cedula_doc" id="cedula_editar" readonly>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" name="name" id="name_editar" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email_editar" class="form-label">Correo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email" id="email_editar" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="telefono_editar" class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control" name="telefono" id="telefono_editar" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="dedicacion_editar" class="form-label">Dedicación</label>
                        <div class="select-group">
                            <span class="select-group-text"><i class="fa fa-clock-o"></i></span>
                            <select class="form-select @error('dedicacion_id') is-invalid @enderror" 
                                name="dedicacion_id" 
                                id="dedicacion_id" 
                                required>
                                <option value="">Seleccione...</option>
                                @foreach($dedicaciones as $dedicacion)
                                    <option value="{{ $dedicacion->dedicacion_id }}" {{-- Usar dedicacion_id --}}
                                        {{ old('dedicacion_id') == $dedicacion->dedicacion_id ? 'selected' : '' }}>
                                        {{ $dedicacion->dedicacion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('dedicacion_id')
                            <div class="invalid-feedback d-block small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>