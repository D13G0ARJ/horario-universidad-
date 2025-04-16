<div class="modal fade" id="mostrarSeccionModal" tabindex="-1" aria-labelledby="mostrarSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalles Completos de la Sección
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">
                                <i class="fas fa-barcode me-2"></i>Código:
                            </label>
                            <p id="modalCodigo" class="form-control-plaintext ps-4"></p>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="fw-bold">
                                <i class="fas fa-building me-2"></i>Aula:
                            </label>
                            <p id="modalAula" class="form-control-plaintext ps-4"></p>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">
                                <i class="fas fa-graduation-cap me-2"></i>Carrera:
                            </label>
                            <p id="modalCarrera" class="form-control-plaintext ps-4"></p>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="fw-bold">
                                <i class="fas fa-clock me-2"></i>Turno:
                            </label>
                            <p id="modalTurno" class="form-control-plaintext ps-4"></p>
                        </div>
                    </div>
                </div>

                <!-- Semestre - Full Width -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="fw-bold">
                                <i class="fas fa-calendar-alt me-2"></i>Semestre:
                            </label>
                            <p id="modalSemestre" class="form-control-plaintext ps-4"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>