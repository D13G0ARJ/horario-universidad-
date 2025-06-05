<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Carrera;
use App\Models\Periodo;
use App\Models\Asignatura;
use App\Models\Horario;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Muestra el dashboard principal del administrador con los conteos de entidades.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener el conteo de docentes registrados
        $docentesCount = Docente::count();

        // Obtener el conteo de períodos creados
        $periodosCount = Periodo::count();

        // Obtener el conteo de asignaturas creadas
        $asignaturasCount = Asignatura::count();

        // Obtener el conteo de horarios creados por SECCIÓN Y PERÍODO.
        // Esto cuenta el número de combinaciones únicas de periodo_id y seccion_id
        // que tienen al menos un horario asociado.
        $horariosCount = Horario::select('periodo_id', 'seccion_id')
                                ->distinct()
                                ->count();

        // Pasa los conteos a la vista 'admin.index'
        return view('admin.index', compact(
            'docentesCount',
            'periodosCount',
            'asignaturasCount',
            'horariosCount'
        ));
    }
}
