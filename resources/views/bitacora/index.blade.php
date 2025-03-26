@extends('layouts.admin')

@section('content')
<div class="row">
    <h3>Registros de Bitácora</h3>
</div>
<br>

<!-- Modal para mensaje de no resultados -->
<div class="modal fade" id="modalNoResultados" tabindex="-1" role="dialog" aria-labelledby="modalNoResultadosLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalNoResultadosLabel">Sin resultados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                No se encontraron registros para el rango de fechas seleccionado.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cerrar-modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <!--<div class="card-header">
                <h3 class="card-title">Actividades del Sistema</h3>
            </div>
            <div class="card-body">
                Filtros de fechafrt\ -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="fecha-inicio" class="form-label">Fecha de Inicio:</label>
                        <input type="date" id="fecha-inicio" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="fecha-fin" class="form-label">Fecha de Fin:</label>
                        <input type="date" id="fecha-fin" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="filtrar-fechas" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="reset-filtros" class="btn btn-outline-secondary w-100">Limpiar</button>
                    </div>
                </div>

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
                            <td style="text-align: center">{{ $registro->created_at->format('d-m-Y') }}</td>
                            <td style="text-align: center">{{ $registro->user->cedula }}</td>
                            <td>{{ $registro->accion }}</td>
                            <td style="text-align: center">{{ $registro->created_at->format('H:i:s') }}</td>
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
        const table = $("#tabla-bitacora").DataTable({
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
                { orderable: false, targets: [2, 3] }
            ]
        });

        // Función para aplicar filtros
        function aplicarFiltros() {
            const inicio = $('#fecha-inicio').val();
            const fin = $('#fecha-fin').val();

            if (inicio && fin && new Date(inicio) > new Date(fin)) {
                alert('La fecha de inicio no puede ser mayor a la fecha final');
                return false;
            }

            table.draw();

            // Verificar resultados después de filtrar
            setTimeout(() => {
                if (table.rows({ filter: 'applied' }).count() === 0) {
                    $('#modalNoResultados').modal('show');
                }
            }, 100);
        }

        // Eventos
        $('#filtrar-fechas').on('click', aplicarFiltros);

        $('#reset-filtros').on('click', function() {
            $('#fecha-inicio, #fecha-fin').val('');
            table.draw();
        });

        // Función de filtrado por fechas
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                const minDate = $('#fecha-inicio').val();
                const maxDate = $('#fecha-fin').val();

                if (!minDate && !maxDate) {
                    return true;
                }

                const rowDate = new Date(data[1]).setHours(0, 0, 0, 0);
                const min = minDate ? new Date(minDate).setHours(0, 0, 0, 0) : null;
                const max = maxDate ? new Date(maxDate).setHours(0, 0, 0, 0) : null;

                if (min && max) {
                    return rowDate >= min && rowDate <= max;
                } else if (min) {
                    return rowDate >= min;
                } else if (max) {
                    return rowDate <= max;
                }
                return true;
            }
        );

        // Cerrar modal al hacer clic en el botón "Cerrar"
        $('#cerrar-modal').on('click', function() {
            $('#modalNoResultados').modal('hide');
        });
    });
</script>
@endpush
@endsection
