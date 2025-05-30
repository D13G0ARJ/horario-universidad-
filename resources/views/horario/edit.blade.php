<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Horario</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; overflow: auto; display: flex; flex-direction: column; }
        .bloque-horario {
            position: absolute; top: 0; left: 0; width: 100%; z-index: 10;
            padding: 3px 5px; font-size: 0.7rem; color: white; overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1); display: flex; flex-direction: column;
            justify-content: space-between; margin: 2px; border-radius: 4px;
        }
        .bg-asignatura-1 { background: linear-gradient(135deg, #4e73df, #3a56c8); }
        .bg-asignatura-2 { background: linear-gradient(135deg, #1cc88a, #17a673); }
        .bg-asignatura-3 { background: linear-gradient(135deg, #36b9cc, #2a96a5); }
        .bg-asignatura-4 { background: linear-gradient(135deg, #f6c23e, #e0b12d); }
        .bg-asignatura-5 { background: linear-gradient(135deg, #e74a3b, #d62c1a); }
        .bg-asignatura-6 { background: linear-gradient(135deg, #858796, #6c6e7e); }
        .bg-asignatura-7 { background: linear-gradient(135deg, #5a5c69, #484a58); }
        #horarioTable thead th, #horarioTable tbody td, #horarioTable tbody th {
            vertical-align: top; padding: 4px; height: 40px; box-sizing: border-box; position: relative;
        }
        #horarioTable tbody { display: block; overflow-y: auto; }
        #horarioTable thead, #horarioTable tbody tr { display: table; width: 100%; table-layout: fixed; }
        .time-slot { background-color: #f8f9fa; position: sticky; left: 0; z-index: 1; width: 80px; }
        .bloque-contenido { display: flex; flex-direction: column; justify-content: space-between; height: 100%; overflow: hidden; }
        .bloque-contenido .asignatura-nombre { font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.75rem; }
        .bloque-contenido .asignatura-details { font-size: 0.65rem; line-height: 1.2; }
        .asignatura-item.dragging { opacity: 0.5; transform: scale(0.98); }
        .drop-zone.hover-cell { background-color: rgba(0, 123, 255, 0.1) !important; border: 2px dashed #007bff !important; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 text-primary">
                    <i class="fas fa-calendar-alt mr-2"></i>Editar Horario
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('horario.index') }}">Horarios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white py-3">
                <h2 class="h5 mb-0">
                    <i class="fas fa-calendar-alt mr-2"></i>Configuración del Horario
                </h2>
            </div>
            <form action="{{ route('horario.update', $horario->id) }}" method="POST" id="horarioForm">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="row g-3 mb-4 bg-light p-3 rounded">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label class="form-label fw-bold">Periodo</label>
                            <input type="text" class="form-control" value="{{ $horario->periodo->nombre ?? 'N/A' }}" readonly>
                            <input type="hidden" name="periodo_id" value="{{ $horario->periodo_id }}">
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label class="form-label fw-bold">Carrera</label>
                            <input type="text" class="form-control" value="{{ $horario->carrera->name ?? 'N/A' }}" readonly>
                            <input type="hidden" name="carrera_id" value="{{ $horario->carrera_id }}">
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label class="form-label fw-bold">Turno</label>
                            <input type="text" class="form-control" value="{{ $horario->turno->nombre ?? 'N/A' }}" readonly>
                            <input type="hidden" name="turno_id" value="{{ $horario->turno_id }}">
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label class="form-label fw-bold">Semestre</label>
                            <input type="text" class="form-control" value="{{ $horario->semestre->numero ?? 'N/A' }}°" readonly>
                            <input type="hidden" name="semestre_id" value="{{ $horario->semestre_id }}">
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label class="form-label fw-bold">Sección</label>
                            <input type="text" class="form-control" value="{{ $horario->seccion->codigo_seccion ?? 'N/A' }}" readonly>
                            <input type="hidden" name="seccion_id" value="{{ $horario->seccion_id }}">
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <label class="form-label fw-bold">Coordinador</label>
                            <input type="text" class="form-control" value="{{ $horario->coordinador->name ?? 'N/A' }}" readonly>
                            <input type="hidden" name="coordinador_cedula" value="{{ $horario->coordinador_cedula }}">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3 d-flex flex-column">
                            <div class="card flex-grow-1 border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Asignaturas Disponibles</h5>
                                </div>
                                <div class="card-body p-2 overflow-auto" id="asignaturasContainer">
                                    <div class="list-group" id="listaAsignaturas">
                                        @if(count($asignaturas) > 0)
                                            @foreach($asignaturas as $index => $asignatura)
                                                @php $colorClass = 'bg-asignatura-'.(($index % 7) + 1); @endphp
                                                <div class="list-group-item asignatura-item {{ $colorClass }} text-white mb-2 py-2 px-3"
                                                     draggable="true"
                                                     data-asignatura-id="{{ $asignatura->asignatura_id }}"
                                                     data-asignatura-name="{{ $asignatura->name }}"
                                                     data-docentes='@json($asignatura->docentes)'
                                                     data-carga-horaria='@json($asignatura->carga_horaria)'>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span style="font-size: 0.8rem;">{{ $asignatura->name }}
                                                            <small>(
                                                                @if($asignatura->carga_horaria && count($asignatura->carga_horaria) > 0)
                                                                    @foreach($asignatura->carga_horaria as $carga)
                                                                        {{ substr($carga->tipo,0,1) }}:{{ $carga->horas_academicas }}b{{ !$loop->last ? ',' : '' }}
                                                                    @endforeach
                                                                @else
                                                                    No definida
                                                                @endif
                                                            )</small>
                                                        </span>
                                                        <i class="fas fa-arrows-alt ms-2"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle me-2"></i>No hay asignaturas.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 d-flex flex-column">
                            <div class="card flex-grow-1 border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Horario - Lunes a Sábado</h5>
                                </div>
                                <div class="card-body p-0 d-flex flex-column">
                                    <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                                        <table class="table table-bordered table-hover mb-0" id="horarioTable">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="text-center" style="width: 10%">Hora</th>
                                                    <th class="text-center" style="width: 15%">Lunes</th>
                                                    <th class="text-center" style="width: 15%">Martes</th>
                                                    <th class="text-center" style="width: 15%">Miércoles</th>
                                                    <th class="text-center" style="width: 15%">Jueves</th>
                                                    <th class="text-center" style="width: 15%">Viernes</th>
                                                    <th class="text-center" style="width: 15%">Sábado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="horarioBody">
                                                <!-- Bloques cargados y nuevos se renderizan por JS -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-save me-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    const asignaturas = @json($asignaturas);
    const bloquesGuardados = @json($bloques);

document.addEventListener('DOMContentLoaded', function() {
    // Render asignaturas disponibles (igual que en crear)
    const listaAsignaturas = document.getElementById('listaAsignaturas');
    if (asignaturas.length > 0) {
        listaAsignaturas.innerHTML = asignaturas.map((asignatura, index) => {
            const colorClass = `bg-asignatura-${(index % 7) + 1}`;
            let cargaHorariaText = 'No definida';
            if (asignatura.carga_horaria && asignatura.carga_horaria.length > 0) {
                cargaHorariaText = asignatura.carga_horaria.map(c => `${c.tipo.substring(0,1)}:${c.horas_academicas}b`).join(', ');
            }
            return `
                <div class="list-group-item asignatura-item ${colorClass} text-white mb-2 py-2 px-3" 
                     draggable="true" 
                     data-asignatura-id="${asignatura.asignatura_id}"
                     data-asignatura-name="${asignatura.name}"
                     data-docentes='${JSON.stringify(asignatura.docentes)}'
                     data-carga-horaria='${JSON.stringify(asignatura.carga_horaria)}'>
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size: 0.8rem;">${asignatura.name} <small>(${cargaHorariaText})</small></span>
                        <i class="fas fa-arrows-alt ms-2"></i>
                    </div>
                </div>
            `;
        }).join('');
    } else {
        listaAsignaturas.innerHTML = `<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle me-2"></i>No hay asignaturas.</div>`;
    }

    // Drag and drop para asignaturas (igual que en crear)
    document.querySelectorAll('.asignatura-item').forEach(item => {
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', JSON.stringify({
                asignatura_id: this.dataset.asignaturaId,
                name: this.dataset.asignaturaName,
                docentes: JSON.parse(this.dataset.docentes),
                cargaHoraria: JSON.parse(this.dataset.cargaHoraria),
                colorClass: Array.from(this.classList).find(cls => cls.startsWith('bg-asignatura-'))
            }));
            this.classList.add('dragging');
        });
        item.addEventListener('dragend', function() { this.classList.remove('dragging'); });
    });

    // Render bloques guardados en la tabla
    const horarioBody = document.getElementById('horarioBody');
    const horaInicio = 7, horaFin = 21;
    for (let h = horaInicio; h <= horaFin; h++) {
        let horaFormato = `${h.toString().padStart(2, '0')}:00`;
        let fila = document.createElement('tr');
        fila.innerHTML = `<th class="time-slot">${horaFormato}</th>` +
            [1,2,3,4,5,6].map(dia => `<td class="drop-zone" data-hora="${horaFormato}" data-dia="${dia}" style="height: 40px; position: relative;"></td>`).join('');
        horarioBody.appendChild(fila);
        if (h < horaFin) {
            horaFormato = `${h.toString().padStart(2, '0')}:45`;
            fila = document.createElement('tr');
            fila.innerHTML = `<th class="time-slot">${horaFormato}</th>` +
                [1,2,3,4,5,6].map(dia => `<td class="drop-zone" data-hora="${horaFormato}" data-dia="${dia}" style="height: 40px; position: relative;"></td>`).join('');
            horarioBody.appendChild(fila);
        }
    }

    // Drag and drop para celdas del horario
    document.querySelectorAll('.drop-zone').forEach(celda => {
        celda.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('hover-cell'); });
        celda.addEventListener('dragleave', function() { this.classList.remove('hover-cell'); });
        celda.addEventListener('drop', handleDrop);
    });

    // Renderizar bloques guardados
    bloquesGuardados.forEach(function(bloque, idx) {
        const filas = document.querySelectorAll('#horarioBody tr');
        let filaInicio = null;
        filas.forEach((fila) => {
            const horaFila = fila.querySelector('.time-slot').textContent.trim();
            if (horaFila === bloque.hora_inicio) filaInicio = fila;
        });
        if (filaInicio) {
            const celda = filaInicio.querySelector(`td[data-dia="${bloque.dia_semana}"]`);
            if (celda) {
                const colorClass = `bg-asignatura-${(idx % 7) + 1}`;
                const asignaturaName = bloque.asignatura ? bloque.asignatura.name : '';
                const docenteName = bloque.docente ? bloque.docente.name : '';
                const horaFin = calcularHoraFin(bloque.hora_inicio, bloque.bloques);
                const bloqueDiv = document.createElement('div');
                bloqueDiv.classList.add('bloque-horario', colorClass);
                bloqueDiv.style.height = `${bloque.bloques * 40}px`;
                bloqueDiv.innerHTML = `
                    <button type="button" class="btn btn-danger btn-xs btn-bloque-editar" title="Eliminar bloque" onclick="eliminarBloque(${bloque.id})">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="bloque-contenido">
                        <div class="asignatura-nombre" title="${asignaturaName}">${asignaturaName}</div>
                        <div class="asignatura-details">
                            <div title="${bloque.tipo_horas} - ${bloque.bloques} bloques">${bloque.tipo_horas} (${bloque.bloques}b)</div>
                            <div title="Docente">Doc: ${docenteName}</div>
                            <div>${bloque.hora_inicio} - ${horaFin}</div>
                        </div>
                    </div>
                    <input type="hidden" name="bloques[${bloque.id}][id]" value="${bloque.id}">
                    <input type="hidden" name="bloques[${bloque.id}][asignatura_id]" value="${bloque.asignatura_id}">
                    <input type="hidden" name="bloques[${bloque.id}][dia]" value="${bloque.dia_semana}">
                    <input type="hidden" name="bloques[${bloque.id}][hora_inicio]" value="${bloque.hora_inicio}">
                    <input type="hidden" name="bloques[${bloque.id}][bloques]" value="${bloque.bloques}">
                    <input type="hidden" name="bloques[${bloque.id}][tipo_horas]" value="${bloque.tipo_horas}">
                    <input type="hidden" name="bloques[${bloque.id}][docente_id]" value="${bloque.docente_id}">
                `;
                celda.appendChild(bloqueDiv);
            }
        }
    });
});

function handleDrop(e) {
    e.preventDefault();
    this.classList.remove('hover-cell');
    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
    const { asignatura_id, name: asignaturaName, docentes: docentesData, cargaHoraria: cargaHorariaData, colorClass } = data;
    const dia = this.dataset.dia;
    const horaInicio = this.dataset.hora;
    // Por defecto 1 bloque, tipo y docente el primero disponible
    let tipo = (cargaHorariaData && cargaHorariaData.length > 0) ? cargaHorariaData[0].tipo : 'Clase';
    let docenteId = (docentesData && docentesData.length > 0) ? docentesData[0].cedula_doc : '';
    let bloques = 1;
    const horaFin = calcularHoraFin(horaInicio, bloques);
    // Visualmente igual que en crear
    const bloqueDiv = document.createElement('div');
    bloqueDiv.classList.add('bloque-horario', colorClass);
    bloqueDiv.style.height = `${bloques * 40}px`;
    bloqueDiv.innerHTML = `
        <button type="button" class="btn btn-danger btn-xs btn-bloque-editar" title="Eliminar bloque" onclick="this.closest('.bloque-horario').remove()">
            <i class="fas fa-times"></i>
        </button>
        <div class="bloque-contenido">
            <div class="asignatura-nombre" title="${asignaturaName}">${asignaturaName}</div>
            <div class="asignatura-details">
                <div title="${tipo} - ${bloques} bloques">${tipo} (${bloques}b)</div>
                <div title="Docente">Doc: ${docenteId}</div>
                <div>${horaInicio} - ${horaFin}</div>
            </div>
        </div>
        <input type="hidden" name="bloques_nuevos[][asignatura_id]" value="${asignatura_id}">
        <input type="hidden" name="bloques_nuevos[][dia]" value="${dia}">
        <input type="hidden" name="bloques_nuevos[][hora_inicio]" value="${horaInicio}">
        <input type="hidden" name="bloques_nuevos[][bloques]" value="${bloques}">
        <input type="hidden" name="bloques_nuevos[][tipo_horas]" value="${tipo}">
        <input type="hidden" name="bloques_nuevos[][docente_id]" value="${docenteId}">
    `;
    this.appendChild(bloqueDiv);
}

function calcularHoraFin(horaInicio, bloques) {
    const [h, m] = horaInicio.split(':').map(Number);
    let totalMinutosFin = (h * 60 + m) + (bloques * 45);
    let horasFin = Math.floor(totalMinutosFin / 60) % 24; 
    let minutosFin = totalMinutosFin % 60;
    return `${String(horasFin).padStart(2, '0')}:${String(minutosFin).padStart(2, '0')}`;
}
function eliminarBloque(bloqueId) {
    const bloque = document.querySelector(`input[name='bloques[${bloqueId}][id]']`)?.closest('.bloque-horario');
    if (bloque) bloque.remove();
    const form = document.querySelector('form');
    if (form) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = `eliminar_bloques[]`;
        input.value = bloqueId;
        form.appendChild(input);
    }
}
    </script>
</body>
</html>
