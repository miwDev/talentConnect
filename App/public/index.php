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

use App\core\data\AlumnoRepo;
use App\core\data\EmpresaRepo;
use App\core\model\Alumno;
use App\core\model\Empresa;

// ===========================================
// PRUEBA: GUARDAR UN ALUMNO
// ===========================================

$alumno = new Alumno(
    null,                 // id
    'usuario_alumno',     // username
    '1234',               // password
    'Juan',               // nombre
    'Pérez',              // apellido
    '654321098',          // teléfono
    'Calle Falsa 123',    // dirección
    'foto.jpg',           // foto
    'cv.pdf',             // cv
    'Sevilla',            // provincia
    'Dos Hermanas'        // localidad
);

$alumnoId = AlumnoRepo::save($alumno);

echo "<h2>Resultado SAVE Alumno:</h2>";
var_dump($alumnoId);


// ===========================================
// PRUEBA: GUARDAR UNA EMPRESA
// ===========================================

$empresa = new Empresa(
    null,                 // id
    'usuario_empresa',    // username
    'abcd',               // password
    'TecnoSoft SL',       // nombre
    '955123456',          // teléfono
    'Av. de la Innovación 45', // dirección
    'Sevilla',            // provincia
    'Mairena del Aljarafe', // localidad
    'Laura Gómez',        // nombrePersona
    '600987654',          // telPersona
    'logo.png',           // logo
    1                     // validacion (1 = validado, 0 = no)
);

$empresaId = EmpresaRepo::save($empresa);

echo "<h2>Resultado SAVE Empresa:</h2>";
var_dump($empresaId);
