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
    $idAlu = Session::readUserId();
    
    error_log("=== renderApplied START ===");
    error_log("ID Alumno: " . $idAlu);
    
    $ofertas = OfertaRepo::findAllApplied($idAlu);
    error_log("Ofertas encontradas: " . count($ofertas));
    
    $solicitudes = SolicitudRepo::findAllByAlumnoId($idAlu);
    error_log("Solicitudes encontradas: " . count($solicitudes));
    
    // ✅ ELIMINAR esta línea que causa el error
    // $ofertasDTO = Adapter::AllOfertaToDTO($ofertas);
    
    // ✅ Usar directamente las ofertas completas
    $ofertasById = [];
    foreach ($ofertas as $oferta) {  // ✅ Usar $ofertas en lugar de $ofertasDTO
        $ofertasById[$oferta->id] = $oferta;
    }
    
    $solicitudesYOfertas = [];
    foreach ($solicitudes as $solicitud) {
        $solicitudesYOfertas[] = [
            'solicitud' => $solicitud,
            'oferta' => $ofertasById[$solicitud->ofertaId] ?? null
        ];
    }
    
    echo $engine->render('Alumno/misSolicitudes', [
        'role' => $role,
        'token' => $token,
        'solicitudesYOfertas' => $solicitudesYOfertas
    ]);
}
}