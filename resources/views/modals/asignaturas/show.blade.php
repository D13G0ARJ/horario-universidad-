<div class="modal fade" id="mostrarModal" tabindex="-1" aria-labelledby="mostrarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i>Detalles Completo de la Asignatura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6">
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-id-card"></i> Información Básica
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Código:</dt>
                                    <dd class="col-sm-8" id="modalCode"></dd>

                                    <dt class="col-sm-4">Nombre:</dt>
                                    <dd class="col-sm-8" id="modalName"></dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="card mb-3 border-info">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-users"></i> Docente Asignado
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled" id="modalDocentes">
                                    <!-- Los docentes se cargarán aquí -->
                                </ul>
                            </div>
                        </div>

                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-building"></i> Sección Asignada
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled" id="modalSecciones">
                                    <!-- Las secciones se cargarán aquí -->
                                </ul>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mostrarModal = document.getElementById('mostrarModal');
        
        mostrarModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const docenteData = JSON.parse(button.dataset.docentes);
            const seccionData = JSON.parse(button.dataset.secciones);

            // Actualizar datos básicos
            document.getElementById('modalCode').textContent = button.dataset.asignatura_id;
            document.getElementById('modalName').textContent = button.dataset.name;

            // Actualizar docentes
            const docentesList = document.getElementById('modalDocentes');
            docentesList.innerHTML = docenteData.map(docente => `
                <li class="mb-2">
                    <span class="badge bg-info badge-detail">
                        <i class="fas fa-user-tie mr-2"></i>${docente}
                    </span>
                </li>
            `).join('');

            // Actualizar secciones
            const seccionesList = document.getElementById('modalSecciones');
            seccionesList.innerHTML = seccionData.map(seccion => `
                <li class="mb-2">
                    <span class="badge bg-success badge-detail">
                        <i class="fas fa-door-open mr-2"></i>${seccion}
                    </span>
                </li>
            `).join('');
        });
    });
</script>

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