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

        $this->handlePostActions($engine);

        $role = Session::readRole();
        $username = Session::readUser();
        $empresaId = Session::readUserId();
        $ofertas = OfertaRepo::findAllbyEmpresaId($empresaId);


        echo $engine->render('Empresa/misOfertas', [
            'role' => $role,
            'ofertas' => $ofertas
        ]);
    }

    private function handlePostActions($engine){
        $role = Session::readRole();
        $selfRoute = "Location: " . $_SERVER['PHP_SELF'] . "?menu=mis-ofertas";

        if (isset($_POST["btnAddOffer"])) {
            
            $oferta = Adapter::postDataToOffer();
            
            if ($oferta === false) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?menu=create-oferta");
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

        if (isset($_POST["btnEdit"])) {
            $id = $_POST['btnEdit'];
            $oferta = OfertaRepo::findById($id);
            
            $ciclo_ids = []; 

            foreach ($oferta->ciclos as $nombreCiclo) {
                $idCiclo = CicloRepo::getIdByName($nombreCiclo);
                if ($idCiclo) {
                    $ciclo_ids[] = $idCiclo;
                }
            }
            $oferta->ciclos = $ciclo_ids;
            
            $ciclos = CicloRepo::findAll();
            
            echo $engine->render('Empresa/editOferta', [
                'role' => $role,
                'ofertaEdit' => $oferta,
                'ciclos' => $ciclos
            ]);

            exit();
        }

        if (isset($_POST["btnAccept"])) {
            
            $oferta = Adapter::editedDataToOferta(); 
            
            if ($oferta === false) {
                header($selfRoute);
                exit();
            }

            $success = OfertaRepo::update($oferta);

            if ($success) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?menu=mis-ofertas");
                exit();
            }else{
                error_log("Error: No se pudo actualizar la oferta en la base de datos");
                
                $id = $oferta->id;
                $ofertaEdit = OfertaRepo::findById($id);
                
                $ciclo_ids = []; 
                foreach ($ofertaEdit->ciclos as $nombreCiclo) {
                    $idCiclo = CicloRepo::getIdByName($nombreCiclo);
                    if ($idCiclo) {
                        $ciclo_ids[] = $idCiclo;
                    }
                }
                $ofertaEdit->ciclos = $ciclo_ids;
                
                $ciclos = CicloRepo::findAll();
                
                echo $engine->render('Empresa/editOferta', [
                    'role' => $role,
                    'ofertaEdit' => $ofertaEdit,
                    'ciclos' => $ciclos,
                    'error' => 'Error: No se pudo actualizar la oferta.'
                ]);
                exit();
            }
        }

        if (isset($_POST["btnDelete"])) {
            
            $success = OfertaRepo::deleteById($_POST['btnDelete']);

            if ($success) {
                header($selfRoute);pter::editedDataToOferta(); 
                exit();
            }else{
                error_log("Error: No se pudo borrar la oferta en la base de datos");
                echo $engine->render('Empresa/editOferta', [
                    'role' => $role,
                    'ofertaEdit' => $ofertaEdit,
                    'ciclos' => $ciclos,
                    'error' => 'Error: No se pudo borrar la oferta.'
                ]);
                exit();
            }
        }
    }
}