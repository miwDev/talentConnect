<?php

namespace App\core\DTO;

class EmpresaDTO
{
    private $id;
    private $cif;
    private $nombre;
    private $email;
    private $telefono;
    private $logo;
    private $validated;

    public function __construct($id, $cif, $nom, $email, $tel, $logo, $val)
    {
        $this->id = $id;
        $this->cif = $cif;
        $this->nombre = $nom;
        $this->email = $email;
        $this->telefono = $tel;
        $this->logo = $logo;
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
