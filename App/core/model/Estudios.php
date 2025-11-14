<?php

namespace App\core\model;

class Estudio
{
    private $id;
    private $ciclo;
    private $fechaInicio;
    private $fechaFin;

    public function __construct($id, $ciclo, $fIni, $fFin)
    {
        $this->id = $id;
        $this->ciclo = $fIni;
        $this->fechaInicio = $fIni;
        $this->fechaFin = $fFin;
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
