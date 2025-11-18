<?php

namespace App\core\controller;

use App\core\helper\Session;
use App\core\data\EmpresaRepo;

class LandingController
{

    public function renderLanding($engine)
    {

        $role = Session::readRole() ?? 'ROLE_GUEST';
        $empresas = EmpresaRepo::findAll();


        echo $engine->render('landing/home',[
                'title' => 'Talent Connect - Inicio',
                'role' => $role,
                'username' => Session::readUser(),
                'empresas' => $empresas
        ]);
    }
}
