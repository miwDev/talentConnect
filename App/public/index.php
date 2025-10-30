<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/helper/Autoloader.php';


// $templatesPath = __DIR__ . '/../core/view/templates';

// $templates = new League\Plates\Engine($templatesPath);

// try {
//     echo $templates->render('pages/listadoAlumnos');
// } catch (\League\Plates\Exception\TemplateNotFound $e) {
//     http_response_code(404);
//     echo "Error 404: Plantilla 'home' no encontrada. Detalles: " . $e->getMessage();
// }

// index.php

use App\core\data\OfertaRepo;
use App\core\data\SolicitudRepo;
use App\core\model\Alumno;
use App\core\model\Empresa;
use App\core\model\Oferta;
use App\core\model\Solicitud;

// ===========================================
// DATOS BASE: empresa y alumno existentes
// ===========================================

// Sustituye estos IDs por los que insertaste antes
$empresa = new Empresa(1, null, null, 'TecnoSoft SL', null, null, null, null, null, null, null, 1);
$alumno = new Alumno(2, null, null, 'Juan', 'Pérez', null, null, null, null, null, null);

// ===========================================
// 1️⃣ CREAR UNA OFERTA
// ===========================================

$oferta = new Oferta(
    null,                // id
    $empresa,            // empresa (objeto Empresa)
    null,                // fecha_creacion (la pone la BD con CURRENT_DATE)
    '2025-12-31',        // fecha_fin
    25000,               // salario
    'Desarrollo de software en PHP y JavaScript', // descripción
    'Programador Junior' // título
);

$ofertaId = OfertaRepo::save($oferta);

echo "<h2>Resultado SAVE Oferta:</h2>";
var_dump($ofertaId);


// ===========================================
// 2️⃣ CREAR UNA SOLICITUD A ESA OFERTA
// ===========================================

if ($ofertaId) {
    // Asignamos el ID devuelto a la oferta
    $oferta->__set('id', $ofertaId);

    $solicitud = new Solicitud(
        null,        // id
        null,        // fecha_creacion (la pone la BD con CURRENT_DATE)
        $alumno,     // alumno (objeto Alumno)
        $oferta,     // oferta (objeto Oferta)
        0        // finalizado (0 = no finalizado)
    );

    $solicitudId = SolicitudRepo::save($solicitud);

    echo "<h2>Resultado SAVE Solicitud:</h2>";
    var_dump($solicitudId);
} else {
    echo "<p style='color:red'>❌ No se pudo crear la oferta. No se probará la solicitud.</p>";
}
