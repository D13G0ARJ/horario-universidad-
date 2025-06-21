@extends('layouts.admin')

@section('rol')
    <h1>UNEFA</h1>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info rounded-lg shadow">
            <div class="inner p-4">
                <h3>{{ $docentesCount ?? 0 }}</h3>
                <p>Docentes Registrados</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            {{-- Ruta corregida: 'docente.index' --}}
            <a href="{{ route('docente.index') }}" class="small-box-footer d-flex align-items-center justify-content-between p-2">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning rounded-lg shadow">
            <div class="inner p-4">
                <h3>{{ $periodosCount ?? 0 }}</h3>
                <p>Períodos Creados</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            {{-- Ruta correcta: 'periodo.index' --}}
            <a href="{{ route('periodo.index') }}" class="small-box-footer d-flex align-items-center justify-content-between p-2">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger rounded-lg shadow">
            <div class="inner p-4">
                <h3>{{ $asignaturasCount ?? 0 }}</h3>
                <p>Asignaturas Creadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            {{-- Ruta corregida: 'asignatura.index' --}}
            <a href="{{ route('asignatura.index') }}" class="small-box-footer d-flex align-items-center justify-content-between p-2">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6"> 
        <div class="small-box bg-primary rounded-lg shadow">
            <div class="inner p-4">
                <h3>{{ $horariosCount ?? 0 }}</h3>
                <p>Horarios Creados</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            {{-- Ruta correcta: 'horario.index' --}}
            <a href="{{ route('horario.index') }}" class="small-box-footer d-flex align-items-center justify-content-between p-2">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    </div>

{{-- Sección para el calendario --}}
<div class="row mt-4">
    <div class="col-md-5"> {{-- **CAMBIO AQUÍ:** col-md-6 para pegarlo a la izquierda y hacerlo más pequeño --}}
        <div class="card card-primary rounded-lg shadow">
            <div class="card-header border-0 rounded-t-lg bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                <h3 class="card-title text-lg font-bold">
                    <i class="far fa-calendar-alt mr-1"></i>
                    Calendario
                </h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle text-white" data-toggle="dropdown" data-offset="-52">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            {{-- Estos enlaces ahora se gestionarán con la funcionalidad del calendario --}}
                            <a href="#" class="dropdown-item" id="addEventFromDropdown">Añadir nuevo evento</a>
                            <a href="#" class="dropdown-item" id="clearAllEvents">Limpiar eventos</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Ver calendario</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm text-white" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm text-white" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                </div>
            <div class="card-body pt-0 rounded-b-lg">
                <div id="calendar" style="width: 100%"></div>
            </div>
            </div>
        </div>
    {{-- Puedes añadir otro col-md-6 aquí si quieres poner otro contenido al lado del calendario --}}
    <div class="col-md-6">
        <div class="card card-default rounded-lg shadow">
            <div class="card-header">
                <h3 class="card-title">Eventos Recientes / Próximas Notas</h3>
            </div>
            <div class="card-body">
                <p>Aquí puedes mostrar una lista de los eventos o notas más recientes o próximas del calendario.</p>
                <ul id="recentEventsList" class="list-group">
                    {{-- Los eventos se cargarán aquí dinámicamente --}}
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Añadir/Editar Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <input type="hidden" id="event-id">
                    <div class="mb-3">
                        <label for="event-title" class="form-label">Título de la Nota</label>
                        <input type="text" class="form-control" id="event-title" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-start-date" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="event-start-date" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-end-date" class="form-label">Fecha de Fin (Opcional)</label>
                        <input type="date" class="form-control" id="event-end-date">
                    </div>
                    <div class="mb-3">
                        <label for="event-color" class="form-label">Color de la Nota</label>
                        <input type="color" class="form-control form-control-color" id="event-color" value="#007bff">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="event-all-day">
                        <label class="form-check-label" for="event-all-day">
                            Todo el día
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" id="deleteEventBtn" style="display:none;">Eliminar Nota</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveEventBtn">Guardar Nota</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar; // Declara la variable de calendario a nivel superior para que sea accesible

            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            var eventIdInput = document.getElementById('event-id');
            var eventTitleInput = document.getElementById('event-title');
            var eventStartDateInput = document.getElementById('event-start-date');
            var eventEndDateInput = document.getElementById('event-end-date');
            var eventColorInput = document.getElementById('event-color');
            var eventAllDayInput = document.getElementById('event-all-day');
            var saveEventBtn = document.getElementById('saveEventBtn');
            var deleteEventBtn = document.getElementById('deleteEventBtn');
            var clickedDate = null; // Para guardar la fecha en la que se hizo clic

            // Función para actualizar la lista de eventos recientes
            function updateRecentEventsList() {
                var recentEventsList = document.getElementById('recentEventsList');
                recentEventsList.innerHTML = ''; // Limpiar la lista existente

                var events = calendar.getEvents(); // Obtener todos los eventos del calendario

                // Ordenar eventos por fecha de inicio
                events.sort((a, b) => a.start - b.start);

                // Mostrar los próximos 5 eventos (o ajusta el número)
                var count = 0;
                events.forEach(function(event) {
                    if (event.start && event.start >= new Date() && count < 5) { // Solo eventos futuros y limitados
                        var listItem = document.createElement('li');
                        listItem.className = 'list-group-item';
                        var endDateDisplay = event.end ? ' - ' + new Date(event.end).toLocaleDateString('es-ES') : '';
                        listItem.innerHTML = `<span style="color: ${event.backgroundColor};">&#9679;</span> ${event.title} (${new Date(event.start).toLocaleDateString('es-ES')}${endDateDisplay})`;
                        recentEventsList.appendChild(listItem);
                        count++;
                    }
                });

                if (count === 0) {
                    recentEventsList.innerHTML = '<li class="list-group-item text-muted">No hay notas o eventos próximos.</li>';
                }
            }


            if (calendarEl) {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left  : 'prev,next today',
                        center: 'title',
                        right : 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    locale: 'es',
                    editable: true,
                    droppable: true,
                    // Interacción con los días
                    dateClick: function(info) {
                        clickedDate = info.dateStr;
                        // Limpiar formulario
                        eventIdInput.value = '';
                        eventTitleInput.value = '';
                        eventStartDateInput.value = clickedDate;
                        eventEndDateInput.value = '';
                        eventColorInput.value = '#007bff';
                        eventAllDayInput.checked = true;
                        deleteEventBtn.style.display = 'none'; // Ocultar botón de eliminar para nuevos eventos
                        eventModal.show();
                    },
                    // Interacción con los eventos existentes
                    eventClick: function(info) {
                        eventIdInput.value = info.event.id;
                        eventTitleInput.value = info.event.title;
                        eventStartDateInput.value = info.event.startStr.split('T')[0]; // Solo la fecha
                        eventEndDateInput.value = info.event.endStr ? info.event.endStr.split('T')[0] : '';
                        eventColorInput.value = info.event.backgroundColor || '#007bff';
                        eventAllDayInput.checked = info.event.allDay;
                        deleteEventBtn.style.display = 'block'; // Mostrar botón de eliminar
                        eventModal.show();
                    },
                    events: [
                        // Aquí puedes cargar tus eventos (notas) desde la base de datos
                        // Para la prueba, mantendremos algunos eventos estáticos
                        {
                            id    : '1',
                            title : 'Nota de Prueba',
                            start : '2025-06-10', // Fecha de la nota
                            backgroundColor: '#f39c12', // Amarillo
                            borderColor    : '#f39c12',
                            allDay: true
                        },
                        {
                            id    : '2',
                            title : 'Reunión Importante',
                            start : '2025-06-15T10:00:00',
                            end   : '2025-06-15T12:00:00',
                            backgroundColor: '#0073b7', // Azul
                            borderColor    : '#0073b7',
                            allDay: false
                        },
                        {
                            id    : '3',
                            title : 'Entrega de Proyecto',
                            start : '2025-06-20',
                            backgroundColor: '#28a745', // Verde
                            borderColor    : '#28a745',
                            allDay: true
                        }
                    ]
                });

                calendar.render();
                updateRecentEventsList(); // Cargar la lista inicial de eventos

            } else {
                console.error("Elemento 'calendar' no encontrado para inicializar FullCalendar.");
            }

            // Lógica para guardar/actualizar un evento
            saveEventBtn.addEventListener('click', function() {
                var eventId = eventIdInput.value;
                var title = eventTitleInput.value.trim();
                var start = eventStartDateInput.value;
                var end = eventEndDateInput.value;
                var color = eventColorInput.value;
                var allDay = eventAllDayInput.checked;

                if (!title || !start) {
                    Swal.fire('Error', 'El título y la fecha de inicio son obligatorios.', 'error');
                    return;
                }

                if (eventId) {
                    // Actualizar evento existente
                    var event = calendar.getEventById(eventId);
                    if (event) {
                        event.setProp('title', title);
                        event.setStart(start);
                        event.setEnd(end || null); // Usa null si no hay fecha de fin
                        event.setProp('backgroundColor', color);
                        event.setProp('borderColor', color);
                        event.setAllDay(allDay);
                        Swal.fire('Actualizado!', 'La nota ha sido actualizada.', 'success');
                    }
                } else {
                    // Añadir nuevo evento
                    calendar.addEvent({
                        id: String(Date.now()), // Generar un ID único simple (para frontend)
                        title: title,
                        start: start,
                        end: end || null,
                        allDay: allDay,
                        backgroundColor: color,
                        borderColor: color
                    });
                    Swal.fire('Guardado!', 'La nota ha sido guardada.', 'success');
                }
                eventModal.hide();
                updateRecentEventsList(); // Actualizar la lista después de guardar/actualizar
            });

            // Lógica para eliminar un evento
            deleteEventBtn.addEventListener('click', function() {
                var eventId = eventIdInput.value;
                if (eventId) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminarlo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var event = calendar.getEventById(eventId);
                            if (event) {
                                event.remove(); // Eliminar el evento del calendario
                                Swal.fire('¡Eliminado!', 'La nota ha sido eliminada.', 'success');
                                eventModal.hide();
                                updateRecentEventsList(); // Actualizar la lista después de eliminar
                            }
                        }
                    });
                }
            });

            // Lógica para el botón "Añadir nuevo evento" del dropdown
            document.getElementById('addEventFromDropdown').addEventListener('click', function(e) {
                e.preventDefault();
                clickedDate = null; // No hay una fecha específica pre-seleccionada
                eventIdInput.value = '';
                eventTitleInput.value = '';
                eventStartDateInput.value = new Date().toISOString().slice(0,10); // Fecha actual por defecto
                eventEndDateInput.value = '';
                eventColorInput.value = '#007bff';
                eventAllDayInput.checked = true;
                deleteEventBtn.style.display = 'none';
                eventModal.show();
            });

            // Lógica para el botón "Limpiar eventos" del dropdown
            document.getElementById('clearAllEvents').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esto eliminará todas las notas del calendario!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, limpiar todo!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        calendar.removeAllEvents(); // Eliminar todos los eventos
                        Swal.fire('¡Limpiado!', 'Todas las notas han sido eliminadas.', 'success');
                        updateRecentEventsList(); // Actualizar la lista
                    }
                });
            });

            // Sincronizar campo de fecha de fin con "Todo el día"
            eventAllDayInput.addEventListener('change', function() {
                if (this.checked) {
                    eventEndDateInput.value = '';
                    eventEndDateInput.disabled = true;
                } else {
                    eventEndDateInput.disabled = false;
                }
            });
            // Inicializar estado de fecha de fin al abrir modal
            document.getElementById('eventModal').addEventListener('show.bs.modal', function () {
                if (eventAllDayInput.checked) {
                    eventEndDateInput.disabled = true;
                } else {
                    eventEndDateInput.disabled = false;
                }
            });
        });
    </script>
@endpush