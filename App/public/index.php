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

use App\core\data\FamiliaRepo;
use App\core\data\CicloRepo;
use App\core\model\Familia;
use App\core\model\Ciclo;

// ===========================================
// 3️⃣ CREAR UNA FAMILIA
// ===========================================
$familia = new Familia(
    null,         // id
    'Informática' // nombre
);

$familiaId = FamiliaRepo::save($familia);

echo "<h2>Resultado SAVE Familia:</h2>";
var_dump($familiaId);

if ($familiaId) {
    $familia->__set('id', $familiaId);

    // ===========================================
    // 4️⃣ CREAR CICLOS ASOCIADOS A ESA FAMILIA
    // ===========================================

    $ciclos = [
        new Ciclo(null, 'Desarrollo de Aplicaciones Web', 'medio', $familia),
        new Ciclo(null, 'Administración de Sistemas', 'superior', $familia),
        new Ciclo(null, 'Ciberseguridad', 'especializacion', $familia)
    ];

    foreach ($ciclos as $ciclo) {
        $cicloId = CicloRepo::save($ciclo);
        echo "<h3>Resultado SAVE Ciclo '{$ciclo->__get('nombre')}':</h3>";
        var_dump($cicloId);
        if ($cicloId) {
            $ciclo->__set('id', $cicloId);
        }
    }
} else {
    echo "<p style='color:red'>❌ No se pudo crear la familia. No se crearán ciclos.</p>";
}
