<?php

namespace App\core\controller;

use App\core\data\EmpresaRepo;
use App\core\data\AlumnoRepo;
use App\core\data\OfertaRepo;
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
        $userId = Session::readUserId();

        $ofertasLast = OfertaRepo::getLast5OffersByCiclo($userId);
        $empresasRelated = EmpresaRepo::findCompaniesWithMatchingOffers($userId);

        echo $engine->render('Alumno/alumnoDashboard', [
            'role' => $role,
            'empresas' => $empresasRelated,
            'ofertas' => $ofertasLast
        ]);
    }
}