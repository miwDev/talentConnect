<?php

namespace App\core\controller;

use App\core\data\Authorization;
use App\core\helper\Session;
use App\core\helper\Adapter;
use App\core\data\EmpresaRepo;


class AuthController
{

    public function renderLogin($engine)
    {
        Session::start();

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $user = Authorization::verifyUser($_POST['username'], $_POST['password']);
            if ($user !== false) {
                if($user['user'] === "empresaNoVerificada"){
                    header("Location: " . $_SERVER['PHP_SELF'] . "?menu=empresaUnverified");
                    exit;
                }else{
                    Session::setSession($user);
                    switch (Session::readRole()) {
                        case "ROLE_ADMIN":
                            header("Location: " . $_SERVER['PHP_SELF'] . "?menu=admin-dashboard");
                            exit;
                        case "ROLE_ALUMNO":
                            header("Location: " . $_SERVER['PHP_SELF'] . "?menu=alumno-dashboard");
                            exit;
                        case "ROLE_EMPRESA":
                            header("Location: " . $_SERVER['PHP_SELF'] . "?menu=empresa-dashboard");
                            exit;
                    }
                }
            } else {
                //pintar errores
            }
        }
        echo $engine->render('Auth/login' , ['role' => 'ROLE_GUEST']);
    }

    public function renderRegRedirect($engine)
    {
        echo $engine->render('Auth/regRedirect' , ['role' => 'ROLE_GUEST']);
    }

    public function renderRegAlumno($engine)
    {
        echo $engine->render('Auth/registerA' , ['role' => 'ROLE_GUEST']);
    }

    public function renderRegEmpresa($engine)
    {

        $this->handlePostActions($engine);
        echo $engine->render('Auth/registerE' , ['role' => 'ROLE_GUEST']);
    }

    public function renderNotVerified($engine)
    {
        echo $engine->render('Empresa/empresaNotVerified');
    }

    private function handlePostActions($engine){

        if (isset($_POST["btnRegistro"])) {

            $required_post_fields = [
                "email", 
                "cif",
                "telefono",
                "nombre_empresa",
                "password",
                "contacto_persona",
                "contacto_telefono",
                "provincia",
                "localidad",
                "direccion"
            ];
            
            $missing_post_fields = [];
            foreach ($required_post_fields as $field) {
                if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    $missing_post_fields[] = $field;
                }
            }

            if (empty($missing_post_fields)) {
                $empresa = Adapter::postDatatoEmpresa();
                $empresaId = EmpresaRepo::save($empresa);

                if($empresaId){
                    echo $engine->render('Empresa/empresaNotVerified');
                }else{
                    //#TODO Error gestion
                }
            }
        }
    }
}

