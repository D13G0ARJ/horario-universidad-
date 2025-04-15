<!-- Modal de registro -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="registroModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Registrar Nuevo Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('docente.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="cedula_doc" class="form-label">Cédula</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input id="cedula_doc" type="text" class="form-control" name="cedula_doc" placeholder="Cédula" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input id="name" type="text" class="form-control" name="name" placeholder="Nombre" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Correo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input id="email" type="email" class="form-control" name="email" placeholder="Correo" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input id="telefono" type="text" class="form-control" name="telefono" placeholder="Teléfono" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Registrar Docente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>