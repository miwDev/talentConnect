<?php

namespace App\core\controller;

use App\core\data\AlumnoRepo;
use App\core\model\Alumno;
use App\core\helper\Session;
use App\core\helper\Adapter;

class AlumnoController
{

    public function renderList($engine)
    {
        echo $engine->render('Alumno/listadoAlumnos');
    }
    
    public function renderDashboard($engine)
    {
        $role = Session::readRole();
        $username = Session::readUser();

        echo $engine->render('Alumno/alumnoDashboard', [
            'role' => $role,
            'username' => Session::readUser()
        ]);
    }
}