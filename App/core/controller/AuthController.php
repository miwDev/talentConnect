<?php

namespace App\core\controller;

use App\core\data\Authorization;
use App\core\helper\Session;

class AuthController
{

    public function renderLogin($engine)
    {
        Session::start();

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $user = Authorization::verifyUser($_POST['username'], $_POST['password']);
            if ($user !== false) {
                Session::setSession($user);

                switch (Session::readRole()) {
                    case "ROLE_ADMIN":
                        header("Location: " . $_SERVER['PHP_SELF'] . "?menu=admin-dashboard");
                        break;
                    case "ROLE_ALUMNO":
                        header("Location: " . $_SERVER['PHP_SELF'] . "?menu=alumno-dashboard");
                        break;
                    case "ROLE_EMPRESA":
                        header("Location: " . $_SERVER['PHP_SELF'] . "?menu=admin-dashboard");
                        break;
                }
            } else {
                //errores
            }
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
