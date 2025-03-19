{{-- filepath: c:\Users\Alexa\OneDrive\Escritorio\horario-universidad-\resources\views\auth\passwords\security-questions.blade.php --}}
@extends('layouts.login')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header text-center bg-transparent border-0">
                    <h4 class="text-dark mb-1 font-weight-bold">Preguntas de Seguridad</h4>
                </div>
                <div class="card-body">
                    <!-- Mostrar el usuario ingresado -->
                    <p class="text-secondary small text-center mb-4">
                        Usuario: <strong>{{ $username }}</strong>
                    </p>

                    <form method="POST" action="{{ route('password.verifyAnswers') }}">
                        @csrf

                        <!-- Campo oculto para pasar el usuario -->
                        <input type="hidden" name="username" value="{{ $username }}">

                        <!-- Primera Pregunta -->
                        <div class="form-group mb-3">
                            <label for="security_answer_1" class="form-label text-secondary small">
                                Primera Pregunta: {{ $question1 }}
                            </label>
                            <input id="security_answer_1" type="text"
                                class="form-control @error('security_answer_1') is-invalid @enderror"
                                name="security_answer_1" placeholder="Respuesta" required>
                            @error('security_answer_1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Segunda Pregunta -->
                        <div class="form-group mb-3">
                            <label for="security_answer_2" class="form-label text-secondary small">
                                Segunda Pregunta: {{ $question2 }}
                            </label>
                            <input id="security_answer_2" type="text"
                                class="form-control @error('security_answer_2') is-invalid @enderror"
                                name="security_answer_2" placeholder="Respuesta" required>
                            @error('security_answer_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Botón de Verificar -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                                Verificar Respuestas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
