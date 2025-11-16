<?php

namespace App\core\model;

class Solicitud
{
    private $id;
    private $fechaCreacion;
    private $alumnoId;
    private $ofertaId;
    private $comentarios;
    private $finalizado;

    public function __construct($id, $fechaCreacion, $alumnoId, $ofertaId, $comentarios, $finalizado)
    {
        error_log("=== Solicitud Constructor ===");
        error_log("id: " . ($id ?? 'NULL'));
        error_log("fechaCreacion: " . ($fechaCreacion ?? 'NULL'));
        error_log("alumnoId: " . ($alumnoId ?? 'NULL'));
        error_log("ofertaId: " . ($ofertaId ?? 'NULL'));
        error_log("comentarios: " . ($comentarios ?? 'NULL'));
        error_log("finalizado: " . ($finalizado ?? 'NULL'));
        
        $this->id = $id;
        $this->fechaCreacion = $fechaCreacion;
        $this->alumnoId = $alumnoId;
        $this->ofertaId = $ofertaId;
        $this->comentarios = $comentarios;
        $this->finalizado = $finalizado;
        
        error_log("Solicitud object created successfully");
    }

    public function __get($propiedad)
    {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        }
        error_log("WARNING: Trying to access non-existent property: " . $propiedad);
        return null;
    }

    public function __set($propiedad, $value)
    {
        if (property_exists($this, $propiedad)) {
            $this->$propiedad = $value;
        }
        return $this;
    }
}