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
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input type="text" 
                                class="form-control" 
                                name="codigo_seccion" 
                                id="edit_codigo" 
                                readonly>
                        </div>
                    </div>

                    <!-- Selector de Aula -->
                    <div class="form-group mb-3">
                        <label for="edit_aula_id" class="form-label">Aula</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <select class="form-select @error('aula_id') is-invalid @enderror" 
                                name="aula_id" 
                                id="edit_aula_id" 
                                required>
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id }}">{{ $aula->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('aula_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selector de Carrera -->
                    <div class="form-group mb-3">
                        <label for="edit_carrera_id" class="form-label">Carrera</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                            <select class="form-select @error('carrera_id') is-invalid @enderror" 
                                name="carrera_id" 
                                id="edit_carrera_id" 
                                required>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('carrera_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selectores de Turno y Semestre -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit_turno_id" class="form-label">Turno</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    <select class="form-select @error('turno_id') is-invalid @enderror" 
                                        name="turno_id" 
                                        id="edit_turno_id" 
                                        required>
                                        @foreach($turnos as $turno)
                                            <option value="{{ $turno->id_turno }}">{{ $turno->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('turno_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit_semestre_id" class="form-label">Semestre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <select class="form-select @error('semestre_id') is-invalid @enderror" 
                                        name="semestre_id" 
                                        id="edit_semestre_id" 
                                        required>
                                        <!-- Opciones se actualizarán dinámicamente -->
                                    </select>
                                </div>
                                @error('semestre_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
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
    // Cargar semestres según turno seleccionado
    $('#edit_turno_id').change(function() {
        const turnoId = $(this).val();
        const semestreSelect = $('#edit_semestre_id');
        
        semestreSelect.empty().prop('disabled', true);
        
        if(turnoId) {
            @foreach($semestres as $semestre)
                @if($semestre->numero <= 8)
                    semestreSelect.append('<option value="{{ $semestre->id_semestre }}">{{ $semestre->numero }}</option>');
                @else
                    if(turnoId == 2) { // Nocturno
                        semestreSelect.append('<option value="{{ $semestre->id_semestre }}">{{ $semestre->numero }}</option>');
                    }
                @endif
            @endforeach
            
            semestreSelect.prop('disabled', false);
        }
    });

    // Actualizar semestres al abrir el modal
    $('#editarSeccionModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const turnoId = button.data('turno_id');
        
        // Forzar actualización de semestres
        $('#edit_turno_id').val(turnoId).trigger('change');
        
        // Establecer semestre después de cargar opciones
        setTimeout(() => {
            $('#edit_semestre_id').val(button.data('semestre_id'));
        }, 100);
    });
</script>
@endpush