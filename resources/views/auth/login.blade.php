@extends('layouts.login')

@section('content')

<header>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-2 col-md-1">   
                <div class="logo">
                    <img src="{{ asset('images/logo.jpg') }}" alt="UNEFA" width="80" height="120" class="img-fluid">
                </div>
            </div>
            <div class="col-10 col-md-11">  
                <div class="titulo">
                    <h1>Sistema de Horarios</h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card border-0 shadow-lg" style="max-width: 400px; margin: 0 auto;">
                <div class="text-center pt-3">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h2 class="text-dark mb-1 font-weight-bold h5">Sistema de Horarios</h2>
                        <h4 class="text-muted h6">Iniciar Sesión</h4>
                    </div>
                </div>

                <div class="card-body px-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Campo de Cédula -->
                        <div class="form-group mb-3">
                            <label for="cedula" class="form-label text-secondary small">Cédula</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-id-card small"></i>
                                </span>
                                <input id="cedula" type="text" 
                                    class="form-control form-control-md @error('cedula') is-invalid @enderror" 
                                    name="cedula" placeholder="Cédula" value="{{ old('cedula') }}" required autofocus>
                            </div>
                            @error('cedula')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo de Contraseña -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label text-secondary small">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-lock small"></i>
                                </span>
                                <input id="password" type="password" 
                                    class="form-control form-control-md" 
                                    name="password" placeholder="Contraseña" required>
                            </div>
                        </div>

                        <!-- Botón de Iniciar Sesión -->
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                                <i class="fas fa-sign-in-alt me-2 small"></i>Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light text-center py-3">
                    <div class="border-top pt-3">
                        <p class="mt-2 small text-muted mb-0">Copyright © DR-CB-YA 2024</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="main-footer">
    <div class="footer-content">
        <div class="footer-section">
            <strong>Copyright &copy; 2025 <a href="#">DR-CB-YA</a>.</strong> All rights reserved.
        </div>
        <div class="footer-section">
            <span id="fecha-hora"></span>
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

    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
</script>

@endsection