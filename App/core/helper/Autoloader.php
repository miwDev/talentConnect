<?php

spl_autoload_register(function ($clase) {

    // Convertir App\core\model\Alumno -> core/model/Alumno.php
    $clase = str_replace('App\\', '', $clase);
    $clase = str_replace('\\', '/', $clase);

    // Desde helper/ subimos dos niveles: helper -> core -> App
    $fichero = __DIR__ . '/../../' . $clase . '.php';

    if (file_exists($fichero)) {
        require_once($fichero);
    } else {
        // Debug: ver qué está buscando
        echo "<!-- NO ENCONTRADO: $fichero -->";
    }
});
