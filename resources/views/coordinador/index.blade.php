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
                    <a href="#" class="btn btn-primary">Nuevo usuario </a>
                </div>
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

@endsection