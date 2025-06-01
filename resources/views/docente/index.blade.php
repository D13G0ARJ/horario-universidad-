
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Título principal -->
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

    <!-- Tabla de docentes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Docentes Registrados
                    </h4>
                    <a href="#" class="btn btn-success ms-auto text-dark"
                        data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="fas fa-plus mr-1"></i>Nuevo Docente
                    </a>
                </div>
                <div class="card-body">
                    <table id="tabla-docentes" class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align: center">Cédula</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Correo</th>
                                <th style="text-align: center">Teléfono</th>
                                <th style="text-align: center">Dedicación</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($docentes as $docente)
                            <tr>
                                <td style="text-align: center">{{ $docente->cedula_doc }}</td>
                                <td>{{ $docente->name }}</td>
                                <td>{{ $docente->email }}</td>
                                <td>{{ $docente->telefono }}</td>
                                <td style="text-align: center">{{ $docente->dedicacion->dedicacion ?? 'Sin dedicación' }}</td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón para Mostrar -->
                                        <button class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#mostrarModal"
                                            data-cedula="{{ $docente->cedula_doc }}"
                                            data-name="{{ $docente->name }}"
                                            data-email="{{ $docente->email }}"
                                            data-telefono="{{ $docente->telefono }}"
                                            data-dedicacion="{{ $docente->dedicacion->dedicacion ?? 'Sin dedicación' }}"
                                            data-hmax="{{ $docente->dedicacion->h_max ?? 0 }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botón para Editar -->
                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal"
                                            data-cedula="{{ $docente->cedula_doc }}"
                                            data-name="{{ $docente->name }}"
                                            data-email="{{ $docente->email }}"
                                            data-telefono="{{ $docente->telefono }}"
                                            data-dedicacion="{{ $docente->dedicacion->dedicacion ?? '' }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Botón para Eliminar -->
                                        <button class="btn btn-danger btn-sm btn-eliminar"
                                            data-id="{{ $docente->cedula_doc }}"
                                            data-name="{{ $docente->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<!-- Modals -->
@include('modals.docentes.create')
@include('modals.docentes.show')
@include('modals.docentes.edit')

@push('scripts')
<script>
    $(document).ready(function() {
        // Configuración del DataTable
        const table = $("#tabla-docentes").DataTable({
            pageLength: 10,
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
                }
            },
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'print',
                    text: '<i class="fas fa-print mr-2"></i>Imprimir',
                    title: '',
                    autoPrint: true,
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
                            .css('font-size', 'inherit');
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    className: 'btn btn-danger mr-2'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                    className: 'btn btn-success mr-2'
                },
            ],
            columnDefs: [{
                targets: [0, 3, 4],
                className: 'text-center'
            }]
        });

        // Confirmación de eliminación con SweetAlert
        $('.btn-eliminar').on('click', function() {
            const docenteId = $(this).data('id');
            const docenteName = $(this).data('name');

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¡Vas a eliminar al docente "${docenteName}"!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear y enviar el formulario de eliminación
                    const form = $(`<form method="POST" action="/docentes/${docenteId}">`);
                    form.append('@csrf');
                    form.append('@method("DELETE")');
                    $('body').append(form);
                    form.submit();
                }
            });
        });

        // Script para llenar el modal de mostrar (consolidado)
        $('#mostrarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const cedula = button.data('cedula');
            const name = button.data('name');
            const email = button.data('email');
            const telefono = button.data('telefono');
            const dedicacion = button.data('dedicacion');
            const hmax = parseFloat(button.data('hmax')); // Obtener h_max como flotante

            // Asignar los datos al modal (usa los IDs del show.blade.php)
            $('#modalCedula').text(cedula);
            $('#modalName').text(name);
            $('#modalEmail').text(email);
            $('#modalTelefono').text(telefono);
            $('#modalDedicacion').text(dedicacion);
            $('#modalHorasMax').text(Math.round(hmax) + ' Horas'); // Redondear a entero para mostrar

            // Guardar la cédula del docente actual para el botón de horario
            let currentDocenteCedula = cedula;

            // Limpiar asignaturas anteriores y el mensaje de "no asignaturas"
            const modalAsignaturasList = $('#modalAsignaturasList');
            const noAsignaturasMessage = $('#noAsignaturasMessage');
            const totalHorasDocenteElement = $('#totalHorasDocente');
            modalAsignaturasList.empty();
            noAsignaturasMessage.hide();

            // Mostrar spinner mientras se cargan las asignaturas
            modalAsignaturasList.html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

            // Cargar asignaturas con AJAX (usando la ruta que ya tenías y el método .get de jQuery)
            $.get(`/api/docentes/${cedula}/asignaturas`, function(response) {
                modalAsignaturasList.empty(); // Limpiar el spinner
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
                    modalAsignaturasList.html(listaAsignaturas); // Aquí va el HTML de la lista
                } else {
                    noAsignaturasMessage.show(); // Mostrar el mensaje si no hay asignaturas
                }

                totalHorasDocenteElement.text(response.total_horas_docente + ' Horas');

            }).fail(function() {
                modalAsignaturasList.html('<li class="list-group-item text-danger">Error al cargar asignaturas.</li>');
            });

            // Cargar períodos académicos para el selector de horario
            const periodoSelect = $('#periodoSelect');
            periodoSelect.empty().append('<option value="">Cargando períodos...</option>'); // Limpiar y poner mensaje de carga
            $.get('/api/periods', function(periods) {
                periodoSelect.empty().append('<option value="">Seleccione un Período</option>');
                periods.forEach(period => {
                    periodoSelect.append(`<option value="${period.id}">${period.nombre}</option>`);
                });
            }).fail(function() {
                periodoSelect.empty().append('<option value="">Error al cargar períodos</option>');
            });

            // Listener para el botón "Mostrar Horario" dentro del modal
            $('#btnCargarHorario').off('click').on('click', function() { // Usar .off().on() para evitar duplicación de eventos
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

        // Script para llenar el modal de edición
        $('#editarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            modal.find('#cedula_editar').val(button.data('cedula'));
            modal.find('#name_editar').val(button.data('name'));
            modal.find('#email_editar').val(button.data('email'));
            modal.find('#telefono_editar').val(button.data('telefono'));
            modal.find('#dedicacion_editar').val(button.data('dedicacion')); // Ojo: Aquí se espera dedicacion_id, no el nombre
            modal.find('#formEditar').attr('action', '/docentes/' + button.data('cedula'));
        });

        // Script para confirmar eliminar (si tienes un modal separado para esto)
        $('#confirmarEliminarModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const docenteCedula = button.data('cedula');
            const form = $(this).find('#formEliminarDocente'); // Asumiendo que tienes un formulario con este ID
            form.attr('action', `/docentes/${docenteCedula}`); // Ajusta la ruta de eliminación
        });
    });
</script>
@endpush
@endsection