<?php

namespace App\core\controller;

use League\Plates\Engine;
use App\core\controller\LandingController;
use App\core\controller\AuthController;
use App\core\controller\AlumnoController;

class Router
{
    private $templatesPath;
    private $engine;

    public function __construct()
    {
        $this->templatesPath = __DIR__ . '/../../core/view/templates';
        $this->engine = new Engine($this->templatesPath);
    }

    public function router()
    {
        if (isset($_GET['menu'])) {
            $menu = $_GET['menu'];
        } else {
            $menu = 'home';
        }

        switch ($menu) {
            case 'home':
                $landing = new LandingController();
                $landing->renderLanding($this->engine);
                break;

            case 'login':
                $Auth = new AuthController();
                $Auth->renderLogin($this->engine);
                break;
            case 'regRedirect':
                $Auth = new AuthController();
                $Auth->renderRegRedirect($this->engine);
                break;
            case 'regEmpresa':
                $Auth = new AuthController();
                $Auth->renderRegEmpresa($this->engine);
                break;
            case 'regAlumno':
                $Auth = new AuthController();
                $Auth->renderRegAlumno($this->engine);
                break;

            case 'admin-alumnos':
                $alumnoManage = new AlumnoController();
                $alumnoManage->renderList($this->engine);
                break;

            case 'admin-empresas':
                $empresaManage = new EmpresaController();
                $empresaManage->renderList($this->engine);
                break;
        }
    }
}
