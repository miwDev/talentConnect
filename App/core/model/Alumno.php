<?php

namespace App\core\model;

class Alumno
{
    private $id;
    private $username;
    private $password;
    private $nombre;
    private $apellido;
    private $telefono;
    private $provincia;
    private $localidad;
    private $direccion;
    private $foto;
    private $cv;

    // arrays

    private $estudios = [];
    private $solicitudes = [];

    public function __construct($id, $username, $pass, $nom, $ape, $tel, $direccion, $foto, $cv, $prov, $loc)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $pass;
        $this->nombre = $nom;
        $this->apellido = $ape;
        $this->telefono = $tel;
        $this->direccion = $direccion;
        $this->foto = $foto;
        $this->cv = $cv;
        $this->provincia = $prov;
        $this->localidad = $loc;
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
