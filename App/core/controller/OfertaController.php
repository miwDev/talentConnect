<?php

namespace App\core\controller;

use App\core\data\EmpresaRepo;
use App\core\data\AlumnoRepo;
use App\core\data\OfertaRepo;
use App\core\data\SolicitudRepo;
use App\core\data\CicloRepo;
use App\core\model\Oferta;
use App\core\helper\Adapter;
use App\core\helper\Session;
use App\core\helper\Validator;
use App\mail\Mailer;

class OfertaController
{
    public function renderAddOferta($engine){
        $role = Session::readRole();
        $ciclos = CicloRepo::findAll();
        
        $errors = [];
        $postData = [];
        
        $result = $this->handlePostActions($engine);

        if (is_array($result)) {
            $errors = $result['errors'];
            $postData = $result['postData'];
        }

        echo $engine->render('Empresa/crearOferta', [
            'role' => $role,
            'ciclos' => $ciclos,
            'errors' => $errors,
            'postData' => $postData
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
        $resultado = null;

        if (isset($_POST["btnAddOffer"])) {
            
            $postData = $_POST;
            $errors = Validator::validateOfferCreation($postData);

            if (empty($errors)) {
                
                $oferta = Adapter::postDataToOffer();
                $success = OfertaRepo::save($oferta);

                if($success){
                    header($selfRoute);
                    exit();
                } else {
                    $errors['general_error'] = "Error de base de datos al guardar la oferta. Inténtalo de nuevo.";
                    $resultado = [
                        'errors' => $errors,
                        'postData' => $postData
                    ];
                }
            } else {
                $resultado = [
                    'errors' => $errors,
                    'postData' => $postData
                ];
            }
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
                header($selfRoute);
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
        
        if (isset($_POST["btnHire"])) {

            $solicitudId = $_POST['btnHire'];
            $solicitud = SolicitudRepo::findById($solicitudId);
            $alumno = SolicitudRepo::getAlumnoBySolicitudId($solicitudId);

            if ($solicitud && $alumno) {
                $oferta = OfertaRepo::findById($solicitud->ofertaId);
                $empresa = EmpresaRepo::findById($oferta->empresaId);

                if ($oferta && $empresa) {
                    $dbResponse = SolicitudRepo::deleteById($solicitudId);
                    
                    if ($dbResponse) {
                        $mailer = new Mailer();
                        $mailer->sendApplicationAccepted($alumno->username, $oferta->titulo, $empresa->nombre); 
                        
                        header($selfRoute);
                        exit();
                    } else {
                        error_log("Error: No se pudo borrar la solicitud en la base de datos");
                    }
                    header($selfRoute);
                    exit();
                }
            }
        }

        if (isset($_POST["btnReject"])) {

            $solicitudId = $_POST['btnReject'];
            $solicitud = SolicitudRepo::findById($solicitudId);
            $alumno = SolicitudRepo::getAlumnoBySolicitudId($solicitudId);

            if ($solicitud && $alumno) {
                $oferta = OfertaRepo::findById($solicitud->ofertaId);
                $empresa = EmpresaRepo::findById($oferta->empresaId);

                if ($oferta && $empresa) {
                    $dbResponse = SolicitudRepo::deleteById($solicitudId);
                    
                    if ($dbResponse) {
                        $mailer = new Mailer();
                        $mailer->sendApplicationRejected($alumno->username, $oferta->titulo, $empresa->nombre); 
                        
                        header($selfRoute);
                        exit();
                    } else {
                        error_log("Error: No se pudo borrar la solicitud en la base de datos");
                    }
                    header($selfRoute);
                    exit();
                }
            }
        }


        if(isset($_POST["btnCandidatos"])){

            $oferta = OfertaRepo::findById($_POST['btnCandidatos']);
            $candidatos = AlumnoRepo::findAllByOfertaId($_POST['btnCandidatos']);
            echo $engine->render('Empresa/verCandidatos', [
                'role' => $role,
                'candidatos' => $candidatos,
                'oferta' => $oferta
            ]);
            
            exit();

        }   
        
        return $resultado;
    }
}