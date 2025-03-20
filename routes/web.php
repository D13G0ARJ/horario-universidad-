<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web de la aplicación. Estas rutas son cargadas
| por el RouteServiceProvider y todas estarán dentro del grupo "web".
|
*/

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

// Ruta principal (administrador)
Route::get('/', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');

// Rutas para el módulo de coordinadores
Route::prefix('coordinador')->middleware('auth')->group(function () {
    Route::get('/', [CoordinadorController::class, 'index'])->name('coordinador.index');
    Route::post('/', [CoordinadorController::class, 'store'])->name('coordinador.store');
    Route::put('/{usuario}', [CoordinadorController::class, 'update'])->name('coordinador.update');
    Route::delete('/{usuario}', [CoordinadorController::class, 'destroy'])->name('coordinador.destroy');
});

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
