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
        <div class="col-md-4 mb-4">
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
        <div class="col-md-8 mb-4">
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
        // Configuración de SweetAlert
        <?php if(\Illuminate\Support\Facades\Session::has('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Éxito!',
                text: '<?php echo session('success'); ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if(\Illuminate\Support\Facades\Session::has('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: '<?php echo session('error'); ?>',
                showConfirmButton: true
            });
        <?php endif; ?>

        // Configuración del PDF con membrete
        const pdfConfig = {
            customize: function(doc) {
                doc.pageMargins = [40, 80, 40, 60];
                doc.content.splice(0, 0, {
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - RESPALDOS',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                doc.content[1].text = 'REPORTE DE RESPALDOS';
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

                // Ajustar anchos de columnas (todas excepto acciones)
                const columnCount = $("#tabla-respaldos thead tr th").length - 1;
                doc.content[2].table.widths = Array(columnCount).fill('auto');
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Configuración de DataTables
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
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Incluye N°, Nombre, Fecha y Archivo (excluye Acciones)
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<img src="{{ asset('images/logo.jpg') }}" style="height: 80px; margin-bottom: 10px;"/>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - RESPALDOS</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE RESPALDOS</h2>' +
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
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                    customize: pdfConfig.customize,
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Incluye N°, Nombre, Fecha y Archivo (excluye Acciones)
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Respaldos Registrados',
                    exportOptions: {
                        columns: [0, 1, 2, 3] // Incluye N°, Nombre, Fecha y Archivo (excluye Acciones)
                    },
                    className: 'btn btn-success mr-2'
                }
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [4] // Columna de Acciones no ordenable
                },
                {
                    targets: -1, // Columna de Acciones (última columna)
                    visible: true,
                    exportable: false, // No se exporta
                    printable: false // No se imprime
                },
                {
                    targets: 3, // Columna de Archivo
                    className: 'text-center'
                }
            ]
        });
    });
</script>
@endpush
@endsection
