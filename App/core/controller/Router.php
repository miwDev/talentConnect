<?php

namespace App\core\controller;

use App\core\controller\AlumnoController;
use League\Plates\Engine;

class Router
{
    private $templatesPath;
    private $templates;

    public function __construct()
    {
        $this->templatesPath = __DIR__ . '/../../core/view/templates';
        $this->templates = new Engine($this->templatesPath);
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
                echo $this->templates->render('pages/home');
                break;

            case 'login':
                echo $this->templates->render('pages/login');
                break;

            case 'alumnos':
                echo $this->templates->render('pages/listadoAlumnos');
                break;

            default:
                http_response_code(404);
                echo "Página no encontrada";
                break;
        }
    }
}
