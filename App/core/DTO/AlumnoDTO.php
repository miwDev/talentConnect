<?php

namespace App\core\DTO;


class AlumnoDTO
{

    public $id;
    public $nombre;
    public $apellido;
    public $email;

    public function __construct($id, $nom, $ape, $email)
    {
        $this->id = $id;
        $this->nombre = $nom;
        $this->apellido = $ape;
        $this->email = $email;
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
