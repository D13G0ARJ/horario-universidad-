<div class="modal fade" id="editarSeccionModal" tabindex="-1" aria-labelledby="editarSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Editar Sección
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formEditarSeccion">
                    @csrf
                    @method('PUT')

                    <!-- Código de Sección (readonly) -->
                    <div class="form-group mb-3">
                        <label for="edit_codigo" class="form-label">Código</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-barcode"></i></span>
                            <input type="text" 
                                class="form-control" 
                                name="codigo_seccion" 
                                id="edit_codigo" 
                                readonly>
                        </div>
                    </div>

                    <!-- Selector de Carrera (Corregido) -->
                    <div class="form-group mb-3">
                        <label for="edit_carrera_id" class="form-label">Carrera</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-graduation-cap"></i></span>
                            <select class="form-select" 
                                name="carrera_id" 
                                id="edit_carrera_id" 
                                required>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}">{{ $carrera->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Selectores de Turno y Semestre -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit_turno_id" class="form-label">Turno</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-clock"></i></span>
                                    <select class="form-select" 
                                        name="turno_id" 
                                        id="edit_turno_id" 
                                        required>
                                        @foreach($turnos as $turno)
                                            <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit_semestre_id" class="form-label">Semestre</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-calendar-alt"></i></span>
                                    <select class="form-select" 
                                        name="semestre_id" 
                                        id="edit_semestre_id" 
                                        required>
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Precargar semestres agrupados por turno
    const semestresPorTurno = @json($turnos->mapWithKeys(function($turno) {
        return [$turno->id_turno => $turno->semestres];
    }));

    function actualizarSemestres(turnoId, semestreId = null) {
        const semestreSelect = $('#edit_semestre_id');
        semestreSelect.empty().prop('disabled', true);
        
        if (semestresPorTurno[turnoId]) {
            semestresPorTurno[turnoId].forEach(semestre => {
                const selected = semestre.id_semestre == semestreId ? 'selected' : '';
                semestreSelect.append(
                    `<option value="${semestre.id_semestre}" ${selected}>Semestre ${semestre.numero}</option>`
                );
            });
            semestreSelect.prop('disabled', false);
        }
    }

    // Evento al mostrar el modal
    $('#editarSeccionModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const modal = $(this);
        
        // Obtener datos
        const codigo = button.data('codigo');
        const carreraId = button.data('carrera-id');
        const turnoId = button.data('turno-id');
        const semestreId = button.data('semestre-id');

        // Actualizar formulario
        modal.find('#edit_codigo').val(codigo);
        modal.find('#edit_carrera_id').val(carreraId);
        modal.find('#edit_turno_id').val(turnoId);
        
        // Cargar semestres
        actualizarSemestres(turnoId, semestreId);
    });

    // Evento cambio de turno
    $('#edit_turno_id').on('change', function() {
        actualizarSemestres(this.value);
    });
</script>
@endpush