<?php

namespace App\core\controller;
use App\core\helper\Session;

class LandingController
{

    public function renderLanding($engine)
    {

        $role = Session::readRole() ?? 'ROLE_GUEST';

        echo $engine->render('landing/home',[
                'title' => 'Talent Connect - Inicio',
                'role' => $role,
                'username' => Session::readUser()
        ]);
    }
}
