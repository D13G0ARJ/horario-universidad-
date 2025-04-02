<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Editar Carrera
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditar">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="code_editar" class="form-label">Código</label>
                        <input type="text" class="form-control" name="code" id="code_editar" readonly required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name_editar" required>
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