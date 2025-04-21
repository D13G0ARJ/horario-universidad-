@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Encabezado compacto -->
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="text-primary">
                <i class="fas fa-calendar-alt mr-2"></i>Horarios Académicos
            </h4>
        </div>
    </div>

    <!-- Calendario compacto -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-2">
            <div id="calendar" style="height: 500px;"></div>
        </div>
    </div>

    <!-- Modal compacto para Asignar Horario -->
    <div class="modal fade" id="asignarHorarioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title">Asignar Nuevo Horario</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAsignarHorario" method="POST" action="{{ route('horario.store') }}">
                    @csrf
                    <div class="modal-body p-3">
                        <div class="row g-2">
                            <div class="col-12">
                                <select id="asignatura" name="asignatura_id" class="form-select form-select-sm" required>
                                    <option value="">Asignatura...</option>
                                    @foreach($asignaturas as $asignatura)
                                        <option value="{{ $asignatura->asignatura_id }}">{{ $asignatura->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select id="seccion" name="seccion_id" class="form-select form-select-sm" required>
                                    <option value="">Sección...</option>
                                    @foreach($secciones as $seccion)
                                        <option value="{{ $seccion->codigo_seccion }}">{{ $seccion->codigo_seccion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select id="docente" name="docente_id" class="form-select form-select-sm" required>
                                    <option value="">Docente...</option>
                                    @foreach($docentes as $docente)
                                        <option value="{{ $docente->cedula_doc }}">{{ $docente->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="date" id="fecha" name="fecha" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <input type="time" id="hora_inicio" name="hora_inicio" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-3">
                                <input type="time" id="hora_fin" name="hora_fin" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/main.min.css" rel="stylesheet">
<style>
    /* Estilos compactos */
    #calendar {
        font-size: 0.85rem;
    }
    .fc-toolbar-title {
        font-size: 1rem;
    }
    .fc-col-header, .fc-timegrid-slot {
        height: 2em !important;
    }
    .fc-event {
        font-size: 0.75rem;
        margin: 1px;
        padding: 0 2px;
    }
    .fc-timegrid-event-harness {
        font-size: 0.7rem;
    }
    .form-select-sm, .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/locales/es.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        slotDuration: '00:45:00',
        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',
        allDaySlot: false,
        locale: 'es',
        firstDay: 1, // Lunes como primer día de la semana
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'today'
        },
        height: 'auto',
        events: @json($horarios),
        selectable: true,
        select: function(info) {
            $('#fecha').val(info.startStr.split('T')[0]);
            $('#hora_inicio').val(info.startStr.split('T')[1].substring(0, 5));
            $('#hora_fin').val(info.endStr.split('T')[1].substring(0, 5));
            $('#asignarHorarioModal').modal('show');
        },
        eventContent: function(arg) {
            // Contenido compacto para eventos
            return {
                html: `<div class="fc-event-title">${arg.event.title}</div>`
            };
        }
    });
    
    calendar.render();
});
</script>
@endpush