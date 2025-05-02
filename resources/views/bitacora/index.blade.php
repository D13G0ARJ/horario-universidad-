@extends('layouts.admin')
@section('style')
<style>#mensaje-inicial {
    transition: all 0.3s ease;
    background-color: #f8f9fa;
    border-radius: 8px;
}

#mensaje-inicial h4 {
    font-weight: 300;
    letter-spacing: 0.5px;
}
</style>
@endsection


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
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    No se encontraron registros para el rango de fechas seleccionado.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                        
                        </tbody>
                    </table>
                    <div id="mensaje-inicial" class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">Utilice los filtros para visualizar los registros</h4>
    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Configuración del PDF SIN logo
        const pdfConfig = {
            customize: function(doc) {
                doc.pageMargins = [40, 60, 40, 60]; // Ajuste de márgenes superior
                
                // Encabezado solo texto
                doc.content.splice(0, 0, {
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - BITÁCORA',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                // Título principal
                doc.content[1].text = 'REPORTE DE BITÁCORA';
                doc.content[1].alignment = 'center';
                doc.content[1].fontSize = 12;
                doc.content[1].margin = [0, 0, 0, 10];

                // Footer
                doc['footer'] = function(currentPage, pageCount) {
                    return {
                        columns: [
                            {
                                text: 'Generado el: ' + new Date().toLocaleDateString('es-VE'),
                                alignment: 'left',
                                fontSize: 8,
                                margin: [40, 0, 0, 0]
                            },
                            {
                                text: 'Página ' + currentPage.toString() + ' de ' + pageCount,
                                alignment: 'center',
                                fontSize: 8
                            }
                        ],
                        margin: [40, 10, 40, 20]
                    };
                };

                // Ajustes de tabla
                doc.content[2].table.widths = ['10%', '15%', '15%', '45%', '15%'];
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Inicializar DataTables
        const table = $('#tabla-bitacora').DataTable({
            dom: 'Bfrtip',
            pageLength: 5,
            ordering: true,
            order: [[1, 'desc']],
            language: {
                emptyTable: "No hay registros para mostrar",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 registros",
                infoFiltered: "(filtrados de _MAX_ registros)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            columns: [
                { data: 0, className: 'text-center' }, // N°
                { data: 1, className: 'text-center' }, // Fecha
                { data: 2, className: 'text-center' }, // Actor
                { data: 3, className: 'text-center' }, // Acción
                { data: 4, className: 'text-center' }  // Hora
            ],
            columnDefs: [
                { width: '10%', targets: 0 },  // N°
                { width: '15%', targets: 1 },  // Fecha
                { width: '15%', targets: 2 },  // Actor
                { width: '45%', targets: 3 },  // Acción
                { width: '15%', targets: 4 }   // Hora
            ],
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    className: 'btn btn-primary',
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<img src="{{ asset("images/logo.jpg") }}" style="height: 80px; margin-bottom: 10px;"/>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - BITÁCORA</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE BITÁCORA</h2>' +
                                '</div>'
                            );

                        $(win.document.body).append(
                            '<div style="text-align: center; margin-top: 20px; font-size: 8pt;">' +
                            '<p>Generado el: ' + new Date().toLocaleDateString('es-VE') + '</p>' +
                            '</div>'
                        );
                    }
                },
    {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
        className: 'btn btn-danger',
        customize: pdfConfig.customize,
        orientation: 'portrait',
        pageSize: 'A4',
        exportOptions: {
            columns: ':visible'
        },
        // Añade esta configuración:
        action: function (e, dt, node, config) {
            config.filename = 'Reporte_Bitacora_' + new Date().toISOString().split('T')[0];
            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
        }
    },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                className: 'btn btn-success',
                title: 'Registros de Bitácora'
            }
        ],
        initComplete: function() {
                $('#tabla-bitacora').hide();
                $('#mensaje-inicial').show();
            }
        });

        // Función para cargar datos via AJAX
        async function cargarDatos(fechaInicio, fechaFin) {
            try {
                const response = await $.ajax({
                    url: '/bitacora/filtrar',
                    method: 'GET',
                    data: { 
                        inicio: fechaInicio, 
                        fin: fechaFin 
                    }
                });

                if (response.length > 0) {
                    table.clear().rows.add(response).draw();
                    $('#tabla-bitacora').fadeIn(500);
                    $('#mensaje-inicial').hide();
                } else {
                    $('#modalNoResultados').modal('show');
                    $('#tabla-bitacora').hide();
                    $('#mensaje-inicial').show();
                }
            } catch (error) {
                console.error('Error:', error);
                toastr.error('Error al cargar los datos');
            }
        }

        // Eventos
        $('#filtrar-fechas').click(function() {
            const fechaInicio = $('#fecha-inicio').val();
            const fechaFin = $('#fecha-fin').val();

            if (!fechaInicio || !fechaFin) {
                toastr.error('Debe seleccionar ambas fechas');
                return;
            }

            if (new Date(fechaInicio) > new Date(fechaFin)) {
                toastr.error('La fecha inicial no puede ser mayor a la final');
                return;
            }

            cargarDatos(fechaInicio, fechaFin);
        });

        $('#reset-filtros').click(function() {
            $('#fecha-inicio, #fecha-fin').val('');
            table.clear().draw();
            $('#tabla-bitacora').hide();
            $('#mensaje-inicial').fadeIn(500);
        });

        // Configurar fecha máxima
        const today = new Date().toISOString().split('T')[0];
        $('#fecha-inicio, #fecha-fin').attr('max', today);
    });
</script>
@endpush

@endsection
