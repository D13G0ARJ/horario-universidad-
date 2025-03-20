@extends('layouts.login')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-lg">
                <div class="text-center pt-3">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h2 class="text-dark mb-1 font-weight-bold h5">Sistema de Registro Docentes</h2>
                        <h4 class="text-muted h6">Registro de Usuario</h4>
                    </div>
                </div>

                <div class="card-body px-4">
                    <form method="POST" action="{{ route('register') }}" class="row g-3">
                        @csrf

                        <!-- Campo de Cédula -->
                        <div class="col-md-6">
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

                        <!-- Campo de Nombre -->
                        <div class="col-md-6">
                            <label for="name" class="form-label text-secondary small">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-user small"></i>
                                </span>
                                <input id="name" type="text"
                                    class="form-control form-control-md @error('name') is-invalid @enderror"
                                    name="name" placeholder="Nombre" value="{{ old('name') }}" required>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo de Correo Electrónico -->
                        <div class="col-md-6">
                            <label for="email" class="form-label text-secondary small">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-envelope small"></i>
                                </span>
                                <input id="email" type="email"
                                    class="form-control form-control-md @error('email') is-invalid @enderror"
                                    name="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo de Contraseña -->
                        <div class="col-md-6">
                            <label for="password" class="form-label text-secondary small">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-lock small"></i>
                                </span>
                                <input id="password" type="password"
                                    class="form-control form-control-md @error('password') is-invalid @enderror"
                                    name="password" placeholder="Contraseña" required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo de Confirmación de Contraseña -->
                        <div class="col-md-6">
                            <label for="password-confirm" class="form-label text-secondary small">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text py-2">
                                    <i class="fas fa-lock small"></i>
                                </span>
                                <input id="password-confirm" type="password"
                                    class="form-control form-control-md"
                                    name="password_confirmation" placeholder="Confirmar Contraseña" required>
                            </div>
                        </div>

                        <!-- Primera Pregunta de Seguridad -->
                        <div class="col-md-6">
                            <label for="security_question_1" class="form-label text-secondary small">Primera pregunta de seguridad</label>
                            <select name="security_question_1" id="security_question_1" class="form-control" required>
                                <option value="">Selecciona una pregunta</option>
                                <option value="¿Cuál es el nombre de tu mascota?">¿Cuál es el nombre de tu mascota?</option>
                                <option value="¿Cuál es tu color favorito?">¿Cuál es tu color favorito?</option>
                                <option value="¿Cuál es el nombre de tu ciudad natal?">¿Cuál es el nombre de tu ciudad natal?</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="security_answer_1" class="form-label text-secondary small">Respuesta a la primera pregunta</label>
                            <input type="text" name="security_answer_1" id="security_answer_1" class="form-control" required>
                        </div>

                        <!-- Segunda Pregunta de Seguridad -->
                        <div class="col-md-6">
                            <label for="security_question_2" class="form-label text-secondary small">Segunda pregunta de seguridad</label>
                            <select name="security_question_2" id="security_question_2" class="form-control" required>
                                <option value="">Selecciona una pregunta</option>
                                <option value="¿Cuál es el nombre de tu mejor amigo de la infancia?">¿Cuál es el nombre de tu mejor amigo de la infancia?</option>
                                <option value="¿Cuál es tu película favorita?">¿Cuál es tu película favorita?</option>
                                <option value="¿Cuál es el nombre de tu primer profesor?">¿Cuál es el nombre de tu primer profesor?</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="security_answer_2" class="form-label text-secondary small">Respuesta a la segunda pregunta</label>
                            <input type="text" name="security_answer_2" id="security_answer_2" class="form-control" required>
                        </div>

                        <!-- Botón de Registro -->
                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                                <i class="fas fa-user-plus me-2 small"></i>Registrarse
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light text-center py-3">
                    <div class="border-top pt-3">
                        <p class="mt-2 small text-muted mb-0">Copyright © DR 2024</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
