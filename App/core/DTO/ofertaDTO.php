<?php

namespace App\core\model;

class Oferta
{
    private $id;
    private $empresaId;
    public $ciclos = []; // Array de IDs de ciclos
    private $fechaCreacion;
    private $titulo;

    public function __construct($id, $empresaId, $ciclos, $fechaCreacion, $titulo)
    {
        $this->id = $id;
        $this->empresaId = $empresaId;
        $this->ciclos = $ciclos;
        $this->fechaCreacion = $fechaCreacion;
        $this->titulo = $titulo;
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