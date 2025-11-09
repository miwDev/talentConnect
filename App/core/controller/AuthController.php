<?php

namespace App\core\controller;

use App\core\data\Authorization;

class AuthController
{

    public function renderLogin($engine)
    {

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $_POST['resultVer'] = Authorization::verifyUser($_POST['username'], $_POST['password']);
        }


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
