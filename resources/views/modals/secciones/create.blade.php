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
                        <label for="codigo_seccion" class="form-label fw-bold">Código Único <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input id="codigo_seccion" type="text"
                                class="form-control @error('codigo_seccion') is-invalid @enderror"
                                name="codigo_seccion"
                                placeholder="Ej: 01S-2614-D1"
                                value="{{ old('codigo_seccion') }}"
                                required
                                autofocus>
                        </div>
                        @error('codigo_seccion')
                            <div class="invalid-feedback d-block small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Selector de Aula -->
                    <div class="form-group mb-4">
                        <label for="aula_id" class="form-label fw-bold">Aula <span class="text-danger">*</span></label>
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
                                    <option value="{{ $aula->id }}" 
                                        {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
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

                    <!-- Selector de Carrera (Corregido) -->
                    <div class="form-group mb-4">
                        <label for="carrera_id" class="form-label fw-bold">Carrera <span class="text-danger">*</span></label>
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
                                    <option value="{{ $carrera->carrera_id }}" {{-- Usar carrera_id --}}
                                        {{ old('carrera_id') == $carrera->carrera_id ? 'selected' : '' }}>
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
                                <label for="turno_id" class="form-label fw-bold">Turno <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <select class="form-select @error('turno_id') is-invalid @enderror"
                                        name="turno_id"
                                        id="turno_id"
                                        required>
                                        <option value="">Seleccione un turno</option>
                                        @foreach($turnos as $turno)
                                            <option value="{{ $turno->id_turno }}" 
                                                {{ old('turno_id') == $turno->id_turno ? 'selected' : '' }}>
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
                                <label for="semestre_id" class="form-label fw-bold">Semestre <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <select class="form-select @error('semestre_id') is-invalid @enderror"
                                        name="semestre_id"
                                        id="semestre_id"
                                        required
                                        {{ old('turno_id') ? '' : 'disabled' }}>
                                        <option value="">Seleccione un semestre</option>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Precargar semestres
        const semestresPorTurno = @json($turnos->mapWithKeys(function($turno) {
            return [$turno->id_turno => $turno->semestres];
        }));

        const turnoSelect = document.getElementById('turno_id');
        const semestreSelect = document.getElementById('semestre_id');

        // Función para cargar semestres
        function cargarSemestres(turnoId) {
            semestreSelect.innerHTML = '<option value="">Seleccione un semestre</option>';
            
            if (semestresPorTurno[turnoId]) {
                semestresPorTurno[turnoId].forEach(semestre => {
                    const option = new Option(`Semestre ${semestre.numero}`, semestre.id_semestre);
                    semestreSelect.appendChild(option);
                });
                semestreSelect.disabled = false;
                semestreSelect.required = true;
            } else {
                semestreSelect.innerHTML = '<option value="">No hay semestres</option>';
                semestreSelect.disabled = true;
            }
        }

        // Evento cambio de turno
        turnoSelect.addEventListener('change', function() {
            if (this.value) {
                cargarSemestres(this.value);
            } else {
                semestreSelect.innerHTML = '<option value="">Seleccione un turno primero</option>';
                semestreSelect.disabled = true;
            }
        });

        // Cargar semestres si hay valor antiguo
        @if(old('turno_id'))
            cargarSemestres({{ old('turno_id') }});
            semestreSelect.value = "{{ old('semestre_id') }}";
        @endif
    });
</script>
@endpush