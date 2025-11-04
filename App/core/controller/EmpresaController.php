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
            echo $engine->render('pages/addEmpresa');
        } elseif (isset($_POST["btnEdit"])) {

            $id = $_POST["btnEdit"];
            $empresaDTO = Adapter::empresaToDTO($id);
            echo $engine->render('pages/editEmpresa', [
                'empresaEdit' => $empresaDTO
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

        $empresasDTO = Adapter::AllEmpresasToDTO(EmpresaRepo::findAll());
        $empresasPendientes = array_filter($empresasDTO, fn($empresa) => !$empresa->validated);

        echo $engine->render('pages/listadoEmpresa', [
            'empresastotal' => $empresasDTO,
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
