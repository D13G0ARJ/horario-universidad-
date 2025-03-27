@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-clipboard-list mr-2"></i>Registros de Bitácora
            </h3>
        </div>
    </div>

    <!-- Modal para mensaje de no resultados -->
    <div class="modal fade" id="modalNoResultados" tabindex="-1" role="dialog" aria-labelledby="modalNoResultadosLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalNoResultadosLabel">
                        <i class="fas fa-exclamation-circle mr-2"></i>Sin resultados
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    No se encontraron registros para el rango de fechas seleccionado.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de búsqueda mejorados -->
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="fecha-inicio" class="form-label">Desde:</label>
            <input type="date" id="fecha-inicio" class="form-control" max="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-4">
            <label for="fecha-fin" class="form-label">Hasta:</label>
            <input type="date" id="fecha-fin" class="form-control" max="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button id="filtrar-fechas" class="btn btn-primary w-100">
                <i class="fas fa-search mr-2"></i>Buscar
            </button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button id="reset-filtros" class="btn btn-outline-secondary w-100">
                <i class="fas fa-broom mr-2"></i>Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Tabla de registros -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-database mr-2"></i>Actividades del Sistema
                    </h4>
                </div>
                <div class="card-body">
                    <table id="tabla-bitacora" class="table table-bordered table-hover">
                        <thead class="thead-dark">
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
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Configuración del PDF (sin imagen)
        const pdfConfig = {
            customize: function(doc) {
                doc.pageMargins = [40, 80, 40, 60];

                // Encabezado con membrete
                doc.content.splice(0, 0, {
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - BITÁCORA',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                doc.content[1].text = 'REPORTE DE BITÁCORA';
                doc.content[1].alignment = 'center';
                doc.content[1].fontSize = 12;
                doc.content[1].margin = [0, 0, 0, 10];

                doc['footer'] = function(currentPage, pageCount) {
                    return {
                        text: 'Página ' + currentPage.toString() + ' de ' + pageCount,
                        alignment: 'center',
                        fontSize: 8,
                        margin: [40, 10, 40, 20]
                    };
                };

                doc.content[2].table.widths = ['auto', 'auto', 'auto', '*', 'auto'];
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Inicializar DataTables
        const table = $("#tabla-bitacora").DataTable({
            pageLength: 10,
            order: [[1, 'desc']],
            language: {
                emptyTable: "No hay registros disponibles",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 registros",
                infoFiltered: "(filtrados de _MAX_ registros totales)",
                search: "Buscar:",
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
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Descargar',
                    title: '',
                    autoPrint: true,
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<img src="{{ asset('images/logo.jpg') }}" style="height: 80px; margin-bottom: 10px;"/>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - BITÁCORA</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE BITÁCORA</h2>' +
                                '</div>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');

                        $(win.document.body).append(
                            '<div style="text-align: center; margin-top: 20px; font-size: 8pt;">' +
                            '<p>Generado el: ' + new Date().toLocaleDateString('es-VE') + '</p>' +
                            '</div>'
                        );
                    },
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                    customize: pdfConfig.customize,
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Bitácora del Sistema',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn btn-success mr-2'
                },
            ],
            columnDefs: [
                { orderable: false, targets: [2, 3] }
            ]
        });

        // Función para aplicar filtros
        function aplicarFiltros() {
            const inicio = $('#fecha-inicio').val();
            const fin = $('#fecha-fin').val();

            if (inicio && fin) {
                if (new Date(inicio) > new Date(fin)) {
                    toastr.error('La fecha de inicio no puede ser mayor a la fecha final');
                    return false;
                }

                const minDate = new Date(inicio).setHours(0, 0, 0, 0);
                const maxDate = new Date(fin).setHours(23, 59, 59, 999);

                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        const rowDate = new Date(data[1].split('-').reverse().join('-')).getTime();
                        return rowDate >= minDate && rowDate <= maxDate;
                    }
                );
            }

            table.draw();
            $.fn.dataTable.ext.search.pop();

            if (table.rows({ filter: 'applied' }).count() === 0) {
                $('#modalNoResultados').modal('show');
            }
        }

        // Eventos
        $('#filtrar-fechas').on('click', aplicarFiltros);

        $('#reset-filtros').on('click', function() {
            $('#fecha-inicio, #fecha-fin').val('');
            table.search('').columns().search('').draw();
        });

        // Configurar fecha máxima como hoy
        const today = new Date().toISOString().split('T')[0];
        $('#fecha-inicio, #fecha-fin').attr('max', today);
    });
</script>
@endpush
@endsection
