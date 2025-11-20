<?php

namespace App\core\controller;

use App\core\data\Authorization;
use App\core\helper\Session;
use App\core\helper\Adapter;
use App\core\data\EmpresaRepo;
use App\core\helper\Validator;



class AuthController
{   
    public function renderLogin($engine)
    {
        Session::start();
        $errors = [];

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $errors = Validator::validateLogin($username, $password);

            if (empty($errors)) {
                $user = Authorization::verifyUser($username, $password);
                
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
                    $errors['db_auth'] = "Credenciales incorrectas o usuario no activo. Vuelve a intentarlo.";
                }
            }
        }
        
        echo $engine->render('Auth/login' , [
            'role' => 'ROLE_GUEST', 
            'errors' => $errors,
            'username' => $_POST['username'] ?? ''
        ]);
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
        $errors = [];
        $postData = [];
        
        $result = $this->handlePostActions($engine);
        
        if (is_array($result)) {
            $errors = $result['errors'] ?? [];
            $postData = $result['postData'] ?? [];
            
            echo $engine->render('Auth/registerE' , [
                'role' => 'ROLE_GUEST', 
                'errors' => $errors,
                'postData' => $postData
            ]);
            return;
        } elseif ($result === 'success') {
            return;
        }

        if (!isset($_POST["btnRegistro"])) {
            echo $engine->render('Auth/registerE' , ['role' => 'ROLE_GUEST']);
        }
    }

    public function renderNotVerified($engine)
    {
        echo $engine->render('Empresa/empresaNotVerified');
    }

    private function handlePostActions($engine){

        $salida = true;

        if (isset($_POST["btnRegistro"])) {
            
            $postData = $_POST;
            $errors = Validator::validateEmpresaRegistration($postData);

            if (empty($errors)) {
                $empresa = Adapter::postDatatoEmpresa();
                $empresaId = EmpresaRepo::save($empresa);

                if ($empresaId) {
                    echo $engine->render('Empresa/empresaNotVerified');
                } else {
                    $errors['general_error'] = "Error al intentar registrar la empresa. Por favor, inténtalo de nuevo.";
                }
            }

            $salida = [
                'errors' => $errors,
                'postData' => $postData
            ];
        }

        return $salida;
    }

    
}

