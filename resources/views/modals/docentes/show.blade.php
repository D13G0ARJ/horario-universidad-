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

                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-building"></i> Asignaturas asociadas al docente
                            </div>
                            <div class="card-body" id="asignaturasContainer">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-info" id="btnMostrarHorarioDocente">
                        <i class="fas fa-calendar-alt mr-1"></i> Mostrar Horario del Docente
                    </button>
                </div>

                <div id="horarioFiltroContainer" style="display: none;" class="mt-4">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-filter mr-1"></i> Filtrar Horario por Período
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="periodoSelect">Seleccione un Período Académico:</label>
                                <select class="form-control" id="periodoSelect">
                                    <option value="">Cargando períodos...</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary mt-3" id="btnCargarHorario">
                                <i class="fas fa-eye mr-1"></i> Mostrar Horario
                            </button>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnMostrarHorarioDocente = document.getElementById('btnMostrarHorarioDocente');
        const horarioFiltroContainer = document.getElementById('horarioFiltroContainer');
        const periodoSelect = document.getElementById('periodoSelect');
        const btnCargarHorario = document.getElementById('btnCargarHorario');
        let currentDocenteCedula = null; // Variable para almacenar la cédula del docente actual

        // Listener para cuando se abre la modal de docente
        document.getElementById('mostrarModal').addEventListener('show.bs.modal', function (event) {
            // Obtener la cédula del docente de la modal (asumiendo que ya se carga en #modalCedula)
            currentDocenteCedula = document.getElementById('modalCedula').textContent.trim();
            // Ocultar el contenedor del filtro de horario al abrir la modal
            horarioFiltroContainer.style.display = 'none';
            // Resetear el select de períodos
            periodoSelect.innerHTML = '<option value="">Cargando períodos...</option>';
        });

        // Listener para el botón "Mostrar Horario del Docente"
        btnMostrarHorarioDocente.addEventListener('click', function() {
            // Alternar la visibilidad del contenedor del filtro
            if (horarioFiltroContainer.style.display === 'none') {
                horarioFiltroContainer.style.display = 'block';
                fetchPeriods(); // Cargar los períodos cuando se muestra el filtro
            } else {
                horarioFiltroContainer.style.display = 'none';
            }
        });

        // Función para cargar los períodos desde la API
        function fetchPeriods() {
            fetch('/api/periods')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(periods => {
                    periodoSelect.innerHTML = '<option value="">-- Seleccione un Período --</option>'; // Opción por defecto
                    periods.forEach(period => {
                        const option = document.createElement('option');
                        option.value = period.id;
                        option.textContent = period.nombre;
                        periodoSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar los períodos:', error);
                    periodoSelect.innerHTML = '<option value="">Error al cargar períodos</option>';
                    // Puedes añadir un mensaje de error visible al usuario aquí si lo deseas
                });
        }

        // Listener para el botón "Mostrar Horario" dentro del filtro
        btnCargarHorario.addEventListener('click', function() {
            const selectedPeriodId = periodoSelect.value;

            if (!selectedPeriodId) {
                alert('Por favor, seleccione un período académico.'); // Usar un modal personalizado en producción
                return;
            }

            if (!currentDocenteCedula) {
                alert('No se pudo obtener la cédula del docente. Intente recargar la página.'); // Usar un modal personalizado en producción
                return;
            }

            // Redirigir a la nueva página del horario del docente
            window.location.href = `/docentes/${currentDocenteCedula}/horario/${selectedPeriodId}`;
        });
    });
</script>
