<?php

namespace App\core\DTO;


class AlumnoDTO
{

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $foto;
    public $confirmed;

    public function __construct($id, $nom, $ape, $email, $foto, $conf)
    {
        $this->id = $id;
        $this->nombre = $nom;
        $this->apellido = $ape;
        $this->email = $email;
        $this->foto = $foto;
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
