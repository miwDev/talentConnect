<?php

namespace App\core\controller;

class AuthController
{

    public function renderLogin($engine)
    {
        echo $engine->render('Auth/login');
    }

    public function renderRegRedirect($engine)
    {
        echo $engine->render('Auth/regRedirect');
    }

    public function renderRegAlumno($engine)
    {
        echo $engine->render('Auth/registerA');
    }

    public function renderRegEmpresa($engine)
    {
        echo $engine->render('Auth/registerE');
    }
}
