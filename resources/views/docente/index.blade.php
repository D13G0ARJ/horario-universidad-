@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-chalkboard-teacher mr-2"></i>Listado de Docentes
            </h3>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger" id="autoCloseAlert">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    
    <script>
        setTimeout(() => {
            document.getElementById('autoCloseAlert').style.display = 'none';
        }, 3000);
    </script>
    @endif

    @if(session('success'))
    <div class="alert alert-success" id="autoCloseSuccessAlert">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('autoCloseSuccessAlert').style.display = 'none';
        }, 3000);
    </script>
    @endif

    @if(session('error'))
    <div class="alert alert-danger" id="autoCloseErrorAlert">
        {{ session('error') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('autoCloseErrorAlert').style.display = 'none';
        }, 3000);
    </script>
    @endif

    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <div class="form-group">
                <label for="statusFilter" class="form-label">Filtrar por Estado:</label>
                <select class="form-select" id="statusFilter">
                    <option value="activo" {{ $statusFilter == 'activo' ? 'selected' : '' }}>Activos</option>
                    <option value="inactivo" {{ $statusFilter == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                    <option value="todos" {{ $statusFilter == 'todos' ? 'selected' : '' }}>Todos</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Docentes Registrados
                    </h4>
                        {{-- Botón Nuevo Docente --}}
                        <a href="#" class="btn btn-success ms-auto text-dark"
                            data-bs-toggle="modal" data-bs-target="#registroModal">
                            <i class="fas fa-plus mr-1"></i>Nuevo Docente
                        </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- **CAMBIO AQUÍ:** Clases de tabla para coincidir con Coordinadores --}}
                        <table class="table table-bordered table-hover" id="docentesTable">
                            {{-- **CAMBIO AQUÍ:** Clase de thead para coincidir con Coordinadores --}}
                            <thead class="thead-dark">
                                <tr>
                                    <th style="text-align: center">Cédula</th>
                                    <th style="text-align: center">Nombre</th>
                                    <th style="text-align: center">Email</th>
                                    <th style="text-align: center">Teléfono</th>
                                    <th style="text-align: center">Dedicación</th>
                                    <th style="text-align: center">Estado</th>
                                    <th style="text-align: center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Los datos se cargarán aquí vía AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DE CONFIRMACIÓN PARA ACTIVAR/DESACTIVAR --}}
    <div class="modal fade" id="confirmarAccionModal" tabindex="-1" aria-labelledby="confirmarAccionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="confirmarAccionModalLabel">Confirmar Acción</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="mensajeConfirmacion"></p>
                    <form id="formAccionDocente" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger" id="confirmarAccionBtn">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmarEliminarModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea eliminar permanentemente a este docente? Esta acción es irreversible.</p>
                    <form id="formEliminarDocente" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@include('modals.docentes.create')
@include('modals.docentes.show')
@include('modals.docentes.edit')

@push('scripts')
<script>
    let docentesTable; // Variable global para la instancia del DataTable

    $(document).ready(function() {
        // Inicializar DataTable
        docentesTable = $('#docentesTable').DataTable({
            language: {
                emptyTable: "No hay docentes registrados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ docentes",
                infoEmpty: "Mostrando 0 docentes",
                infoFiltered: "(filtrados de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                buttons: {
                    print: "Imprimir",
                    pdf: "PDF",
                    excel: "Excel"
                }
            },
            "paging": true,
            "pageLength": 5,
            "searching": true,
            "lengthChange": false, // Oculta el selector "Mostrar X entradas"
            "info": true, // Muestra la información de paginación
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "{{ route('api.docentes.by.status') }}",
                "type": "GET",
                "data": function(d) {
                    d.status = $('#statusFilter').val();
                },
                "dataSrc": "data"
            },
            "columns": [
                { "data": "cedula_doc" },
                { "data": "name" },
                { "data": "email" },
                { "data": "telefono" },
                { "data": "dedicacion_name" },
                {
                    "data": "status",
                    "render": function(data, type, row) {
                        return data === 'activo' ?
                            '<span class="badge bg-success text-white">Activo</span>' :
                            '<span class="badge bg-danger text-white">Inactivo</span>';
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "className": "text-center",
                    "render": function(data, type, row) {
                        const cedula = row.cedula_doc;
                        const name = row.name;
                        const email = row.email;
                        const telefono = row.telefono;
                        const dedicacionName = row.dedicacion_name;
                        const dedicacionId = row.dedicacion_id;
                        const hmax = row.h_max;
                        const status = row.status;

                        let buttons = `
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-info btn-sm me-1"
                                    data-bs-toggle="modal" data-bs-target="#mostrarModal"
                                    data-cedula="${cedula}"
                                    data-name="${name}"
                                    data-email="${email}"
                                    data-telefono="${telefono}"
                                    data-dedicacion="${dedicacionName}"
                                    data-hmax="${hmax}">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button class="btn btn-primary btn-sm me-1" data-bs-toggle="modal"
                                    data-bs-target="#editarModal"
                                    data-cedula="${cedula}"
                                    data-name="${name}"
                                    data-email="${email}"
                                    data-telefono="${telefono}"
                                    data-dedicacion="${dedicacionId}">
                                    <i class="fas fa-edit"></i>
                                </button>
                        `;

                        if (status === 'activo') {
                            buttons += `
                                <button class="btn btn-secondary btn-sm me-1 btn-desactivar"
                                    data-bs-toggle="modal" data-bs-target="#confirmarAccionModal"
                                    data-cedula="${cedula}"
                                    data-nombre="${name}"
                                    data-accion="desactivar">
                                    <i class="fas fa-user-slash"></i>
                                </button>
                            `;
                        } else {
                            buttons += `
                                <button class="btn btn-success btn-sm me-1 btn-activar"
                                    data-bs-toggle="modal" data-bs-target="#confirmarAccionModal"
                                    data-cedula="${cedula}"
                                    data-nombre="${name}"
                                    data-accion="activar">
                                    <i class="fas fa-user-check"></i>
                                </button>
                            `;
                        }

                        buttons += `
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#confirmarEliminarModal"
                                    data-cedula="${cedula}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `;
                        return buttons;
                    }
                }
            ],

            // ** Configuración de DataTables para que se vea como el de Coordinadores **
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    autoPrint: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5] // Cédula, Nombre, Email, Teléfono, Dedicación, Estado
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<h3 style="margin: 5px 0; font-size: 14pt;">REPORTE DE DOCENTES</h3>' +
                                '</div>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit')
                            .find('thead th')
                            .css({
                                'background-color': '#343a40',
                                'color': '#ffffff'
                            });
                        $(win.document.body).find('table thead th:nth-child(1)').css('width', 'auto');
                        $(win.document.body).find('table thead th:nth-child(2)').css('width', 'auto');
                        $(win.document.body).find('table thead th:nth-child(3)').css('width', 'auto');
                        $(win.document.body).find('table thead th:nth-child(4)').css('width', 'auto');
                        $(win.document.body).find('table thead th:nth-child(5)').css('width', 'auto');
                        $(win.document.body).find('table thead th:nth-child(6)').css('width', 'auto');
                    }
                },
                {
                    extend: 'pdfHtml5', // Usamos pdfHtml5 para más control
                    text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    className: 'btn btn-danger mr-2',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5] // Cédula, Nombre, Email, Teléfono, Dedicación, Estado
                    },
                    customize: function(doc) {
                        doc.content.splice(0, 0, {
                            text: 'REPORTE DE DOCENTES',
                            fontSize: 14,
                            alignment: 'center',
                            margin: [0, 0, 0, 12]
                        });
                        doc.styles.tableHeader = {
                            fillColor: '#343a40',
                            color: '#ffffff',
                            fontSize: 10,
                            bold: true,
                            alignment: 'center'
                        };
                        doc.content[1].table.widths = ['auto', 'auto', 'auto', 'auto', 'auto', 'auto'];
                    }
                },
                {
                    extend: 'excelHtml5', // Usamos excelHtml5 para más control
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    className: 'btn btn-success mr-2',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5] // Cédula, Nombre, Email, Teléfono, Dedicación, Estado
                    }
                },
            ],
            // Ajustar la alineación de las columnas para que coincida con el estilo de Coordinadores
            columnDefs: [
                { targets: [0, 1, 2, 3, 4, 5, 6], className: 'text-center' } // Centrar todas las columnas por defecto
            ]
        });

        // Evento para recargar el DataTable cuando cambie el filtro de estado
        $('#statusFilter').on('change', function() {
            docentesTable.ajax.reload(null, false);
        });

        // SCRIPT PARA LLENAR EL MODAL DE EDICIÓN
        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            modal.find('#cedula_editar').val(button.data('cedula'));
            modal.find('#name_editar').val(button.data('name'));
            modal.find('#email_editar').val(button.data('email'));
            modal.find('#telefono_editar').val(button.data('telefono'));
            modal.find('#dedicacion_editar').val(button.data('dedicacion'));
            modal.find('#formEditar').attr('action', '/docentes/' + button.data('cedula'));
        });

        // SCRIPT PARA EL MODAL DE MOSTRAR (show.blade.php)
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const cedula = button.data('cedula');
            const name = button.data('name');
            const email = button.data('email');
            const telefono = button.data('telefono');
            const dedicacion = button.data('dedicacion');
            const hmax = parseFloat(button.data('hmax'));

            $('#modalCedula').text(cedula);
            $('#modalName').text(name);
            $('#modalEmail').text(email);
            $('#modalTelefono').text(telefono);
            $('#modalDedicacion').text(dedicacion);
            $('#modalHorasMax').text(Math.round(hmax) + ' Horas');

            let currentDocenteCedula = cedula;

            const modalAsignaturasList = $('#modalAsignaturasList');
            const noAsignaturasMessage = $('#noAsignaturasMessage');
            const totalHorasDocenteElement = $('#totalHorasDocente');
            modalAsignaturasList.empty();
            noAsignaturasMessage.hide();

            modalAsignaturasList.html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

            $.get(`/api/docentes/${cedula}/asignaturas`, function(response) {
                modalAsignaturasList.empty();
                if (response.asignaturas && response.asignaturas.length > 0) {
                    const listaAsignaturas = response.asignaturas
                        .map(asig =>
                            `<li class="list-group-item">
                            <strong>${asig.asignatura_id}</strong> - ${asig.name}
                                <span class="badge bg-secondary float-end">
                                    ${asig.carga_horaria_total} Horas totales.
                                </span>
                            </li>`
                        )
                        .join('');
                    modalAsignaturasList.html(listaAsignaturas);
                } else {
                    noAsignaturasMessage.show();
                }
                totalHorasDocenteElement.text(response.total_horas_docente + ' Horas');
            }).fail(function() {
                modalAsignaturasList.html('<li class="list-group-item text-danger">Error al cargar asignaturas.</li>');
            });

            const periodoSelect = $('#periodoSelect');
            periodoSelect.empty().append('<option value="">Cargando períodos...</option>');
            $.get('/api/periods', function(periods) {
                periodoSelect.empty().append('<option value="">Seleccione un Período</option>');
                periods.forEach(period => {
                    periodoSelect.append(`<option value="${period.id}">${period.nombre}</option>`);
                });
            }).fail(function() {
                periodoSelect.empty().append('<option value="">Error al cargar períodos</option>');
            });

            $('#btnCargarHorario').off('click').on('click', function() {
                const selectedPeriodId = periodoSelect.val();

                if (!selectedPeriodId) {
                    alert('Por favor, seleccione un período académico.');
                    return;
                }

                if (!currentDocenteCedula) {
                    alert('No se pudo obtener la cédula del docente. Intente recargar la página.');
                    return;
                }

                window.location.href = `/docentes/${currentDocenteCedula}/horario/${selectedPeriodId}`;
            });
        });

        // Script para confirmar eliminar
        $('#confirmarEliminarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const docenteCedula = button.data('cedula');
            const form = $(this).find('#formEliminarDocente');
            form.attr('action', `/docentes/${docenteCedula}`);
        });

        // Script para el modal de Confirmación de Activar/Desactivar
        $('#confirmarAccionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const docenteCedula = button.data('cedula');
            const docenteNombre = button.data('nombre');
            const accion = button.data('accion');
            const modal = $(this);
            const form = modal.find('#formAccionDocente');
            const confirmarBtn = modal.find('#confirmarAccionBtn');
            let mensaje = "";

            if (accion === 'desactivar') {
                mensaje = `¿Está seguro de que desea <strong class="text-danger">DESACTIVAR</strong> al docente ${docenteNombre} (${docenteCedula})? <br> Sus asignaturas serán liberadas.`;
                form.attr('action', `/docentes/${docenteCedula}/deactivate`);
                confirmarBtn.removeClass('btn-success').addClass('btn-danger').text('Desactivar');
            } else if (accion === 'activar') {
                mensaje = `¿Está seguro de que desea <strong class="text-success">ACTIVAR</strong> al docente ${docenteNombre} (${docenteCedula})?`;
                form.attr('action', `/docentes/${docenteCedula}/activate`);
                confirmarBtn.removeClass('btn-danger').addClass('btn-success').text('Activar');
            }

            modal.find('#mensajeConfirmacion').html(mensaje);
        });
    });
</script>
@endpush
@endsection