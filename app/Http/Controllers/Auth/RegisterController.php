<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin'; // Redirigir a la ruta /admin después de registrarse

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'cedula' => ['required', 'string', 'max:255', 'unique:users'], // Validar cédula
            'name' => ['required', 'string', 'max:255'], // Validar nombre
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Validar correo
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Validar contraseña
            'security_question_1' => ['required', 'string', 'max:255'], // Validar primera pregunta
            'security_answer_1' => ['required', 'string', 'max:255'],   // Validar primera respuesta
            'security_question_2' => ['required', 'string', 'max:255'], // Validar segunda pregunta
            'security_answer_2' => ['required', 'string', 'max:255'],   // Validar segunda respuesta
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'cedula' => $data['cedula'], // Guardar cédula
            'name' => $data['name'], // Guardar nombre
            'email' => $data['email'], // Guardar correo
            'password' => Hash::make($data['password']), // Guardar contraseña
            'security_question_1' => $data['security_question_1'], // Guardar primera pregunta
            'security_answer_1' => Hash::make($data['security_answer_1']), // Hashear y guardar primera respuesta
            'security_question_2' => $data['security_question_2'], // Guardar segunda pregunta
            'security_answer_2' => Hash::make($data['security_answer_2']), // Hashear y guardar segunda respuesta
        ]);
    }
}
