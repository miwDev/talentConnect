<?php

namespace App\core\controller;

use App\core\data\EmpresaRepo;
use App\core\data\OfertaRepo;
use App\core\data\CicloRepo;
use App\core\model\Oferta;
use App\core\helper\Adapter;
use App\core\helper\Session;

class SolicitudController
{
    public function renderOffers($engine){
        $role = Session::readRole();
        $token = Session::readToken();
        $username = Session::readUser();
        $ofertas = OfertaRepo::findAll();

        echo $engine->render('Alumno/verOfertasAlu', [
            'role' => $role,
            'token' => $token,
            'ofertas' => $ofertas
        ]);
    }
}