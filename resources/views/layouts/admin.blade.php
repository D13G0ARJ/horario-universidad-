<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <!-- Fuentes -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- DataTables -->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <!-- Estilos personalizados -->
    <style>
        /* Eliminar scrollbars globalmente */
        ::-webkit-scrollbar {
            display: none;
        }

        html {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
            overflow: hidden;
            height: 100%;
        }

        body {
            overflow: hidden;
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        /* Ajustar estructura principal */
        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            /* Ocupar toda la altura visible */
            overflow: hidden;
        }

        .content-wrapper {
            flex: 1;
            overflow: hidden;
            /* Deshabilitar scroll interno */
            padding-bottom: 60px;
        }

        /* Ajustar sidebar */
        .main-sidebar {
            height: 100vh;
            overflow-y: hidden;
            /* Ocultar scroll en sidebar */
            position: fixed;
        }



        /* Asegurar contenido principal */
        .content {
            margin-top: 60px;
            /* Ajustar según altura de tu navbar */
            height: calc(100vh - 120px);
            /* 60px header + 60px footer */
            overflow: hidden;
        }
    </style>

@yield('style')


</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top shadow-sm" ">
    <div class=" container-fluid">
            <!-- Left Side -->
            <div class="d-flex align-items-center">
                <a class="nav-link text-dark" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
                <h1 class="mb-0 fs-4 fw-bold text-primary ms-2">Sistema Horarios</h1>
            </div>

            <!-- Right Side -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link text-dark" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-cog fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg"
                            style="min-width: 220px; margin-top: 8px;">
                            <li>
                                <span class="dropdown-header small text-muted">Cuenta</span>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2"
                                    href="#" data-bs-toggle="modal" data-bs-target="#perfilModal">
                                    <i class="fas fa-user-circle me-3 opacity-75"></i>
                                    <span>Ver Perfil</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2"
                                    href="#" data-bs-toggle="modal" data-bs-target="#preguntasSeguridadModal"
                                    data-security-question-1="{{ auth()->user()->security_question_1 }}"
                                    data-security-answer-1="{{ auth()->user()->security_answer_1 }}"
                                    data-security-question-2="{{ auth()->user()->security_question_2 }}"
                                    data-security-answer-2="{{ auth()->user()->security_answer_2 }}">
                                    <i class="fas fa-lock me-3 opacity-75"></i>
                                    <span>Configurar preguntas</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-2">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-3 opacity-75"></i>
                                    <span>Cerrar Sesión</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
    </div>
    </nav>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4 fixed-left h-100">
        <!-- Brand Logo -->
        <a href="{{ url('/') }}" class="brand-link text-center bg-primary">
            <div class="d-inline-block align-middle">
                <img src="{{ asset('images/logo.jpg') }}"
                    class="brand-image img-circle elevation-3"
                    alt="Logo Sistema"
                    style="width: 45px; height: 45px; object-fit: cover">
            </div>
            <span class="brand-text font-weight-light d-block mt-2">UNEFA</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar h-100">
            <!-- User Panel -->
            <div class="user-panel d-flex align-items-center px-3 py-3">
                <div class="image flex-shrink-0">
                    <div class="d-flex align-items-center justify-content-start rounded-circle elevation-2"
    style="width: 20px; height: 20px; background-color: rgba(255,255,255,0.1); margin-left: -12px;">
    <i class="fas fa-user-circle text-white" style="font-size: 1.8em;"></i>
</div>
                </div>
                <div class="info flex-grow-1 ms-2">
                    <a href="#" class="d-block text-truncate" data-bs-toggle="modal" data-bs-target="#perfilModal">
                        {{ auth()->user()->name }}
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Coordinadores -->
                    <li class="nav-item">
                        <a href="{{ url('/coordinador') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Coordinadores</p>
                        </a>
                    </li>

                    <!-- Gestión de Horarios -->
                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Gestión de horarios
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview nav-child">
                            <li class="nav-item">
                                <a href="{{ url('/docente') }}" class="nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>Docentes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/carrera') }}" class="nav-link">
                                    <i class="nav-icon fas fa-graduation-cap"></i>
                                    <p>Carreras</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/asignatura') }}" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Asignaturas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/secciones" class="nav-link">
                                    <i class="nav-icon fas fa-layer-group"></i>
                                    <p>Secciones</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/periodo" class="nav-link">
                                    <i class="nav-icon fas fa-clock"></i>
                                    <p>Periodos</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Horario -->
                    <li class="nav-item">
                        <a href="{{ url('/horario') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Horarios</p>
                        </a>
                    </li>

                    <!-- Monitoreo -->
                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shield-alt"></i>
                            <p>
                                Monitoreo
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview nav-child">
                            <li class="nav-item">
                                <a href="{{ url('/bitacora') }}" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>Bitácora</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/respaldo') }}" class="nav-link">
                                    <i class="nav-icon fas fa-database"></i>
                                    <p>Respaldo</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>


    @if (Session::get('mensaje') && Session::get('icono'))
    <script>
        Swal.fire({
            title: "Good job!",
            text: "{{ Session::get('mensaje') }}",
            icon: "{{ Session::get('icono') }}",
        });
    </script>
    @endif

    @if (Session::get('mensajeactualizado'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "success",
            title: "{{ Session::get('mensajeactualizado') }}"
        });
    </script>
    @endif





    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="
    margin-left: 250px; /* Ancho del sidebar */
    margin-top: 60px; /* Altura del navbar */
    height: calc(100vh - 110px); /* Altura total menos header y footer */
    overflow-y: auto;
    padding: 20px;
">
        <!-- Contenido principal -->
        <div class="container-fluid">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer fixed-bottom bg-white shadow-sm">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-6 text-start">
                        <strong>Copyright &copy; 2025 <a href="#">DR-CB-YA</a>.</strong>
                    </div>
                    <div class="col-6 text-end">
                        <div class="float-right d-none d-sm-inline">
                            Versión 1.0.0
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>



    <!-- Modal del Perfil del Usuario -->
    <div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Mi Perfil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Datos del usuario logueado -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cédula:</label>
                        <p class="form-control-static">{{ Auth::user()->cedula }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre:</label>
                        <p class="form-control-static">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <p class="form-control-static">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->






    <!-- Modal de Configurar Preguntas de Seguridad -->
    <div class="modal fade" id="preguntasSeguridadModal" tabindex="-1" aria-labelledby="preguntasSeguridadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Configurar Preguntas de Seguridad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('security-questions.update') }}">
                        @csrf

                        <!-- Pregunta de Seguridad 1 -->
                        <div class="form-group mb-3">
                            <label for="security_question_1" class="form-label text-secondary small">Primera Pregunta de Seguridad</label>
                            <select id="security_question_1" name="security_question_1"
                                class="form-select @error('security_question_1') is-invalid @enderror" required>
                                <option value="" disabled>Seleccione una pregunta</option>
                                <option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
                                <option value="¿Cuál es tu comida favorita?">¿Cuál es tu comida favorita?</option>
                                <option value="¿En qué ciudad naciste?">¿En qué ciudad naciste?</option>
                            </select>
                            @error('security_question_1')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Respuesta de Seguridad 1 -->
                        <div class="form-group mb-3">
                            <label for="security_answer_1" class="form-label text-secondary small">Respuesta</label>
                            <input id="security_answer_1" type="text"
                                class="form-control @error('security_answer_1') is-invalid @enderror"
                                name="security_answer_1" placeholder="Respuesta" required>
                            @error('security_answer_1')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pregunta de Seguridad 2 -->
                        <div class="form-group mb-3">
                            <label for="security_question_2" class="form-label text-secondary small">Segunda Pregunta de Seguridad</label>
                            <select id="security_question_2" name="security_question_2"
                                class="form-select @error('security_question_2') is-invalid @enderror" required>
                                <option value="" disabled>Seleccione una pregunta</option>
                                <option value="¿Cuál es el nombre de tu escuela primaria?">¿Cuál es el nombre de tu escuela primaria?</option>
                                <option value="¿Cuál es tu película favorita?">¿Cuál es tu película favorita?</option>
                                <option value="¿Cuál es el nombre de tu mejor amigo/a de la infancia?">¿Cuál es el nombre de tu mejor amigo/a de la infancia?</option>
                            </select>
                            @error('security_question_2')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Respuesta de Seguridad 2 -->
                        <div class="form-group mb-3">
                            <label for="security_answer_2" class="form-label text-secondary small">Respuesta</label>
                            <input id="security_answer_2" type="text"
                                class="form-control @error('security_answer_2') is-invalid @enderror"
                                name="security_answer_2" placeholder="Respuesta" required>
                            @error('security_answer_2')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botón de Guardar -->
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-warning btn-md rounded-pill py-2">
                                <i class="fas fa-save me-2 small"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('toast_success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('toast_success') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                Swal.fire({
                    icon: 'error',
                    title: '{{ $error }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endforeach
        </script>
    @endif


    <!-- Script para llenar el modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const preguntasSeguridadModal = document.getElementById('preguntasSeguridadModal');
            preguntasSeguridadModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Obtener datos del botón
                const pregunta1 = button.getAttribute('data-security-question-1');
                const respuesta1 = button.getAttribute('data-security-answer-1');
                const pregunta2 = button.getAttribute('data-security-question-2');
                const respuesta2 = button.getAttribute('data-security-answer-2');

                // Actualizar los campos del modal
                const selectPregunta1 = preguntasSeguridadModal.querySelector('#security_question_1');
                const inputRespuesta1 = preguntasSeguridadModal.querySelector('#security_answer_1');
                const selectPregunta2 = preguntasSeguridadModal.querySelector('#security_question_2');
                const inputRespuesta2 = preguntasSeguridadModal.querySelector('#security_answer_2');

                // Seleccionar la pregunta 1 actual
                if (pregunta1) {
                    Array.from(selectPregunta1.options).forEach(option => {
                        if (option.value === pregunta1) {
                            option.selected = true;
                        }
                    });
                }

                // Llenar la respuesta 1 actual
                if (respuesta1) {
                    inputRespuesta1.value = respuesta1;
                }

                // Seleccionar la pregunta 2 actual
                if (pregunta2) {
                    Array.from(selectPregunta2.options).forEach(option => {
                        if (option.value === pregunta2) {
                            option.selected = true;
                        }
                    });
                }

                // Llenar la respuesta 2 actual
                if (respuesta2) {
                    inputRespuesta2.value = respuesta2;
                }
            });
        });
    </script>
    ```
    <!-- Scripts -->
    <!-- jQuery -->
    <!-- jQuery -->
    <!-- jQuery (debe ir primero) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Extensions -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <!-- PDF Export Libraries (orden crítico) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- AdminLTE -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts personalizados base -->
    <script>
        // Funciones base que podrían necesitar todas las vistas
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo básico de modales si es necesario
        });
    </script>

    @stack('scripts') <!-- Para que las vistas añadan sus scripts específicos -->
</body>
</html>
