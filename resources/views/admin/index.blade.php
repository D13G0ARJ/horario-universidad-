@extends('layouts.admin')

@section('rol')
    <h1>UNEFA</h1>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
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
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
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
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
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
    <!-- ./col -->
    <div class="col-lg-3 col-6"> 
        <!-- small box -->
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
    <!-- ./col -->
</div>

{{-- Sección para el calendario --}}
<div class="row mt-4">
    <div class="col-md-12"> {{-- Columna completa para el calendario --}}
        <div class="card card-primary rounded-lg shadow">
            <div class="card-header border-0 rounded-t-lg bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                <h3 class="card-title text-lg font-bold">
                    <i class="far fa-calendar-alt mr-1"></i>
                    Calendario
                </h3>
                <!-- tools card -->
                <div class="card-tools">
                    <!-- button with a dropdown -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle text-white" data-toggle="dropdown" data-offset="-52">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a href="#" class="dropdown-item">Añadir nuevo evento</a>
                            <a href="#" class="dropdown-item">Limpiar eventos</a>
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
                <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pt-0 rounded-b-lg">
                <!--The calendar -->
                <div id="calendar" style="width: 100%"></div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

{{-- Si necesitas la sección de "Principal" que tenías, puedes mantenerla o ajustarla --}}
<div class="row mt-4">
    <div class="col-12">
        <h1>Dashboard General</h1>
        <p>Bienvenido al panel de administración del sistema de horarios UNEFA.</p>
    </div>
</div>

@endsection

@section('scripts')
    {{-- IMPORTANT: Ensure jQuery is loaded BEFORE jQuery UI and FullCalendar --}}
    {{-- If your layouts/admin.blade.php already loads jQuery (common with AdminLTE), do not repeat it here. --}}
    {{-- Example if you need to load jQuery here: <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- fullCalendar 5.11.3 -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script> {{-- For languages --}}
    
    {{-- Make sure CSS styles are also loaded in the <head> of your main layout --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

    <script>
        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');

            if (calendarEl) { // Ensure the element exists before initializing the calendar
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left  : 'prev,next today',
                        center: 'title',
                        right : 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    locale: 'es', // Set language to Spanish
                    editable: true,
                    droppable: true, // This allows dragging external events to the calendar
                    eventReceive: function(info) { // called when an external event is dropped
                        console.log('Event received: ' + info.event.title); // Use console.log instead of alert
                    },
                    events: [
                        // Here you can load your events from the database
                        // Example of a static event:
                        {
                            title : 'Evento de Prueba',
                            start : '2025-06-05T10:00:00',
                            end   : '2025-06-05T12:00:00',
                            backgroundColor: '#f39c12', // Yellow
                            borderColor    : '#f39c12'  // Yellow
                        },
                        {
                            title          : 'Reunión de Coordinación',
                            start          : '2025-06-06T14:00:00',
                            end            : '2025-06-06T15:30:00',
                            allDay         : false,
                            backgroundColor: '#0073b7', // Blue
                            borderColor    : '#0073b7'  // Blue
                        }
                    ]
                });

                calendar.render();
            } else {
                console.error("Elemento 'calendar' no encontrado para inicializar FullCalendar.");
            }
        });
    </script>
@endsection
