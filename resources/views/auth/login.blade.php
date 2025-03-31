@extends('layouts.login')

@section('content')
<div class="card shadow-lg" style="max-width: 500px; margin: 0 auto; border: 1px solid #dee2e6;"> <!-- Borde más oscuro -->
    <div class="card-body p-4 p-md-5">
        <!-- Encabezado con logo -->
        <div class="d-flex align-items-center gap-3 mb-5">
            <img 
                src="{{ asset('images/logo.jpg') }}" 
                alt="UNEFA" 
                class="img-fluid" 
                style="width: 50px; height: auto;"
            >
            <div>
                <h2 class="text-primary mb-0">Sistema de Horarios</h2>
                <h5 class="text-muted mt-1">Iniciar Sesión</h5>
            </div>
        </div>

        <!-- Formulario (Mantiene tu estructura actual) -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Campo Cédula -->
            <div class="mb-4">
                <label class="form-label text-secondary small">Cédula</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <input type="text" class="form-control" name="cedula" required>
                </div>
            </div>

            <!-- Campo Contraseña -->
            <div class="mb-4">
                <label class="form-label text-secondary small">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>

            <!-- Botón de Login -->
            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
            </button>

            <!-- Enlace olvidé contraseña -->
            <div class="text-center">
                <a href="{{ route('password.verifyUserForm') }}" class="text-primary small">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>
</div>
@endsection