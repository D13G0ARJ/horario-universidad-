<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeccionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
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
            'aula_id' => 'required|exists:aulas,id', // Asumiendo que Aula usa 'id' como PK
            'carrera_id' => 'required|exists:carreras,carrera_id', // Corregido aquí
            'turno_id' => 'required|exists:turnos,id_turno',
            'semestre_id' => [
            'required',
            'exists:semestres,id_semestre',
            function ($attribute, $value, $fail) {
                $turno = $this->input('turno_id');
                $semestre = \App\Models\Semestre::where('id_semestre', $value)->first(); // Usa where()

                if (!$semestre) {
                    $fail('Semestre no encontrado');
                    return;
                }

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
            'carrera_id.exists' => 'La carrera seleccionada no es válida', // Nuevo mensaje
            'aula_id.exists' => 'El aula seleccionada no es válida', // Nuevo mensaje
            'semestre_id.exists' => 'El semestre seleccionado no es válido',
            'turno_id.exists' => 'El turno seleccionado no es válido'
        ];
    }
}