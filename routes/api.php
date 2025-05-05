<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/semestres-por-turno/{turnoId}', function($turnoId) {
    $semestres = DB::table('semestres')
        ->where('turno_id', $turnoId)
        ->orderBy('numero')
        ->get(['id_semestre as id', 'numero']);
    
    return response()->json($semestres);
});

Route::get('/docentes/{id}/asignaturas', [DocenteController::class, 'getAsignaturasByDocente']);
