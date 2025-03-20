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
                                    <th>
                                        <center>Nro</center>
                                    </th>
                                    <th>
                                        <center>Cédula</center>
                                    </th>
                                    <th>
                                        <center>Nombre</center>
                                    </th>
                                    <th>
                                        <center>Email</center>
                                    </th>
                                    <th>
                                        <center>Acciones</center>
                                    </th>
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
                                            <!-- Botón para Mostrar -->
     <!-- Botón para Mostrar -->
                                            <button class="btn btn-info btn-sm"
data-bs-toggle="modal"
data-bs-target="#mostrarModal"
data-id="{{ $usuario->id }}"
data-name="{{ $usuario->name }}"
data-cedula="{{ $usuario->cedula }}"
data-email="{{ $usuario->email }}"
data-security-question-1="{{ $usuario->security_question_1 }}"
data-security-answer-1="{{ $usuario->security_answer_1 }}"
data-security-question-2="{{ $usuario->security_question_2 }}"
data-security-answer-2="{{ $usuario->security_answer_2 }}">
<i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Botón para Editar -->
                                            <button class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editarModal"
                                                data-id="{{ $usuario->id }}"
                                                data-name="{{ $usuario->name }}"
                                                data-cedula="{{ $usuario->cedula }}"
                                                data-email="{{ $usuario->email }}">
                                                <i class="fas fa-pencil-alt"></i>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal"
                                                data-action="{{ route('coordinador.destroy', $usuario->cedula) }}">
                                                <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
<!-- Modal de registro -->
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

<!-- Pregunta de Seguridad 1 -->
<div class="form-group mb-3">
    <label for="security_question_1" class="form-label text-secondary small">Primera Pregunta de Seguridad</label>
    <select id="security_question_1" name="security_question_1"
        class="form-select @error('security_question_1') is-invalid @enderror" required>
        <option value="" disabled selected>Seleccione una pregunta</option>
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
        <option value="" disabled selected>Seleccione una pregunta</option>
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
                    <!-- Botón de Registro -->
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-md rounded-pill py-2">
                            <i class="fas fa-user-plus me-2 small"></i>Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Mostrar -->
<div class="modal fade" id="mostrarModal" tabindex="-1" aria-labelledby="mostrarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Coordinador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre:</label>
                    <p id="modalNombre"></p>
                </div>
                <div class="form-group">
                    <label>Cédula:</label>
                    <p id="modalCedula"></p>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <p id="modalEmail"></p>
                </div>
                <div class="form-group">
                    <label>Primera Pregunta de Seguridad:</label>
                    <p id="modalPregunta1"></p>
                </div>
                <div class="form-group">
                    <label>Respuesta a la Primera Pregunta:</label>
                    <p id="modalRespuesta1"></p>
                </div>
                <div class="form-group">
                    <label>Segunda Pregunta de Seguridad:</label>
                    <p id="modalPregunta2"></p>
                </div>
                <div class="form-group">
                    <label>Respuesta a la Segunda Pregunta:</label>
                    <p id="modalRespuesta2"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
    <!-- Script para llenar el modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mostrarModal = document.getElementById('mostrarModal');
            mostrarModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Obtener datos del botón
                const nombre = button.getAttribute('data-name');
                const cedula = button.getAttribute('data-cedula');
                const email = button.getAttribute('data-email');
                const pregunta1 = button.getAttribute('data-security-question-1');
                const respuesta1 = button.getAttribute('data-security-answer-1');
                const pregunta2 = button.getAttribute('data-security-question-2');
                const respuesta2 = button.getAttribute('data-security-answer-2');

                // Actualizar los campos del modal
                mostrarModal.querySelector('#modalNombre').textContent = nombre;
                mostrarModal.querySelector('#modalCedula').textContent = cedula;
                mostrarModal.querySelector('#modalEmail').textContent = email;
                mostrarModal.querySelector('#modalPregunta1').textContent = pregunta1;
                mostrarModal.querySelector('#modalRespuesta1').textContent = respuesta1;
                mostrarModal.querySelector('#modalPregunta2').textContent = pregunta2;
                mostrarModal.querySelector('#modalRespuesta2').textContent = respuesta2;
            });
        });
    </script>


    <!-- Modal para Editar -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Coordinador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('coordinador.update', $usuario->cedula) }}" id="formEditar">
                    @csrf
                    @method('PUT')

                    <!-- Cédula (solo lectura) -->
                    <div class="form-group mb-3">
                        <label for="cedula_editar" class="form-label">Cédula</label>
                        <input type="text"
                            class="form-control"
                            name="cedula"
                            id="cedula_editar"
                            readonly required> <!-- Bloquea la edición -->
                        @error('cedula')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div class="form-group mb-3">
                        <label for="name_editar" class="form-label">Nombre</label>
                        <input type="text"
                            class="form-control"
                            name="name"
                            id="name_editar"
                            required>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Correo -->
                    <div class="form-group mb-3">
                        <label for="email_editar" class="form-label">Email</label>
                        <input type="email"
                            class="form-control"
                            name="email"
                            id="email_editar"
                            required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editarModal = document.getElementById('editarModal');
            editarModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Obtener datos del botón
                const cedula = button.getAttribute('data-cedula');
                const name = button.getAttribute('data-name');
                const email = button.getAttribute('data-email');

                // Actualizar el formulario
                const form = document.getElementById('formEditar');
                form.action = `/coordinadores/${cedula}`; // Actualizar la URL de la acción

                // Llenar los campos
                document.getElementById('cedula_editar').value = cedula;
                document.getElementById('name_editar').value = name;
                document.getElementById('email_editar').value = email;
            });
        });
    </script>

    <!-- Botón para Eliminar -->


<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="confirmarEliminarModalLabel">Confirmar Eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar este coordinador?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
            <form id="formEliminar" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Sí, eliminar</button>
            </form>
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmarEliminarModal = document.getElementById('confirmarEliminarModal');
    const formEliminar = document.getElementById('formEliminar');

    confirmarEliminarModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const action = button.getAttribute('data-action'); // Obtener la URL de acción del botón

        // Actualizar la acción del formulario
        formEliminar.action = action;
    });
});
</script>
    @endsection
