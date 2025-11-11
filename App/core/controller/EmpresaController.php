<?php

namespace App\core\controller;

use App\core\data\EmpresaRepo;
use App\core\helper\Adapter;

class EmpresaController
{
    public function renderList($engine)
    {
        $selfRoute = "Location: " . $_SERVER['PHP_SELF'] . "?menu=admin-empresas";

        if (isset($_POST["btnAdd"])) {
            echo $engine->render('Empresa/addEmpresa');
        } elseif (isset($_POST["btnAddCompany"])) {
            EmpresaRepo::save(Adapter::DTOtoEmpresa());
            header($selfRoute);
            exit();
        } elseif (isset($_POST["btnEdit"])) {
            $id = $_POST["btnEdit"];
            $empresaDTO = Adapter::empresaToDTO($id);
            echo $engine->render('Empresa/editEmpresa', [
                'empresaEdit' => $empresaDTO
            ]);
            exit();
        } elseif (isset($_POST["btnVerFicha"])) {
            $id = $_POST["btnVerFicha"];
            $empresa = EmpresaRepo::findById($id);
            echo $engine->render('Empresa/verFichaEmpresa', [
                'empresaVer' => $empresa
            ]);
            exit();
        } elseif (isset($_POST["btnAccept"])) {
            $id = $_POST["id"];
            Adapter::empresaEditDTO($id, $_POST);
            header($selfRoute);
            exit();
        } elseif (isset($_POST["btnCancel"])) {
            header($selfRoute);
            exit();
        } elseif (isset($_POST["btnBorrar"])) {

            $this->deleteCompany($_POST["btnBorrar"]);
            header($selfRoute);
            exit();
        } elseif (isset($_POST["btnRechazar"])) {

            $this->deleteCompany($_POST["btnRechazar"]);
            header($selfRoute);
            exit();
        } elseif (isset($_POST["btnConfirmar"])) {

            $this->validateCompany($_POST["btnConfirmar"]);
            header($selfRoute);
            exit();
        }

        $empresasAllDTO = [];
        $empresasLista = [];
        $empresasPendientes = [];

        $empresasAll = EmpresaRepo::findAll();
        $empresasAllDTO = Adapter::AllEmpresasToDTO($empresasAll);
        $empresasLista = $empresasAllDTO;

        if (!empty($_POST["buscar"])) {
            $filter = $_POST["buscar"];
            $empresasFiltered = EmpresaRepo::filteredFindAll($filter);

            if (!empty($empresasFiltered)) {
                $empresasLista = Adapter::AllEmpresasToDTO($empresasFiltered);
            } else {
                $empresasLista = $empresasAllDTO;
            }
        }

        // Cálculo de $empresasPendientes sin array_filter
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

    public function validateCompany($id)
    {
        $empresa = EmpresaRepo::findById($id);
        $empresa->validacion = 1;
        return EmpresaRepo::update($empresa);
    }

    public function deleteCompany($id)
    {
        return EmpresaRepo::deleteById($id);
    }
}
