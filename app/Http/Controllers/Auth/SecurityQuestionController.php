<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SecurityQuestionController extends Controller
{
    public function showVerifyUserForm()
    {
        return view('auth.passwords.verify-user');
    }

    public function verifyUser(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
        ]);

        $user = User::where('username', $request->username)->first();

        // Redirigir a la vista de preguntas de seguridad con las preguntas del usuario
        return view('auth.passwords.security-questions', [
            'question1' => $user->security_question_1,
            'question2' => $user->security_question_2,
            'username' => $user->username,
        ]);
    }

    public function verifyAnswers(Request $request)
{
    $request->validate([
        'username' => 'required|exists:users,username',
        'security_answer_1' => 'required',
        'security_answer_2' => 'required',
    ]);

    $user = User::where('username', $request->username)->first();

    if (
        $request->security_answer_1 === $user->security_answer_1 && // Comparación directa
        $request->security_answer_2 === $user->security_answer_2   // Comparación directa
    ) {
        // Redirigir al formulario de restablecimiento de contraseña
        return redirect()->route('password.reset', ['token' => \Str::random(60)]);
    }

    return back()->withErrors(['security_answer_1' => 'Respuestas incorrectas']);
}

// Método para actualizar las preguntas de seguridad
public function update(Request $request)
{
    // Validación de los datos
    $request->validate([
        'security_question_1' => 'required|string|max:255',
        'security_answer_1' => 'required|string|max:255',
        'security_question_2' => 'required|string|max:255',
        'security_answer_2' => 'required|string|max:255',
    ]);

    // Actualizar las preguntas de seguridad del usuario autenticado
    $user = auth()->user();
    $user->update([
        'security_question_1' => $request->security_question_1,
        'security_answer_1' => $request->security_answer_1, // Sin hashear
        'security_question_2' => $request->security_question_2,
        'security_answer_2' => $request->security_answer_2, // Sin hashear
    ]);

    // Redireccionar con un mensaje de éxito
    return redirect()->back()->with('success', 'Preguntas de seguridad actualizadas correctamente.');
}
}