<?php

namespace App\core\model;

class Solicitud
{
    private $id;
    private $fechaCreacion;
    private $alumno;
    private $oferta;
    private $finalizado; // borrador

    public function __construct($id, $fechaCreacion, $alumno, $oferta, $finalizado)
    {
        $this->id = $id;
        $this->fechaCreacion = $fechaCreacion;
        $this->alumno = $alumno;
        $this->oferta = $oferta;
        $this->finalizado = $finalizado;
    }

    public function __get($propiedad)
    {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        }
    }

    public function __set($propiedad, $value)
    {
        if (property_exists($this, $propiedad)) {
            $this->$propiedad = $value;
        }
        return $this;
    }
}
