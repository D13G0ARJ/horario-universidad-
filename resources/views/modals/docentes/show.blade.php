<div class="modal fade" id="mostrarModal" tabindex="-1" aria-labelledby="mostrarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i>Detalles Completo del Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6">
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-id-card"></i> Información Personal y de Contacto
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Cédula:</dt>
                                    <dd class="col-sm-8" id="modalCedula"></dd>

                                    <dt class="col-sm-4">Nombre:</dt>
                                    <dd class="col-sm-8" id="modalName"></dd>

                                    <dt class="col-sm-4">Teléfono:</dt>
                                    <dd class="col-sm-8" id="modalTelefono"></dd>

                                    <dt class="col-sm-4">Email:</dt>
                                    <dd class="col-sm-8" id="modalEmail"></dd>
                                </dl>
                            </div>
                        </div>
                        <div class="card mb-3 border-info">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-users"></i> Información Académica
                            </div>
                            <div class="card-body">
                                <dd class="col-sm-8" id="modalDedicacion"></dd>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-building"></i> Asignaturas asociadas al docente
                            </div>
                            <div class="card-body" id="asignaturasContainer">
                            <!-- Las asignaturas se cargarán aquí dinámicamente -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        border-radius: 0.7rem;
    }
    .card-header {
        font-weight: 500;
    }
    dt {
        color: #6c757d;
    }
    dd {
        color: #2c3e50;
        font-weight: 500;
    }
    .badge-detail {
        font-size: 0.9em;
        padding: 0.5em 0.8em;
        margin: 0.2em;
        display: inline-block;
    }
</style>