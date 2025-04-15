@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-layer-group mr-2"></i>Gestión de Secciones
            </h3>
        </div>
    </div>

    <!-- Tabla de secciones -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Secciones Registradas
                    </h4>
                    <a href="#" class="btn btn-light ms-auto text-dark"
                    data-bs-toggle="modal" data-bs-target="#crearSeccionModal">
                    <i class="fas fa-plus mr-1"></i>Nueva Sección
                </a>
                </div>
                <div class="card-body">
                    <table id="tabla-secciones" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th>Código</th>
                                <th>Aula</th>
                                <th>Carrera</th>
                                <th>Turno</th>
                                <th>Semestre</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($secciones as $seccion)
                            <tr>
                                <td style="text-align: center"></td>
                                <td>{{ $seccion->codigo_seccion }}</td>
                                <td>{{ $seccion->aula->nombre }}</td>
                                <td>{{ $seccion->carrera->nombre }}</td>
                                <td>{{ $seccion->turno->nombre }}</td>
                                <td>{{ $seccion->semestre->numero }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarSeccionModal"
                                            data-codigo="{{ $seccion->codigo_seccion }}"
                                            data-aula="{{ $seccion->aula->nombre }}"
                                            data-carrera="{{ $seccion->carrera->nombre }}"
                                            data-turno="{{ $seccion->turno->nombre }}"
                                            data-semestre="{{ $seccion->semestre->numero }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarSeccionModal"
                                            data-codigo="{{ $seccion->codigo_seccion }}"
                                            data-aula_id="{{ $seccion->aula_id }}"
                                            data-carrera_id="{{ $seccion->carrera_id }}"
                                            data-turno_id="{{ $seccion->turno_id }}"
                                            data-semestre_id="{{ $seccion->semestre_id }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <form action="{{ route('secciones.destroy', $seccion->codigo_seccion) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
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
</div>

<!-- Inclusión de modals -->
@include('modals.secciones.create')
@include('modals.secciones.show')
@include('modals.secciones.edit')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Configuración del PDF
        const pdfConfig = {
            customize: function(doc) {
                doc.pageMargins = [40, 80, 40, 60];
                doc.content.splice(0, 0, {
                    text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - SECCIONES',
                    alignment: 'center',
                    fontSize: 10,
                    bold: true,
                    margin: [0, 0, 0, 10]
                });

                doc.content[1].text = 'REPORTE DE SECCIONES';
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

                doc.content[2].table.widths = ['auto', '*', '*', '*', '*', '*', 'auto'];
                doc.content[2].table.headerRows = 1;
                doc.styles.tableHeader.fillColor = '#343a40';
                doc.styles.tableHeader.color = '#ffffff';
                doc.content[2].layout = 'lightHorizontalLines';
            }
        };

        // Configuración DataTables
        const table = $("#tabla-secciones").DataTable({
            pageLength: 10,
            language: {
                emptyTable: "No hay secciones registradas",
                info: "Mostrando _START_ a _END_ de _TOTAL_ secciones",
                infoEmpty: "Mostrando 0 secciones",
                infoFiltered: "(filtradas de _MAX_ registros totales)",
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
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
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
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - SECCIONES</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE SECCIONES</h2>' +
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
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Secciones Registradas',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    className: 'btn btn-success mr-2'
                },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    className: 'text-center',
                    orderable: false
                },
                { 
                    targets: [6], 
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // SweetAlerts
        @if(session('alert'))
            Swal.fire({
                icon: '{{ session('alert')['type'] }}',
                title: '{{ session('alert')['title'] }}',
                text: '{{ session('alert')['message'] }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Confirmación eliminación
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: '¿Eliminar Sección?',
                text: "¡Esta acción no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Handlers para modals
        $('#mostrarSeccionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            modal.find('#modalCodigo').text(button.data('codigo'));
            modal.find('#modalAula').text(button.data('aula'));
            modal.find('#modalCarrera').text(button.data('carrera'));
            modal.find('#modalTurno').text(button.data('turno'));
            modal.find('#modalSemestre').text(button.data('semestre'));
        });

        $('#editarSeccionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            
            modal.find('#edit_codigo').val(button.data('codigo'));
            modal.find('#edit_aula_id').val(button.data('aula_id'));
            modal.find('#edit_carrera_id').val(button.data('carrera_id'));
            modal.find('#edit_turno_id').val(button.data('turno_id'));
            modal.find('#edit_semestre_id').val(button.data('semestre_id'));
            
            modal.find('#formEditarSeccion').attr('action', '/secciones/' + button.data('codigo'));
        });
    });
</script>
@endpush
@endsection