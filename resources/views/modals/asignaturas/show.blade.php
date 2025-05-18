<div class="modal fade" id="mostrarModal" tabindex="-1" aria-labelledby="mostrarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="mostrarModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Detalles Completos de la Asignatura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3 border-primary shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-id-card me-2"></i>Información Básica
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4">Código:</dt>
                                    <dd class="col-sm-8" id="modalShowCode"></dd>

                                    <dt class="col-sm-4">Nombre:</dt>
                                    <dd class="col-sm-8" id="modalShowName"></dd>
                                </dl>
                            </div>
                        </div>

                        <div class="card mb-3 border-secondary shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <i class="fas fa-hourglass-half me-2"></i>Carga Horaria
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush" id="modalShowCargaHoraria">
                                    </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-3 border-info shadow-sm">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Docentes Asignados
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush" id="modalShowDocentes">
                                    </ul>
                                <p id="noDocentesMessage" class="text-muted text-center" style="display: none;">No hay docentes asignados.</p>
                            </div>
                        </div>

                        <div class="card border-success shadow-sm">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-university me-2"></i>Secciones Asignadas
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush" id="modalShowSecciones">
                                    </ul>
                                <p id="noSeccionesMessage" class="text-muted text-center" style="display: none;">No hay secciones asignadas.</p>
                            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mostrarModalElement = document.getElementById('mostrarModal');
    if (mostrarModalElement) {
        mostrarModalElement.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Obtener datos del botón
            const asignaturaId = button.dataset.asignatura_id;
            const name = button.dataset.name;
            const docentesData = JSON.parse(button.dataset.docentes || '[]');
            const seccionesData = JSON.parse(button.dataset.secciones || '[]');
            const cargaHorariaData = JSON.parse(button.dataset.carga_horaria || '{}'); // Nueva línea

            // Actualizar Información Básica
            document.getElementById('modalShowCode').textContent = asignaturaId || 'No disponible';
            document.getElementById('modalShowName').textContent = name || 'No disponible';

            // Actualizar Carga Horaria
            const cargaHorariaList = document.getElementById('modalShowCargaHoraria');
            cargaHorariaList.innerHTML = ''; // Limpiar contenido previo
            if (Object.keys(cargaHorariaData).length > 0) {
                for (const tipo in cargaHorariaData) {
                    if (cargaHorariaData.hasOwnProperty(tipo) && cargaHorariaData[tipo] > 0) {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        
                        let tipoText = tipo.charAt(0).toUpperCase() + tipo.slice(1);
                        if(tipo === 'teorica') tipoText = 'Teórica';
                        if(tipo === 'practica') tipoText = 'Práctica';
                        if(tipo === 'laboratorio') tipoText = 'Laboratorio';

                        li.innerHTML = `
                            <span class="text-capitalize"><i class="far fa-clock me-2"></i>${tipoText}</span>
                            <span class="badge bg-primary rounded-pill">${cargaHorariaData[tipo]} hr(s)</span>
                        `;
                        cargaHorariaList.appendChild(li);
                    }
                }
            } else {
                const li = document.createElement('li');
                li.className = 'list-group-item text-muted';
                li.textContent = 'No hay carga horaria especificada.';
                cargaHorariaList.appendChild(li);
            }

            // Actualizar Docentes
            const docentesList = document.getElementById('modalShowDocentes');
            const noDocentesMessage = document.getElementById('noDocentesMessage');
            docentesList.innerHTML = ''; // Limpiar contenido previo
            if (docentesData && docentesData.length > 0) {
                noDocentesMessage.style.display = 'none';
                docentesData.forEach(docente => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.innerHTML = `<i class="fas fa-user-tie me-2 text-info"></i>${docente}`;
                    docentesList.appendChild(li);
                });
            } else {
                noDocentesMessage.style.display = 'block';
            }

            // Actualizar Secciones
            const seccionesList = document.getElementById('modalShowSecciones');
            const noSeccionesMessage = document.getElementById('noSeccionesMessage');
            seccionesList.innerHTML = ''; // Limpiar contenido previo
            if (seccionesData && seccionesData.length > 0) {
                noSeccionesMessage.style.display = 'none';
                seccionesData.forEach(seccion => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.innerHTML = `<i class="fas fa-door-open me-2 text-success"></i>${seccion}`;
                    seccionesList.appendChild(li);
                });
            } else {
                noSeccionesMessage.style.display = 'block';
            }
        });
    }
});
</script>

<style>
    /* Estilos opcionales para mejorar la apariencia del modal */
    #mostrarModal .modal-content {
        border-radius: 0.5rem;
    }
    #mostrarModal .card-header {
        font-weight: 500;
    }
    #mostrarModal dt {
        color: #6c757d; /* Gris estándar de Bootstrap */
        font-weight: bold;
    }
    #mostrarModal dd {
        color: #212529; /* Negro estándar de Bootstrap */
        margin-bottom: 0.5rem;
    }
    #mostrarModal .list-group-item {
        border-left: 0;
        border-right: 0;
    }
    #mostrarModal .list-group-item:first-child {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-top: 0;
    }
    #mostrarModal .list-group-item:last-child {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom: 0;
    }
</style>