<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeccionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cambiar a false si necesitas control de acceso
    }

    public function rules()
    {
        return [
            'codigo_seccion' => [
                'required',
                'string',
                'max:20',
                Rule::unique('secciones')->ignore($this->seccion?->codigo_seccion, 'codigo_seccion')
            ],
            'aula_id' => 'required|exists:aulas,id',
            'carrera_id' => 'required|exists:carreras,id',
            'turno_id' => 'required|exists:turnos,id_turno',
            'semestre_id' => [
                'required',
                'exists:semestres,id_semestre',
                function ($attribute, $value, $fail) {
                    $turno = $this->input('turno_id');
                    $semestre = \App\Models\Semestre::find($value);

                    // Validación personalizada
                    if ($turno == 1 && $semestre->numero > 8) {
                        $fail('Para turno diurno solo se permiten semestres del 1 al 8');
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'codigo_seccion.unique' => 'Este código de sección ya está registrado',
            'semestre_id.exists' => 'El semestre seleccionado no es válido',
            'turno_id.exists' => 'El turno seleccionado no es válido'
        ];
    }
}