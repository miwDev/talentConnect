<?php

namespace App\core\model;

class Alumno
{
    private $id;
    private $username;
    private $password;
    private $nombre;
    private $apellido;
    private $dni;
    private $telefono;
    private $provincia;
    private $localidad;
    private $direccion;
    private $foto;
    private $cv;

    private $confirmed;

    // arrays

    private $estudios = [];
    private $solicitudes = [];

    public function __construct($id, $username, $pass, $nom, $ape, $dni, $tel, $direccion, $foto, $cv, $prov, $loc, $conf)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $pass;
        $this->nombre = $nom;
        $this->apellido = $ape;
        $this->dni = $dni;
        $this->telefono = $tel;
        $this->direccion = $direccion;
        $this->foto = $foto;
        $this->cv = $cv;
        $this->provincia = $prov;
        $this->localidad = $loc;
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
