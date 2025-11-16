<?php

namespace App\core\controller;

use League\Plates\Engine;
use App\core\controller\LandingController;
use App\core\controller\AuthController;
use App\core\controller\AlumnoController;
use App\core\controller\EmpresaController;
use App\core\controller\AdminController;
use App\core\controller\SolicitudController;
use App\core\helper\Login;
use App\core\helper\Session;
use App\core\data\EmpresaRepo;
use App\core\data\AlumnoRepo;
use App\core\model\Empresa;
use App\core\model\Alumno;

class Router
{

    private $templatesPath;
    private $engine;

    public function __construct()
    {
        $this->templatesPath = __DIR__ . '/../../core/view/templates';
        $this->engine = new Engine($this->templatesPath);
    }

    private function getDashboardRoute(string $role): string
    {
        switch ($role) {
            case 'ROLE_ALUMNO':
                $route ='alumno-dashboard';
                break;
            case 'ROLE_EMPRESA':
                $route ='empresa-dashboard';
                break;
            case 'ROLE_ADMIN':
                $route ='admin-dashboard';
                break;
            default:
                 $route ='home';
        }

        return $route;
    }

    public function router()
    {
        Session::start();

        $menu = $_GET['menu'] ?? 'home'; 

        $role = Session::readRole() ?? 'ROLE_GUEST';
        
        $dashboard = $this->getDashboardRoute($role);

        switch ($menu) {
            case 'login':
            case 'regRedirect':
            case 'regEmpresa':
            case 'regAlumno':
            case 'empresaUnverified':
                if ($role !== 'ROLE_GUEST') {
                    header("Location: ?menu={$dashboard}"); 
                    break;
                }
                $Auth = new AuthController();
                $this->handlePublicAuthRoutes($menu, $Auth);
                break; 
            case 'home':
                $landing = new LandingController();
                $landing->renderLanding($this->engine);
                break;
        }
        switch($role){
            case 'ROLE_ALUMNO':
                $this->handleAlumnoRoutes($menu);
                break;

            case 'ROLE_EMPRESA':
                $this->handleEmpresaRoutes($menu);
                break;

            case 'ROLE_ADMIN':
                $this->handleAdminRoutes($menu);
                break;
        }
    }

    private function handlePublicAuthRoutes($menu, AuthController $Auth) {
        switch ($menu) {
            case 'login': 
                $Auth->renderLogin($this->engine); 
                break;
            case 'regRedirect': 
                $Auth->renderRegRedirect($this->engine); 
                break;
            case 'regEmpresa': 
                $Auth->renderRegEmpresa($this->engine); 
                break;
            case 'regAlumno': 
                $Auth->renderRegAlumno($this->engine); 
                break;
            case 'empresaUnverified': 
                $Auth->renderNotVerified($this->engine); 
                break;
        }
    }

    private function handleAdminRoutes($menu) {
        switch ($menu) {
            case 'admin-alumnos':
                $alumnoManage = new AlumnoController();
                $alumnoManage->renderList($this->engine);
                break;
            case 'admin-empresas':
                $empresaManage = new EmpresaController();
                $empresaManage->renderList($this->engine);
                break;
            case 'admin-dashboard':
                $adminManage = new AdminController();
                $adminManage->renderList($this->engine);
                break;
            default:
                header('Location: ?menu=admin-dashboard'); 
                exit;
        }
    }

    private function handleAlumnoRoutes($menu) {
        switch ($menu) {
            case 'alumno-dashboard':
                $alumnoManage = new AlumnoController();
                $alumnoManage->renderDashboard($this->engine);
                break;
            case 'ofertas-alumno':
                $solicitudManage = new SolicitudController();
                $solicitudManage->renderOffers($this->engine);
                break;
            default:
                header('Location: ?menu=alumno-dashboard'); 
                break;
        }
    }

    private function handleEmpresaRoutes($menu) {
        switch ($menu) {
            case 'empresa-dashboard':
                $empresaManage = new EmpresaController();
                $empresaManage->renderDashboard($this->engine);
                break;
            case 'create-oferta':
                $ofertaManage = new OfertaController();
                $ofertaManage->renderAddOferta($this->engine);
                break;
            case 'mis-ofertas':
                $ofertaManage = new OfertaController();
                $ofertaManage->renderMyOffers($this->engine);
                break;
            default:
                header('Location: ?menu=empresa-dashboard'); 
                break;
        }
    }
}