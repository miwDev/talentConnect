<?php

namespace App\core\controller;

class AuthController
{

    public function renderLogin($engine)
    {
        echo $engine->render('pages/login');
    }

    public function renderRegRedirect($engine)
    {
        echo $engine->render('pages/regRedirect');
    }

    public function renderRegAlumno($engine)
    {
        echo $engine->render('pages/registerA');
    }

    public function renderRegEmpresa($engine)
    {
        echo $engine->render('pages/registerE');
    }
}
