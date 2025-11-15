<?php

namespace App\core\controller;

use App\core\data\EmpresaRepo;
use App\core\helper\Adapter;
use App\core\helper\Session;

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
        
        $empresasAll = EmpresaRepo::findAll();
        $empresasAllDTO = Adapter::AllEmpresasToDTO($empresasAll);
        $empresasLista = $empresasAllDTO; 

        if (!empty($_POST["buscar"])) {
            $filter = $_POST["buscar"];
            $empresasFiltered = EmpresaRepo::filteredFindAll($filter);

            if (!empty($empresasFiltered)) {
                $empresasLista = Adapter::AllEmpresasToDTO($empresasFiltered);
            } 
        }

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

    private function handlePostActions($engine)
    {
        $selfRoute = "Location: " . $_SERVER['PHP_SELF'] . "?menu=admin-empresas";

        if (isset($_POST["btnAdd"])) {
            echo $engine->render('Empresa/addEmpresa');
            exit(); 
        }
        
        if (isset($_POST["btnAddCompany"])) {
            EmpresaRepo::save(Adapter::DTOtoEmpresa());
            header($selfRoute);
            exit();
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
        return EmpresaRepo::update($empresa);
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