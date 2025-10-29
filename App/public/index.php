<?php

require __DIR__ . '/../vendor/autoload.php';

$templatesPath = __DIR__ . '/../core/view/templates';

$templates = new League\Plates\Engine($templatesPath);

try {
    echo $templates->render('pages/listadoAlumnos');
} catch (\League\Plates\Exception\TemplateNotFound $e) {
    http_response_code(404);
    echo "Error 404: Plantilla 'home' no encontrada. Detalles: " . $e->getMessage();
}
