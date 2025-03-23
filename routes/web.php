<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::fallback(function () { return redirect('/login'); });


Route::get('/', function () {return view('admin');});

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//rotas del administrador
route::get('/', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');

// Rutas para coordinadores
Route::get('/coordinador', [CoordinadorController::class, 'index'])->name('coordinador.index')->middleware('auth');
Route::post('/coordinadores', [CoordinadorController::class, 'store'])->name('coordinador.store');
Route::put('/coordinadores/{usuario}', [CoordinadorController::class, 'update'])->name('coordinador.update');
Route::delete('/coordinadores/{usuario}', [CoordinadorController::class, 'destroy'])->name('coordinador.destroy');

// Rutas para carreras
Route::get('/carrera', [CarreraController::class, 'index'])->name('carrera.index')->middleware('auth');
Route::post('/carreras', [CarreraController::class,'store'])->name('carrera.store');
Route::put('/carreras/{carrera}', [CarreraController::class, 'update'])->name('carrera.update');
Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroy'])->name('carrera.destroy');

// Rutas para asignaturas
Route::get('/asignatura', [AsignaturaController::class, 'index'])->name('asignatura.index')->middleware('auth');
Route::post('/asignaturas', [AsignaturaController::class, 'store'])->name('asignatura.store');
Route::put('/asignaturas/{asignatura}', [AsignaturaController::class, 'update'])->name('asignatura.update');
Route::delete('/asignaturas/{asignatura}', [AsignaturaController::class, 'destroy'])->name('asignatura.destroy');

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

use App\Http\Controllers\Auth\SecurityQuestionController;

// Ruta para actualizar las preguntas de seguridad
Route::post('/security-questions', [SecurityQuestionController::class, 'update'])->name('security-questions.update');
