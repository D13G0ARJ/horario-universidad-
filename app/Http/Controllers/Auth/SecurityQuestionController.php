<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

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
            $user->security_answer_1 === $request->security_answer_1 &&
            $user->security_answer_2 === $request->security_answer_2
        ) {
            // Redirigir al formulario de restablecimiento de contraseña
            return redirect()->route('password.reset', ['token' => \Str::random(60)]);
        }

        return back()->withErrors(['security_answer_1' => 'Respuestas incorrectas']);
    }
}
