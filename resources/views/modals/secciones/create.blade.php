<div class="modal fade" id="crearSeccionModal" tabindex="-1" aria-labelledby="crearSeccionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="crearSeccionModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Registrar Nueva Sección
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('secciones.store') }}">
                    @csrf
                    
                    <!-- Campo Código de Sección -->
                    <div class="form-group mb-3">
                        <label for="codigo_seccion" class="form-label">Código Único</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input id="codigo_seccion" type="text" 
                                class="form-control @error('codigo_seccion') is-invalid @enderror"
                                name="codigo_seccion" 
                                placeholder="Ej: 01S-2614-D1"
                                value="{{ old('codigo_seccion') }}" 
                                required>
                        </div>
                        @error('codigo_seccion')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selector de Aula -->
                    <div class="form-group mb-3">
                        <label for="aula_id" class="form-label">Aula</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
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
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selector de Carrera -->
                    <div class="form-group mb-3">
                        <label for="carrera_id" class="form-label">Carrera</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                            <select class="form-select @error('carrera_id') is-invalid @enderror" 
                                name="carrera_id" 
                                id="carrera_id" 
                                required>
                                <option value="">Seleccione una carrera</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                        {{ $carrera->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('carrera_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Selector de Turno -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="turno_id" class="form-label">Turno</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
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
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Selector de Semestre (Dinámico) -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="semestre_id" class="form-label">Semestre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <select class="form-select @error('semestre_id') is-invalid @enderror" 
                                        name="semestre_id" 
                                        id="semestreSelect" 
                                        required>
                                        <option value="">Primero seleccione un turno</option>
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
                            <i class="fas fa-save mr-2"></i>Registrar Sección
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
                @foreach($semestres as $semestre)
                    @if($semestre->numero <= 8)
                        semestreSelect.append(
                            '<option value="{{ $semestre->id_semestre }}">{{ $semestre->numero }}</option>'
                        );
                    @else
                        if(turnoId == 2) {
                            semestreSelect.append(
                                '<option value="{{ $semestre->id_semestre }}">{{ $semestre->numero }}</option>'
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