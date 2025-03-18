@extends('layouts.login')

@section('content')

<header>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-2 col-md-1">   
                <div class="logo">
                <img src="{{asset('images/logo.jpg')}}" alt="UNEFA" width="80" height="120" class="img-fluid">

                </div>
            </div>
            <div class="col-10 col-md-11">  
                <div class="titulo">
                    <h1>Sistema de Registro Docentes</h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4 col-md-6 col-sm-8"> <!-- Columnas reducidas -->
            <div class="card border-0 shadow-lg" style="max-width: 400px; margin: 0 auto;"> <!-- Ancho máximo añadido -->
                <div class="text-center pt-3"> <!-- Padding superior reducido -->
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h2 class="text-dark mb-1 font-weight-bold h5">Sistema de Registro Docentes</h2> <!-- Título más pequeño -->
                        <h4 class="text-muted h6">Iniciar Sesión</h4> <!-- Subtítulo más pequeño -->
                    </div>
                </div>

                <div class="card-body px-4"> <!-- Padding horizontal reducido -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Campo de Usuario ajustado -->
                        <div class="form-group mb-3">
                            <label for="usuario" class="form-label text-secondary small">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text py-2"> <!-- Padding vertical reducido -->
                                    <i class="fas fa-user small"></i>
                                </span>
                                <input id="usuario" type="text" 
                                    class="form-control form-control-md @error('email') is-invalid @enderror" 
                                    name="email" placeholder="Usuario">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo de Contraseña ajustado -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label text-secondary small">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-lock small"></i>
                                </span>
                                <input id="password" type="password" 
                                    class="form-control form-control-md" 
                                    name="password" placeholder="Contraseña">
                            </div>
                        </div>

                        <!-- Botón ajustado -->
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary btn-md rounded-pill py-2"> <!-- Botón más compacto -->
                                <i class="fas fa-sign-in-alt me-2 small"></i>Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer ajustado -->
                <div class="card-footer bg-light text-center py-3">
                    <div class="border-top pt-3">
                        <p class="mt-2 small text-muted mb-0">Copyright © DR 2024</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                <!-- Footer Institucional -->
                <div class="card-footer bg-light text-center py-4">
                    <div class="border-top pt-4">
                        
                        <p class="mt-3 small text-muted mb-0">Copyright © DR 2024</p>
                    </div>
                </div>


                <footer class="main-footer">
    <div class="footer-content">
        <!-- Sección izquierda -->
        <div class="footer-section">
            <strong>Copyright &copy; 2025 <a href="#">Diego Rodriguez</a>.</strong> All rights reserved.
        </div>
        
        <!-- Sección derecha -->
        <div class="footer-section">
            <span id="fecha-hora"></span> <!-- Espacio para fecha y hora -->
        </div>
    </div>
</footer>

<script>
    function actualizarFechaHora() {
        const opciones = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };

        const fechaHora = new Date().toLocaleDateString('es-ES', opciones);
        document.getElementById('fecha-hora').textContent = fechaHora;
    }

    // Actualizar inmediatamente y cada segundo
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
</script>




@endsection

