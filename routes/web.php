<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\SecurityQuestionController;
use App\Http\Controllers\RespaldoController;
use App\Http\Controllers\HorarioController;

// Deshabilitar ciertas rutas de autenticación predeterminadas
Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
    'confirm' => false,
]);

// Redirigir a la página de inicio de sesión si no se encuentra una ruta
Route::fallback(function () {
    return redirect('/login');
});

// Rutas del administrador
Route::get('/', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');

// Rutas para coordinadores
Route::get('/coordinador', [CoordinadorController::class, 'index'])->name('coordinador.index')->middleware('auth');
Route::post('/coordinadores', [CoordinadorController::class, 'store'])->name('coordinador.store');
Route::put('/coordinadores/{usuario}', [CoordinadorController::class, 'update'])->name('coordinador.update');
Route::delete('/coordinadores/{usuario}', [CoordinadorController::class, 'destroy'])->name('coordinador.destroy');

// Rutas para carreras
Route::get('/carrera', [CarreraController::class, 'index'])->name('carrera.index')->middleware('auth');
Route::post('/carreras', [CarreraController::class, 'store'])->name('carrera.store');
Route::put('/carreras/{carrera}', [CarreraController::class, 'update'])->name('carrera.update');
Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroy'])->name('carrera.destroy');

// Rutas para asignaturas
Route::get('/asignatura', [AsignaturaController::class, 'index'])->name('asignatura.index')->middleware('auth');
Route::get('/asignatura/filtrar', [AsignaturaController::class, 'filtrar'])->name('asignatura.filtrar');
Route::post('/asignatura', [AsignaturaController::class, 'store'])->name('asignatura.store');
Route::put('/asignaturas/{asignatura}', [AsignaturaController::class, 'update'])->name('asignatura.update');
Route::delete('/asignaturas/{asignatura}', [AsignaturaController::class, 'destroy'])->name('asignatura.destroy');


// Rutas para docentes
Route::get('/docente', [DocenteController::class, 'index'])->name('docente.index')->middleware('auth');
Route::post('/docentes', [DocenteController::class, 'store'])->name('docente.store');
Route::put('/docentes/{docente}', [DocenteController::class, 'update'])->name('docente.update');
Route::delete('/docentes/{docente}', [DocenteController::class, 'destroy'])->name('docente.destroy');

// Rutas adicionales para activación/desactivación
Route::patch('/docentes/{docente}/deactivate', [DocenteController::class, 'deactivate'])->name('docente.deactivate');
Route::patch('/docentes/{docente}/activate', [DocenteController::class, 'activate'])->name('docente.activate');

// Nueva ruta para cargar docentes filtrados vía AJAX
Route::get('/api/docentes-by-status', [DocenteController::class, 'getDocentesByStatus'])->name('api.docentes.by.status');

// Nueva ruta para mostrar el horario consolidado del docente para un período específico
Route::get('/docentes/periods', [DocenteController::class, 'getPeriods']);
Route::get('/docentes/{docente}/horario/{periodo}', [DocenteController::class, 'showDocenteHorario'])
    ->name('docentes.horario');



// Rutas para períodos
Route::get('/periodo', [PeriodoController::class, 'index'])->name('periodo.index')->middleware('auth');
Route::post('/periodos', [PeriodoController::class, 'store'])->name('periodo.store');
Route::put('/periodos/{periodo}', [PeriodoController::class, 'update'])->name('periodo.update');
Route::delete('/periodos/{periodo}', [PeriodoController::class, 'destroy'])->name('periodo.destroy');


// Rutas para la gestión de horarios (CRUD y dependientes)
Route::prefix('horario')->middleware('auth')->group(function () {
    // Listar horarios
    Route::get('/', [HorarioController::class, 'index'])->name('horario.index');
    // Formulario de creación
    Route::get('/create', [HorarioController::class, 'create'])->name('horario.create');
    // Rutas API para dependientes y filtros

    Route::get('/api/semestres-por-turno/{turnoId}', [HorarioController::class, 'getSemestresPorTurno'])->name('api.semestres.por.turno');
    Route::get('/obtener-secciones', [HorarioController::class, 'obtenerSecciones'])->name('api.obtener.secciones');
    Route::get('/asignaturas', [HorarioController::class, 'getAsignaturasFiltradas'])->name('horario.asignaturas.filtradas');

    // Guardar nuevo horario
    Route::post('/', [HorarioController::class, 'store'])->name('horario.store');
    // Mostrar detalle de horario
    Route::get('/{horario}', [HorarioController::class, 'show'])->where('horario', '[0-9]+')->name('horario.show');
    // Formulario de edición de horario
    Route::get('/{horario}/edit', [HorarioController::class, 'edit'])->where('horario', '[0-9]+')->name('horario.edit');
    // Actualizar horario
    Route::put('/{horario}', [HorarioController::class, 'update'])->where('horario', '[0-9]+')->name('horario.update');
    // Eliminar horario (debe ser /horario/{horario}, no /horarios/{horario})
    Route::delete('/{horario}', [HorarioController::class, 'destroy'])->where('horario', '[0-9]+')->name('horario.destroy');

});

// Rutas para secciones
Route::resource('/secciones', SeccionController::class);
// web.php
Route::get('/semestres-por-turno/{turno}', [SeccionController::class, 'semestresPorTurno']);

// Rutas para recuperación de contraseña
Route::prefix('password')->group(function () {
    // Verificar usuario
    Route::get('/verify-user', [ForgotPasswordController::class, 'showVerifyUserForm'])->name('password.verifyUserForm');
    Route::post('/verify-user', [ForgotPasswordController::class, 'verifyUser'])->name('password.verifyUser');

    // Preguntas de seguridad
    Route::get('/security-questions', function () {
        return view('auth.passwords.security-questions', [
            'username' => session('username'),
            'question1' => session('question1'),
            'question2' => session('question2'),
        ]);
    })->name('password.securityQuestions');

    Route::post('/verify-answers', [ForgotPasswordController::class, 'verifyAnswers'])->name('password.verifyAnswers');

    // Restablecer contraseña
    Route::get('/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/update', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');
});

// Ruta para actualizar las preguntas de seguridad
Route::post('/security-questions', [SecurityQuestionController::class, 'update'])->name('security-questions.update');

// Rutas para bitácora
Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index')->middleware('auth');
Route::get('/bitacora/filtrar', [BitacoraController::class, 'filtrar'])->name('bitacora.filtrar');

// Rutas para respaldos
Route::prefix('respaldo')->middleware(['auth'])->group(function () {
    // Ruta para mostrar la vista de respaldos
    Route::get('/', [RespaldoController::class, 'index'])->name('respaldo.index');

    // Ruta para generar un respaldo
    Route::post('/store', [RespaldoController::class, 'store'])->name('respaldo.store');

    // Ruta para restaurar un respaldo específico
    Route::post('/restore/{id}', [RespaldoController::class, 'restore'])->name('respaldo.restore');

    // Ruta para eliminar un respaldo específico
    Route::delete('/delete/{id}', [RespaldoController::class, 'destroy'])->name('respaldo.destroy');
});

