<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="registroModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Registrar Nueva Carrera
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('carrera.store') }}">
                    @csrf
                    
                    <!-- Campo Código -->
                    <div class="form-group mb-4">
                        <label for="carrera_id" class="form-label fw-bold">Código de Carrera</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text" 
                                class="form-control @error('carrera_id') is-invalid @enderror" 
                                id="carrera_id" 
                                name="carrera_id" 
                                placeholder="Ej: ING-SIST-2024" 
                                value="{{ old('carrera_id') }}" 
                                required 
                                autofocus>
                        </div>
                        @error('carrera_id')
                            <div class="invalid-feedback d-block small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Campo Nombre -->
                    <div class="form-group mb-4">
                        <label for="name" class="form-label fw-bold">Nombre de la Carrera</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            <input type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                placeholder="Ej: Ingeniería de Sistemas" 
                                value="{{ old('name') }}" 
                                required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Registrar Carrera
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>