<div class="modal fade" id="crearSeccionModal" tabindex="-1" aria-labelledby="crearSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="crearSeccionModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Registrar Nueva Sección
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('secciones.store') }}">
                    @csrf

                    <!-- Campo Código de Sección -->
                    <div class="form-group mb-4">
                        <label for="codigo_seccion" class="form-label fw-bold">Código Único</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input id="codigo_seccion" type="text"
                                class="form-control @error('codigo_seccion') is-invalid @enderror"
                                name="codigo_seccion"
                                placeholder="Ej: 01S-2614-D1"
                                value="{{ old('codigo_seccion') }}"
                                required>
                        </div>
                        @error('codigo_seccion')
                            <div class="invalid-feedback d-block small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Selector de Aula -->
                    <div class="form-group mb-4">
                        <label for="aula_id" class="form-label fw-bold">Aula</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-building"></i>
                            </span>
                            <select class="form-select @error('aula_id') is-invalid @enderror"
                                name="aula_id"
                                id="aula_id"
                                required>
                                <option value="">Seleccione un aula</option>
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id }}" {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
                                        {{ $aula->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('aula_id')
                            <div class="invalid-feedback d-block small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Selector de Carrera -->
                    <div class="form-group mb-4">
                        <label for="carrera_id" class="form-label fw-bold">Carrera</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            <select class="form-select @error('carrera_id') is-invalid @enderror" 
                                name="carrera_id" 
                                id="carrera_id" 
                                required>
                                <option value="">Seleccione una carrera</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}" {{ old('carrera_id') == $carrera->carrera_id ? 'selected' : '' }}>
                                        {{ $carrera->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('carrera_id')
                            <div class="invalid-feedback d-block small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Selectores de Turno y Semestre -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="turnoSelect" class="form-label fw-bold">Turno</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <select class="form-select @error('turno_id') is-invalid @enderror"
                                        name="turno_id"
                                        id="turnoSelect"
                                        required>
                                        <option value="">Seleccione un turno</option>
                                        @foreach($turnos as $turno)
                                            <option value="{{ $turno->id_turno }}" {{ old('turno_id') == $turno->id_turno ? 'selected' : '' }}>
                                                {{ $turno->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('turno_id')
                                    <div class="invalid-feedback d-block small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="semestreSelect" class="form-label fw-bold">Semestre</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <select class="form-select @error('semestre_id') is-invalid @enderror"
                                        name="semestre_id"
                                        id="semestreSelect"
                                        required>
                                        <option value="">Primero seleccione un turno</option>
                                    </select>
                                </div>
                                @error('semestre_id')
                                    <div class="invalid-feedback d-block small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Registrar Sección
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Cargar semestres según turno seleccionado
        $('#turnoSelect').change(function() {
            const turnoId = $(this).val();
            const semestreSelect = $('#semestreSelect');
            
            semestreSelect.empty().prop('disabled', true);

            if(turnoId) {
                // Agregar opción por defecto
                semestreSelect.append('<option value="">Seleccione un semestre</option>');

                // Filtrar semestres
                @foreach($semestres as $semestre)
                    @if($semestre->numero <= 8)
                        semestreSelect.append(
                            '<option value="{{ $semestre->id_semestre }}" {{ old("semestre_id") == "'.$semestre->id_semestre.'" ? "selected" : "" }}>{{ $semestre->numero }}</option>'
                        );
                    @else
                        if(turnoId == 2) { // Nocturno
                            semestreSelect.append(
                                '<option value="{{ $semestre->id_semestre }}" {{ old("semestre_id") == "'.$semestre->id_semestre.'" ? "selected" : "" }}>{{ $semestre->numero }}</option>'
                            );
                        }
                    @endif
                @endforeach

                semestreSelect.prop('disabled', false);
            }
        }).trigger('change');
    });
</script>
@endpush