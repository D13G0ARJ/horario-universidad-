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

// Rutas para períodos
Route::get('/periodo', [PeriodoController::class, 'index'])->name('periodo.index')->middleware('auth');
Route::post('/periodos', [PeriodoController::class, 'store'])->name('periodo.store');
Route::put('/periodos/{periodo}', [PeriodoController::class, 'update'])->name('periodo.update');
Route::delete('/periodos/{periodo}', [PeriodoController::class, 'destroy'])->name('periodo.destroy');


// Rutas para la gestión de horarios
Route::middleware(['auth'])->group(function () {
    Route::get('/horarios', [HorarioController::class, 'index'])->name('horario.index'); // Mostrar el calendario
    Route::post('/horarios', [HorarioController::class, 'store'])->name('horario.store'); // Crear un nuevo horario
    Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('horario.update'); // Actualizar un horario
    Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy'])->name('horario.destroy'); // Eliminar un horario
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


// Rutas para horarios
Route::prefix('horario')->middleware('auth')->group(function () {

    // Ruta principal para listar horarios: /horario
    Route::get('/', [HorarioController::class, 'index'])->name('horario.index');

    // Ruta para mostrar el formulario de creación de horario: /horario/create
    Route::get('/create', [HorarioController::class, 'create'])->name('horario.create');

    // Ruta para almacenar un nuevo horario (POST): /horario
    Route::post('/', [HorarioController::class, 'store'])->name('horario.store');

    // Ruta para eliminar un horario específico (DELETE): /horario/{id}
    Route::delete('/{id}', [HorarioController::class, 'destroy'])->name('horario.destroy');

    // Rutas AJAX para filtros dependientes:
    // 1. Obtener semestres por turno: /horario/api/semestres-por-turno/{turnoId}
    //    (Esta ruta se usa en el JS para cargar el select de semestres)
    Route::get('/api/semestres-por-turno/{turnoId}', [HorarioController::class, 'getSemestresPorTurno'])->name('api.semestres.por.turno');

    // 2. Obtener secciones filtradas por carrera, semestre y turno: /horario/obtener-secciones
    //    (Esta ruta se usa en el JS para cargar el select de secciones)
    Route::get('/obtener-secciones', [HorarioController::class, 'obtenerSecciones'])->name('api.obtener.secciones');

    // 3. Obtener asignaturas filtradas por sección y otros parámetros: /horario/asignaturas
    //    Esta es la ruta CRÍTICA para tu problema de filtrado.
    //    Recibe todos los filtros como query parameters (e.g., ?seccion_id=X&carrera_id=Y...)
    Route::get('/asignaturas', [HorarioController::class, 'getAsignaturasFiltradas'])->name('horario.asignaturas.filtradas');

});

