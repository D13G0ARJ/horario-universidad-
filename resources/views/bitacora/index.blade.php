{{-- filepath: c:\Users\Alexa\OneDrive\Escritorio\horario-universidad-\resources\views\bitacora\index.blade.php --}}
@extends ('layouts.admin')

@section('content')
<div class="row">
    <h1>Registros de Bitácora</h1>
</div>
<br>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Actividades del Sistema</h3>
            </div>
            <div class="card-body">
                <table id="tabla-bitacora" class="table table-bordered table-hover">
                    <thead class='thead-dark'>
                        <tr>
                            <th style="text-align: center">N°</th>
                            <th style="text-align: center">Fecha</th>
                            <th style="text-align: center">Actor</th>
                            <th style="text-align: center">Acción</th>
                            <th style="text-align: center">Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bitacoras as $registro)
                        <tr>
                            <td style="text-align: center">{{ $loop->iteration }}</td>
                            <td style="text-align: center">
                                {{ $registro->created_at->format('d/m/Y') }}
                            </td>
                            <td style="text-align: center">{{ $registro->user->cedula }}</td>
                            <td>{{ $registro->accion }}</td>
                            <td style="text-align: center">
                                {{ $registro->created_at->format('H:i:s') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $("#tabla-bitacora").DataTable({
            pageLength: 10,
            order: [[0, 'desc']], // Ordenar por la primera columna de forma descendente
            language: {
                emptyTable: "No hay registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(Filtrado de _MAX_ registros totales)",
                search: "Buscador:",
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
                { orderable: false, targets: [2, 3] } // Deshabilitar ordenación para Actor y Acción
            ]
        }).buttons().container().appendTo('#tabla-bitacora_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush
@endsection
