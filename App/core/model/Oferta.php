<?php

namespace App\core\model;

class Oferta
{
    private $id;
    private $empresa;
    private $fechaCreacion;
    private $fechaFin;
    private $salario;
    private $descripcion;
    private $titulo;

    // arrays
    private $solicitudes = [];

    public function __construct($id, $empresa, $fechaCreacion, $fechaFin, $salario, $descripcion, $titulo)
    {
        $this->id = $id;
        $this->empresa = $empresa;
        $this->fechaCreacion = $fechaCreacion;
        $this->fechaFin = $fechaFin;
        $this->salario = $salario;
        $this->descripcion = $descripcion;
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
