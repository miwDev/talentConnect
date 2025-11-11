<?php

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../core/helper/Autoloader.php';

use App\core\controller\Router;

$router = new Router();
$router->router();
