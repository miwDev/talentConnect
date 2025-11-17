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
        
        $this->id = $id;
        $this->fechaCreacion = $fechaCreacion;
        $this->alumnoId = $alumnoId;
        $this->ofertaId = $ofertaId;
        $this->comentarios = $comentarios;
        $this->finalizado = $finalizado;
        
    }

    public function __get($propiedad)
    {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        }
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