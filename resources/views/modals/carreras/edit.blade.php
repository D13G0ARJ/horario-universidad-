<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar Carrera
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <!-- Campo Código (readonly) -->
                    <div class="form-group mb-4">
                        <label for="carrera_id_editar" class="form-label fw-bold">Código</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text" 
                                class="form-control" 
                                id="carrera_id_editar" 
                                name="carrera_id" 
                                readonly
                                required>
                        </div>
                    </div>

                    <!-- Campo Nombre -->
                    <div class="form-group mb-4">
                        <label for="name_editar" class="form-label fw-bold">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            <input type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name_editar" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>