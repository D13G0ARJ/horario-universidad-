{{-- filepath: [verify-user.blade.php](http://_vscodecontentref_/0) --}}
@extends('layouts.login')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header text-center bg-transparent border-0">
                    <h4 class="text-dark mb-1 font-weight-bold">Verificar Usuario</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.verifyUser') }}">
                        @csrf

                        <!-- Campo de Usuario -->
                        <div class="form-group mb-3">
                            <label for="username" class="form-label text-secondary small">Ingrese su Cédula</label>
                            <input id="username" type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                name="username" placeholder="Cédula" value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Botón de Verificar -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                                Verificar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensajes -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">
                    @if (session('success'))
                        Verificación Exitosa
                    @else
                        Error
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (session('success'))
                    {{ session('success') }}
                @elseif ($errors->any())
                    {{ $errors->first('username') }}
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @if (session('success'))
                    <a href="{{ route('password.securityQuestions') }}" class="btn btn-primary">Continuar</a>
                @endif
            </div>
        </div>
    </div>
</div>

@if (session('success') || $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
            messageModal.show();
        });
    </script>
@endif
@endsection
