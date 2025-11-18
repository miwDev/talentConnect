<?php

namespace App\core\controller;

use League\Plates\Engine;
use App\core\controller\LandingController;
use App\core\controller\AuthController;
use App\core\controller\AlumnoController;
use App\core\controller\EmpresaController;
use App\core\controller\AdminController;
use App\core\controller\SolicitudController;
use App\core\helper\Session;

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
        $dashboard = '';
        switch ($role) {
            case 'ROLE_ALUMNO':
                $dashboard = 'alumno-dashboard';
            case 'ROLE_EMPRESA':
                $dashboard = 'empresa-dashboard';
            case 'ROLE_ADMIN':
                $dashboard = 'admin-dashboard';
            default:
                $dashboard = 'home';
        }

        return $dashboard;
    }

    public function router()
    {
        Session::start();

        $menu = $_GET['menu'] ?? 'home'; 
        $role = Session::readRole() ?? 'ROLE_GUEST';
        
        switch ($menu) {
            case 'home':
                $landing = new LandingController();
                $landing->renderLanding($this->engine);
                exit;

            case 'login':
            case 'regRedirect':
            case 'regEmpresa':
            case 'regAlumno':
            case 'empresaUnverified':
                if ($role !== 'ROLE_GUEST') {
                    $dashboard = $this->getDashboardRoute($role);
                    header("Location: ?menu={$dashboard}"); 
                    exit;
                }
                $Auth = new AuthController();
                $this->handlePublicAuthRoutes($menu, $Auth);
                exit;
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
            default:
                header('Location: ?menu=home');
                exit;
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
                exit;
            case 'admin-empresas':
                $empresaManage = new EmpresaController();
                $empresaManage->renderList($this->engine);
                exit;
            case 'admin-dashboard':
                $adminManage = new AdminController();
                $adminManage->renderList($this->engine);
                exit;
            default:
                header('Location: ?menu=admin-dashboard'); 
                exit;
        }
    }

    private function handleAlumnoRoutes($menu) {
        $alumnoManage = new AlumnoController();
        $solicitudManage = new SolicitudController();
        $empresaManage = new EmpresaController();
        switch ($menu) {
            case 'alumno-dashboard':
                $alumnoManage->renderDashboard($this->engine);
                exit;
            case 'ofertas-alumno':
                $solicitudManage->renderOffers($this->engine);
                exit;
            case 'misSolicitudes-alumno':
                $solicitudManage->renderApplied($this->engine);
                exit;
            case 'ver-empresa':
                $empresaManage->renderFicha($this->engine);
                exit;
            default:
                header('Location: ?menu=alumno-dashboard'); 
                exit;
        }
    }

    private function handleEmpresaRoutes($menu) {
        $ofertaManage = new OfertaController();
        $empresaManage = new EmpresaController();
        switch ($menu) {
            case 'empresa-dashboard':
                $empresaManage->renderDashboard($this->engine);
                exit;
            case 'create-oferta':
                $ofertaManage->renderAddOferta($this->engine);
                exit;
            case 'mis-ofertas':
                $ofertaManage->renderMyOffers($this->engine);
                exit;
            default:
                header('Location: ?menu=empresa-dashboard'); 
                exit;
        }
    }
}