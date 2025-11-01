<?php

namespace App\core\controller;

use App\core\data\AlumnoRepo;
use App\core\model\Alumno;

class AlumnoController
{

    public function renderList($engine)
    {
        echo $engine->render('pages/listadoAlumnos');
    }

    public function renderDashboard($engine) {}
}
