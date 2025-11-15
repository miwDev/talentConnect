<?php

namespace App\core\controller;

use App\core\data\EmpresaRepo;
use App\core\data\OfertaRepo;
use App\core\data\CicloRepo;
use App\core\model\Oferta;
use App\core\helper\Adapter;
use App\core\helper\Session;

class OfertaController
{
    public function renderAddOferta($engine){

        $this->handlePostActions($engine);

        $role = Session::readRole();
        $username = Session::readUser();
        $ciclos = CicloRepo::findAll();

        echo $engine->render('Empresa/crearOferta', [
            'role' => $role,
            'ciclos' => $ciclos
        ]);
    }

    public function renderMyOffers($engine){

        //$this->handlePostActions($engine);

        $role = Session::readRole();
        $username = Session::readUser();
        $ofertas = OfertaRepo::findAll();


        echo $engine->render('Empresa/misOfertas', [
            'role' => $role,
            'ofertas' => $ofertas
        ]);
    }

    private function handlePostActions($engine){
    $selfRoute = "Location: " . $_SERVER['PHP_SELF'] . "?menu=empresa-dashboard";

    if (isset($_POST["btnAddOffer"])) {
        
        $oferta = Adapter::postDataToOffer();
        
        if ($oferta === false) {
            header($selfRoute);
            exit();
        }
        
        $success = OfertaRepo::save($oferta);

        if($success){
            header($selfRoute);
            exit(); 
        } else {
            error_log("Error: No se pudo guardar la oferta en la base de datos");
        }
        exit(); 
    }

    if (isset($_POST["btnCancel"])) {
        header($selfRoute);
        exit(); 
    }
}
}