@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-book mr-2"></i>Listado de Asignaturas
            </h3>
        </div>
    </div>

    <!-- Tabla de asignaturas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Asignaturas Registradas
                    </h4>
                    <a href="#" class="btn btn-light ms-auto text-dark"
                        data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="fas fa-plus mr-1"></i>Nueva Asignatura
                    </a>
                </div>
                <div class="card-body">
                    <table id="tabla-asignaturas" class="table table-bordered table-hover w-100">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th style="text-align: center">Código</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Secciones</th>
                                <th style="text-align: center">Docentes</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignaturas as $asignatura)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $asignatura->asignatura_id }}</td>
                                <td>{{ $asignatura->name }}</td>
                                <td style="text-align: center">
                                    @foreach($asignatura->secciones as $seccion)
                                    <span class="badge bg-success">{{ $seccion->codigo_seccion }}</span>
                                    @endforeach
                                </td>
                                <td style="text-align: center">
                                    @foreach($asignatura->docentes as $docente)
                                    <span class="badge bg-info">{{ $docente->name }}</span>
                                    @endforeach
                                </td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-asignatura_id="{{ $asignatura->asignatura_id }}"
                                            data-name="{{ $asignatura->name }}"
                                            data-docentes="{{ $asignatura->docentes->pluck('name')->toJson() }}"
                                            data-secciones="{{ $asignatura->secciones->pluck('codigo_seccion')->toJson() }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal"
                                            data-asignatura_id="{{ $asignatura->asignatura_id }}"
                                            data-name="{{ $asignatura->name }}"
                                            data-docentes="{{ $asignatura->docentes->pluck('cedula_doc')->toJson() }}"
                                            data-secciones="{{ $asignatura->secciones->pluck('codigo_seccion')->toJson() }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <form action="{{ route('asignatura.destroy', $asignatura->asignatura_id) }}" method="POST" class="delete-form">
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
@include('modals.asignaturas.create', [
    'docentes' => $docentes,
    'secciones' => $secciones
])
@include('modals.asignaturas.show')
@include('modals.asignaturas.edit')

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/datatables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Configuración DataTables
        const table = $('#tabla-asignaturas').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA</h3>' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">DE LA FUERZA ARMADA NACIONAL</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">EXTENSIÓN LOS TEQUES</h4>' +
                                '<h4 style="margin: 5px 0; font-size: 12pt;">SISTEMA DE GESTIÓN DE HORARIOS - ASIGNATURAS</h4>' +
                                '<h2 style="margin: 15px 0; font-size: 16pt;">REPORTE DE ASIGNATURAS</h2>' +
                                '</div>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(doc) {
                        doc.content.splice(0, 0, {
                            text: 'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA\nDE LA FUERZA ARMADA NACIONAL\nEXTENSIÓN LOS TEQUES\nSISTEMA DE GESTIÓN DE HORARIOS - ASIGNATURAS',
                            alignment: 'center',
                            fontSize: 10,
                            bold: true,
                            margin: [0, 0, 0, 10]
                        });
                        doc.content[1].text = 'REPORTE DE ASIGNATURAS';
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    title: 'Asignaturas Registradas',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            columnDefs: [
                { targets: [0, 1, 3, 4, 5], className: 'text-center' },
                { targets: -1, orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            autoWidth: false,
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100]
        });

        // SweetAlerts
        @if(session('alert'))
            Swal.fire({
                icon: '{{ session("alert")["type"] ?? "info" }}',
                title: '{{ session("alert")["title"] ?? "Notificación" }}',
                text: '{{ session("alert")["message"] ?? "" }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Confirmación eliminación
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: '¿Eliminar Asignatura?',
                text: "¡Se eliminarán todas las secciones y relaciones asociadas!",
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
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            modal.find('#modalCode').text(button.data('asignatura_id'));
            modal.find('#modalName').text(button.data('name'));
            modal.find('#modalDocentes').text(button.data('docentes'));
            modal.find('#modalSecciones').text(button.data('secciones'));
        });

        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            const docentes = JSON.parse(button.data('docentes'));
            const secciones = JSON.parse(button.data('secciones'));

            modal.find('#asignatura_id_editar').val(button.data('asignatura_id'));
            modal.find('#name_editar').val(button.data('name'));
            modal.find('#docentes_editar').val(docentes).trigger('change');
            modal.find('#secciones_editar').val(secciones).trigger('change');
            modal.find('#formEditar').attr('action', '/asignaturas/' + button.data('asignatura_id'));
        });
    });
</script>
@endpush