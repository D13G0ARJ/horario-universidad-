@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Listado de coordinadores</h1>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Coordinadores Registrados</h3>
                <div class="card-tools">
    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registroModal">
        Nuevo usuario
    </a>
</div>

            <

            </div>
            <div class="card-body col-12" style="width: 100%;">
                <table class="table table-bordered table-striped table-hover w-100" id="tabla">
                    <thead>
                        <tr>
                            <th><center>Nro</center></th>
                            <th><center>Cédula</center></th>
                            <th><center>Nombre</center></th>
                            <th><center>Email</center></th>
                            <th><center>Acciones</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $contador = 0;
                        @endphp

                        @foreach ($usuarios as $usuario)
                        @php
                        $contador++;
                        @endphp
                        <tr>
                            <td>{{ $contador }}</td>
                            <td>{{ $usuario->cedula }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td style="text-align: center;">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <!-- Botón para ver -->
                                    {{-- 
                                    <a href="{{ route('/', $usuario->id) }}" class="btn btn-info" type="button"> </a>

                                    <!-- Botón para editar -->
                                    <a href="{{ route('/', $usuario->id) }}" class="btn btn-success" type="button"> </a>

                                    <!-- Formulario para eliminar -->
                                    <form id="deleteForm-{{ $usuario->id }}" action="{{ route('/', $usuario->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger delete-button"> </button>
                                    </form>
                                    --}}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal de registro -->
            <!-- Modal -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-registro" id="registroModalLabel">Registrar Nuevo Coordinador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de registro -->
                <form method="POST" action="{{ route('coordinador.store') }}">
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

                    <!-- Campo de Nombre -->
                    <div class="form-group mb-3">
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
                    <div class="form-group mb-3">
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
                    <div class="form-group mb-3">
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
                    <div class="form-group mb-3">
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

                    <!-- Botón de Registro -->
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                            <i class="fas fa-user-plus me-2 small"></i>Registrarse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection