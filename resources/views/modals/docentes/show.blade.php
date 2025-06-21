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

                                    <dt class="col-sm-4">Correo:</dt>
                                    <dd class="col-sm-8" id="modalEmail"></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3 border-success">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-briefcase"></i> Dedicación del Docente
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Dedicación:</dt>
                                    <dd class="col-sm-8" id="modalDedicacion"></dd>

                                    <dt class="col-sm-4">Horas Máx:</dt>
                                    <dd class="col-sm-8" id="modalHorasMax"></dd>

                                    <dt class="col-sm-4">Total Horas Asign.:</dt>
                                    <dd class="col-sm-8" id="totalHorasDocente">Cargando...</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3 border-info">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-book"></i> Asignaturas Asignadas
                    </div>
                    <div class="card-body">
                        <ul id="modalAsignaturasList" class="list-group list-group-flush">
                            </ul>
                        <p id="noAsignaturasMessage" class="text-muted mt-2" style="display: none;">
                            Este docente no tiene asignaturas asignadas.
                        </p>
                    </div>
                </div>

                <div class="card mb-3 border-warning">
                    <div class="card-header bg-warning text-white">
                        <i class="fas fa-calendar-alt"></i> Ver Horario
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="periodoSelect" class="form-label">Seleccione un Período Académico:</label>
                            <select class="form-select" id="periodoSelect">
                                </select>
                        </div>
                        <button type="button" class="btn btn-warning" id="btnCargarHorario">
                            <i class="fas fa-eye"></i> Mostrar Horario
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>