{{-- filepath: c:\Users\Alexa\OneDrive\Escritorio\horario-universidad-\resources\views\auth\passwords\reset-password.blade.php --}}
@extends('layouts.login')

@section('title', 'Restablecer Contraseña')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header text-center bg-transparent border-0">
                    <h4 class="text-dark mb-1 font-weight-bold">Restablecer Contraseña</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Token de restablecimiento -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Campo de Usuario -->
                        <div class="form-group mb-3">
                            <label for="username" class="form-label text-secondary small">Cédula</label>
                            <input id="username" type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                name="username" value="{{ $username }}" required readonly>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Nueva Contraseña -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label text-secondary small">Nueva Contraseña</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" placeholder="Nueva Contraseña" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label text-secondary small">Confirmar Contraseña</label>
                            <input id="password_confirmation" type="password"
                                class="form-control"
                                name="password_confirmation" placeholder="Confirmar Contraseña" required>
                        </div>

                        <!-- Botón de Restablecer -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                                Restablecer Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
