<?php

namespace App\core\controller;

use App\core\data\EmpresaRepo;
use App\core\data\OfertaRepo;
use App\core\data\SolicitudRepo;
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
        $ofertas = OfertaRepo::findAllNotApplied(Session::readUserId());

        echo $engine->render('Alumno/verOfertasAlu', [
            'role' => $role,
            'token' => $token,
            'ofertas' => $ofertas
        ]);
    }

    public function renderApplied($engine){
        $role = Session::readRole();
        $token = Session::readToken();
        $username = Session::readUser();

        $ofertas = OfertaRepo::findAllApplied(Session::readUserId());
        $ofertas = Adapter::AllOfertaToDTO($ofertas);

        $solicitudes = SolicitudRepo::findAllByAlumnoId(Session::readUserId());
        


        echo $engine->render('Alumno/misSolicitudes', [
            'role' => $role,
            'token' => $token,
            'ofertas' => $ofertas,
            'solicitudes' => $solicitudes
        ]);
    }
}