@extends ('layouts.admin')

@section('rol')
    <h1>Administrador</h1>

@endsection

@section('content')
    <div class="row">
        <h1>Datos del Docente</h1>
    </div>

    <div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Docentes Registrados</h3>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Nombre del usuario</label>
                                <p>{{$usuario->name}}</p>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Email</label>
                                <p>{{$usuario->email}}</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{url('/usuarios')}}" class="btn btn-secondary">Volver</a>

                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>
@endsection



