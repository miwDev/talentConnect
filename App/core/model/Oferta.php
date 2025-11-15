<?php

namespace App\core\model;

class Oferta
{
    private $id;
    private $empresaId;
    public $ciclos = []; // Array de IDs de ciclos
    private $fechaCreacion;
    private $fechaFin;
    private $salario;
    private $descripcion;
    private $titulo;
    private $solicitudes = [];

    public function __construct($id, $empresaId, $ciclos, $fechaCreacion, $fechaFin, $salario, $descripcion, $titulo)
    {
        $this->id = $id;
        $this->empresaId = $empresaId;
        $this->ciclos = $ciclos;
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