{{-- filepath: c:\Users\Alexa\OneDrive\Escritorio\horario-universidad-\resources\views\respaldo\index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-0 text-primary">
                <i class="fas fa-database mr-2"></i>Gestión de Respaldos
            </h3>
        </div>
    </div>

    <div class="row">
        <!-- Sección de Generar Respaldo -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-file-export mr-2"></i>Generar Respaldo
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info bg-light-info border-0 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>Se creará un respaldo completo de la base de datos.
                    </div>
                    <form action="{{ route('respaldo.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-2">
                            <i class="fas fa-save mr-2"></i>Generar Respaldo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sección de Restauración -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-file-import mr-2"></i>Restaurar Respaldo
                    </h4>
                </div>
                <div class="card-body">
                    @if($respaldos->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay respaldos disponibles</h5>
                        </div>
                    @else
                        <table id="tabla-respaldos" class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="text-align: center">N°</th>
                                    <th style="text-align: center">Fecha</th>
                                    <th style="text-align: center">Usuario</th>
                                    <th style="text-align: center">Archivo</th>
                                    <th style="text-align: center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($respaldos as $respaldo)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td style="text-align: center">{{ $respaldo->created_at->format('d-m-Y') }}</td>
                                    <td style="text-align: center">{{ $respaldo->usuario->cedula }}</td>
                                    <td style="text-align: center">{{ $respaldo->file_path }}</td>
                                    <td style="text-align: center">
                                        <form action="{{ route('respaldo.restore', $respaldo->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const table = $("#tabla-respaldos").DataTable({
            pageLength: 5,
            order: [[1, 'desc']],
            language: {
                emptyTable: "No hay registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(Filtrado de _MAX_ registros totales)",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'collection',
                    text: 'Reportes',
                    buttons: [
                        'copy',
                        'excel',
                        'pdf',
                        'print'
                    ]
                },
                'colvis'
            ],
            columnDefs: [
                { orderable: false, targets: [3, 4] }
            ]
        });
    });
</script>
@endpush
@endsection
