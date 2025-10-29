<?php

namespace App\core\model;

class Ciclo
{
    private $id;
    private $nombre;
    private $nivel;
    private $familia;

    public function __construct($id, $nombre, $nivel, $familia)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->familia = $familia;
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
