<div class="modal fade" id="mostrarModal" tabindex="-1" aria-labelledby="mostrarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i>Detalles Completos de la Asignatura
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

                        <!-- Nueva Sección de Carga Horaria -->
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <i class="fas fa-clock"></i> Carga Horaria
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0" id="modalCargaHoraria">
                                    <!-- Bloques de carga horaria -->
                                </ul>
                                <div class="mt-3">
                                    <strong>Total Horas:</strong>
                                    <span class="badge bg-dark" id="totalHoras">0h</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="card mb-3 border-info">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-users"></i> Docentes Asignados
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled" id="modalDocentes"></ul>
                            </div>
                        </div>

                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-building"></i> Secciones Asignadas
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled" id="modalSecciones"></ul>
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
            
            // Obtener datos
            const docenteData = JSON.parse(button.dataset.docentes);
            const seccionData = JSON.parse(button.dataset.secciones);
            const cargaHorariaData = JSON.parse(button.dataset.cargaHoraria);

            // Actualizar datos básicos
            document.getElementById('modalCode').textContent = button.dataset.asignatura_id;
            document.getElementById('modalName').textContent = button.dataset.name;

            // Cargar docentes
            const docentesList = document.getElementById('modalDocentes');
            docentesList.innerHTML = docenteData.map(docente => `
                <li class="mb-2">
                    <span class="badge bg-info d-flex align-items-center">
                        <i class="fas fa-user-tie me-2"></i>
                        <span>${docente}</span>
                    </span>
                </li>
            `).join('');

            // Cargar secciones
            const seccionesList = document.getElementById('modalSecciones');
            seccionesList.innerHTML = seccionData.map(seccion => `
                <li class="mb-2">
                    <span class="badge bg-success d-flex align-items-center">
                        <i class="fas fa-door-open me-2"></i>
                        <span>${seccion}</span>
                    </span>
                </li>
            `).join('');

            // Cargar carga horaria
            const cargaHorariaList = document.getElementById('modalCargaHoraria');
            const totalHoras = cargaHorariaData.reduce((acc, curr) => acc + parseInt(curr.horas_academicas), 0);
            
            cargaHorariaList.innerHTML = cargaHorariaData.map(carga => `
                <li class="mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge ${getBadgeClass(carga.tipo)}">
                            <i class="${getIcon(carga.tipo)} me-2"></i>
                            ${carga.tipo.charAt(0).toUpperCase() + carga.tipo.slice(1)}
                        </span>
                        <span class="fw-bold">${carga.horas_academicas}h</span>
                    </div>
                </li>
            `).join('');

            document.getElementById('totalHoras').textContent = `${totalHoras}h`;
        });

        function getBadgeClass(tipo) {
            const classes = {
                teorica: 'bg-primary',
                practica: 'bg-danger',
                laboratorio: 'bg-purple'
            };
            return classes[tipo] || 'bg-secondary';
        }

        function getIcon(tipo) {
            const icons = {
                teorica: 'fas fa-chalkboard',
                practica: 'fas fa-flask',
                laboratorio: 'fas fa-microscope'
            };
            return icons[tipo] || 'fas fa-clock';
        }
    });
</script>

<style>
    .modal-content {
        border-radius: 0.7rem;
        border: none;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }
    .card-header {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    dt {
        color: #6c757d;
        font-weight: 500;
    }
    dd {
        color: #2c3e50;
        font-weight: 600;
    }
    .badge {
        padding: 0.6em 1em;
        font-size: 0.9em;
        font-weight: 500;
    }
    .bg-purple {
        background-color: #6f42c1!important;
    }
    #totalHoras {
        font-size: 1.1em;
        padding: 0.5em 1em;
    }
</style>