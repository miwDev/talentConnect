<?php

namespace App\core\controller;

use App\core\data\EmpresaRepo;
use App\core\data\OfertaRepo;
use App\core\helper\Adapter;
use App\core\helper\Session;
use App\core\helper\Validator;
use App\mail\Mailer;



class EmpresaController
{
    public function renderDashboard($engine){
        $role = Session::readRole();
        $username = Session::readUser();

        echo $engine->render('Empresa/empresaDashboard', [
            'role' => $role,
            'username' => Session::readUser()
        ]);
    }

    public function renderList($engine)
    {
        $this->handlePostActions($engine);
        
        $empresasLista = [];


        if (isset($_POST['btnOrdenar'])) {
            $filtro = $_POST['ordenEmpresa'] ?? 'nombre';
            $empresasRaw = EmpresaRepo::findAllOrderBy($filtro);
            $empresasLista = Adapter::AllEmpresasToDTO($empresasRaw);
        } 
        elseif (isset($_POST['btnBuscar']) && !empty($_POST['buscar'])) {
            $texto = $_POST['buscar'];
            $empresasRaw = EmpresaRepo::filteredFindAll($texto);
            $empresasLista = Adapter::AllEmpresasToDTO($empresasRaw);
        } 
        else {
            $empresasRaw = EmpresaRepo::findAll();
            $empresasLista = Adapter::AllEmpresasToDTO($empresasRaw);
        }

        $empresasAll = EmpresaRepo::findAll();
        $empresasAllDTO = Adapter::AllEmpresasToDTO($empresasAll);
        $empresasPendientes = [];
        
        foreach ($empresasAllDTO as $empresa) {
            if (!$empresa->validated) {
                $empresasPendientes[] = $empresa;
            }
        }

        echo $engine->render('Empresa/listadoEmpresa', [
            'empresasTotal' => $empresasLista,
            'pendientes' => $empresasPendientes
        ]);
    }

    public function renderFicha($engine)
    {
        $role = Session::readRole();
        $idEmpresa = $_GET["empresa"];

        $empresa = EmpresaRepo::findById($idEmpresa);

        $filterType = "active";
        
        if (isset($_POST['ofertasFilter'])) {
            $filterType = $_POST['ofertasFilter'];
        }

        $ofertas = [];
        
        if ($filterType === "expired") {
            $ofertas = OfertaRepo::findExpiredByEmpresaId($idEmpresa);
        } else {
            $ofertas = OfertaRepo::findActiveByEmpresaId($idEmpresa);
        }
        
        echo $engine->render('Empresa/verEmpresaInfo', [
            'role' => $role,
            'empresa' => $empresa,
            'ofertas' => $ofertas
        ]);
    }



    private function handlePostActions($engine)
    {
        $selfRoute = "Location: " . $_SERVER['PHP_SELF'] . "?menu=admin-empresas";

        if (isset($_POST["btnAdd"])) {
            echo $engine->render('Empresa/addEmpresa');
            exit(); 
        }
        
        // CORRECCIÓN: Este es el único bloque para manejar btnAddCompany
        if (isset($_POST["btnAddCompany"])) {
            
            // 1. Validar los datos
            $errors = Validator::validateEmpresaRegistrationAdmin($_POST);

            if (empty($errors)) {
                // 2. Si no hay errores, guarda la empresa y redirige.
                EmpresaRepo::save(Adapter::DTOtoEmpresa());
                header($selfRoute);
                exit();
            } else {
                // 3. Si hay errores, re-renderiza la plantilla con los datos POST y los errores.
                echo $engine->render('Empresa/addEmpresa', [
                    'errors' => $errors,
                    'postData' => $_POST
                ]);
                exit();
            }
        }
        
        if (isset($_POST["btnEdit"])) {
            $id = $_POST["btnEdit"];
            $empresaDTO = Adapter::empresaToDTO($id);
            echo $engine->render('Empresa/editEmpresa', [
                'empresaEdit' => $empresaDTO
            ]);
            exit();
        }
        
        if (isset($_POST["btnAccept"])) {
            $id = $_POST["id"];
            Adapter::empresaEditDTO($id, $_POST);
            header($selfRoute);
            exit();
        }

        if (isset($_POST["btnVerFicha"])) {
            $id = $_POST["btnVerFicha"];
            $empresa = EmpresaRepo::findById($id);
            echo $engine->render('Empresa/verFichaEmpresa', [
                'empresaVer' => $empresa
            ]);
            exit();
        }

        if (isset($_POST["btnCancel"])) {
            header($selfRoute);
            exit();
        }
        
        if (isset($_POST["btnBorrar"]) || isset($_POST["btnRechazar"])) {
            $id = $_POST["btnBorrar"] ?? $_POST["btnRechazar"];
            $this->deleteCompany($id);
            header($selfRoute);
            exit();
        }
        
        if (isset($_POST["btnConfirmar"])) {
            $this->validateCompany($_POST["btnConfirmar"]);
            header($selfRoute);
            exit();
        }
    }

    public function validateCompany($id)
    {
        $empresa = EmpresaRepo::findById($id);
        $empresa->validacion = 1;
        EmpresaRepo::update($empresa);

        $mail = new Mailer();
        $mail->sendCompanyVerified($empresa->username);
        return true;
    }

    public function deleteCompany($id)
    {
        $empresa = EmpresaRepo::findById($id);

        $rutaLogo = $empresa->logo;
        $basePath = __DIR__ . '/../../public';

        if ($rutaLogo && $rutaLogo !== '/assets/images/companyNull.svg') {
            $fullLogoPath = $basePath . $rutaLogo;
            
            if (file_exists($fullLogoPath)) {
                unlink($fullLogoPath);
            }
        }
        return EmpresaRepo::deleteById($id);
    }
}