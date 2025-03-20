<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Muestra el formulario para verificar el usuario.
     */
    public function showVerifyUserForm()
    {
        return view('auth.passwords.verify-user');
    }

    /**
     * Verifica si el usuario existe y redirige a las preguntas de seguridad.
     */
    public function verifyUser(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,cedula', // Validar que 'username' exista en la columna 'cedula'
        ]);

        $user = User::where('cedula', $request->username)->first(); // Buscar por 'cedula'

        if ($user) {
            // Almacenar datos en la sesión
            session([
                'username' => $user->cedula,
                'question1' => $user->security_question_1,
                'question2' => $user->security_question_2,
            ]);

            // Redirigir a la vista de preguntas de seguridad con un mensaje de éxito
            return redirect()->route('password.securityQuestions')->with('success', 'Verificado con éxito.');
        }

        // Si no se encuentra el usuario, regresar con un mensaje de error
        return back()->withErrors(['username' => 'No se ha encontrado el usuario.']);
    }

    /**
     * Verifica las respuestas de seguridad y redirige al formulario de restablecimiento de contraseña.
     */
    public function verifyAnswers(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,cedula', // Validar que 'username' exista en la columna 'cedula'
            'security_answer_1' => 'required',
            'security_answer_2' => 'required',
        ]);

        $user = User::where('cedula', $request->username)->first(); // Buscar por 'cedula'

        // Verificar las respuestas de seguridad
        if (
            $user->security_answer_1 === $request->security_answer_1 &&
            $user->security_answer_2 === $request->security_answer_2
        ) {
            // Generar un token y redirigir al formulario de restablecimiento de contraseña
            return redirect()->route('password.reset', ['token' => Str::random(60)]);
        }

        // Si las respuestas son incorrectas, regresar con un error
        return back()->withErrors([
            'security_answer_1' => 'Las respuestas de seguridad no coinciden.',
            'security_answer_2' => 'Las respuestas de seguridad no coinciden.',
        ]);
    }

    /**
     * Muestra el formulario de restablecimiento de contraseña.
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset-password', [
            'token' => $token,
            'username' => session('username'), // Obtener el username desde la sesión
        ]);
    }

    /**
     * Actualiza la contraseña del usuario.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,cedula', // Validar que 'username' exista en la columna 'cedula'
            'password' => 'required|confirmed|min:8', // Validar que la contraseña sea confirmada y tenga al menos 8 caracteres
        ]);

        $user = User::where('cedula', $request->username)->first(); // Buscar por 'cedula'

        if ($user) {
            // Actualizar la contraseña del usuario
            $user->password = bcrypt($request->password);
            $user->save();

            // Redirigir al inicio de sesión con un mensaje de éxito
            return redirect()->route('login')->with('success', 'Contraseña restablecida con éxito.');
        }

        // Si no se pudo restablecer la contraseña, regresar con un error
        return back()->withErrors(['username' => 'No se pudo restablecer la contraseña.']);
    }
}
