<?php

namespace App\core\DTO;


class AlumnoDTO
{

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $confirmed;

    public function __construct($id, $nom, $ape, $email, $conf)
    {
        $this->id = $id;
        $this->nombre = $nom;
        $this->apellido = $ape;
        $this->email = $email;
        $this->confirmed = $conf;
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
