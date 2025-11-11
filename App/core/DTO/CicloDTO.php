<?php

namespace App\core\DTO;


class CicloDTO
{

    public $id;
    public $nombre;

    public function __construct($id, $nom)
    {
        $this->id = $id;
        $this->nombre = $nom;
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
