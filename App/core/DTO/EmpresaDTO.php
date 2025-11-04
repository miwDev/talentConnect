<?php

namespace App\core\DTO;

class EmpresaDTO
{
    private $id;
    private $nombre;
    private $email;
    private $telefono;
    private $validated;

    public function __construct($id, $nom, $email, $tel, $val)
    {
        $this->id = $id;
        $this->nombre = $nom;
        $this->email = $email;
        $this->telefono = $tel;
        $this->validated = $val;
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
