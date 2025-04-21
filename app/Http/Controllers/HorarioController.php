<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Asignatura;
use App\Models\Seccion;
use App\Models\Docente;
use App\Models\Turno;
use App\Models\Semestre;

class HorarioController extends Controller
{
    public function index()
    {
        // Transformar los horarios para FullCalendar
        $horarios = Horario::all()->map(function ($horario) {
            return [
                'id' => $horario->id,
                'title' => $horario->asignatura->name ?? 'Sin asignatura', // Ajusta según tu modelo
                'start' => $horario->fecha . 'T' . $horario->hora_inicio, // Usa un campo `fecha` en lugar de `dia`
                'end' => $horario->fecha . 'T' . $horario->hora_fin,
            ];
        });

        return view('horario.index', [
            'horarios' => $horarios,
            'asignaturas' => Asignatura::all(),
            'secciones' => Seccion::all(),
            'docentes' => Docente::all(),
            'turnos' => Turno::all(),
            'semestres' => Semestre::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'coordinador_cedula' => 'required|exists:users,cedula',
            'periodo_id' => 'required|exists:periodos,id',
            'asignatura_id' => 'required|exists:asignaturas,asignatura_id',
            'carrera_id' => 'required|exists:carreras,carrera_id',
            'docente_id' => 'required|exists:docentes,cedula_doc',
            'seccion_id' => 'required|exists:secciones,codigo_seccion',
            'turno_id' => 'required|exists:turnos,id_turno',
            'semestre_id' => 'required|exists:semestres,id_semestre',
            'fecha' => 'required|date', // Cambiado de `dia` a `fecha` para usar fechas completas
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        try {
            Horario::create($request->all());
            return redirect()->route('horario.index')->with('success', 'Horario asignado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('horario.index')->with('error', 'Ocurrió un error al asignar el horario.');
        }
    }
}