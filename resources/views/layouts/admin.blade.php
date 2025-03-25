<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <!-- Estilos personalizados -->
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .main-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .content-wrapper {
            padding-bottom: 60px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <h1>UNEFA</h1>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ms-auto"> <!-- ml-auto cambió a ms-auto en BS5 -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-bs-toggle="dropdown" href="#">
                        <i class="fas fa-user-cog"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end"> <!-- dropdown-menu-right cambió a dropdown-menu-end -->
                        <span class="dropdown-header">Cuenta</span>
                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#perfilModal">
                            <i class="fas fa-user-circle me-2"></i> Ver Perfil <!-- mr-2 cambió a me-2 -->
                        </a>
                        <a href="#" class="dropdown-item"
                            data-bs-toggle="modal"
                            data-bs-target="#preguntasSeguridadModal"
                            data-security-question-1="{{ auth()->user()->security_question_1 }}"
                            data-security-answer-1="{{ auth()->user()->security_answer_1 }}"
                            data-security-question-2="{{ auth()->user()->security_question_2 }}"
                            data-security-answer-2="{{ auth()->user()->security_answer_2 }}">
                            <i class="fas fa-lock me-2"></i> Configurar preguntas
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{url( '/')}}" class="brand-link">
                <img src="{{ asset('images/logo.jpg') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Sistema Horarios</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('images/acoount.jpg') }}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"
                            data-bs-toggle="modal"
                            data-bs-target="#perfilModal">
                            {{ auth()->user()->name }}
                        </a>
                    </div>
                </div>

                <!-- Sidebar Menu Modificado -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{url( '/')}}" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>


                        <a href="{{url( '/coordinador')}}" class="nav-link" ">
                                        <i class=" far fa-circle nav-icon"></i>
                            <p>Coordinadores </p>
                        </a>
                        <!-- Gestión de Docentes -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Gestión de horarios
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url( '/docente')}}" class="nav-link" ">
                                        <i class=" far fa-circle nav-icon"></i>
                                        <p>Docentes </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url( '/carrera')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Carreras</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url( '/asignatura')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Asignaturas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/secciones" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Secciones</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/periodo" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Periodos</p>
                                    </a>
                                </li>

                                <!-- ... más subitems ... -->
                            </ul>
                        </li>



                        <!-- Gestión de Seguridad -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Monitoreo de Actualizaciones
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url( '/#')}}" class="nav-link" ">
                                        <i class=" far fa-circle nav-icon"></i>
                                        <p>Bitacora </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Generar Reportes de Bitacora</p>
                                    </a>
                                </li>
                                <!-- ... más subitems ... -->
                            </ul>
                        </li>



                        {{--
                        <!-- Gestión de Docentes -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Mi informacion
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url( '/#')}}" class="nav-link" ">
                        <i class=" far fa-circle nav-icon"></i>
                        <p>Consultar Datos Personales </p>
                        </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Modificar Datos Personales
                                </p>
                            </a>
                        </li>
                        <!-- ... más subitems ... -->
                    </ul>
                    </li>


                    <!-- Gestión de Docentes -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>
                                Documentos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url( '/#')}}" class="nav-link" ">
                                        <i class=" far fa-circle nav-icon"></i>
                                    <p>Documentos personales </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Documentos laborales</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Documentos Financieros</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Documentos de evaluacion</p>
                                </a>
                            </li>
                            <!-- ... más subitems ... -->
                        </ul>
                    </li>
                    --}}

                    <!-- ... (agregar demás items del menú de forma similar) ... -->

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
        <div class="content-wrapper">
            <br>
            <div class="container">
                @yield('content')
            </div>
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
                <div class="p-3">
                    <h5>Title</h5>
                    <p>Sidebar content</p>
                </div>
            </aside>
            <!-- /.control-sidebar -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="float-right d-none d-sm-inline">
                    Anything you want
                </div>
                <!-- Default to the left -->
                <strong>Copyright &copy; 2025 <a href="#">DR-CB-YA</a>.</strong> All rights reserved.
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

        <!-- Scripts -->
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- Bootstrap 5 -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        <!-- AdminLTE -->
        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Scripts personalizados -->
        <script>
            // Script para preguntas de seguridad
            document.addEventListener('DOMContentLoaded', function() {
                // Tu código existente...
            });

            // DataTable initialization
            $(document).ready(function() {
                $('#secciones-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                    },
                    order: [
                        [0, 'desc']
                    ]
                });
            });
        </script>

        @stack('scripts')

</body>

</html>
