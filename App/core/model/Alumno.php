<?php

namespace App\core\model;

class Alumno
{
    private $id;
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

    public function __construct($id, $nom, $ape, $tel, $prov, $loc, $direccion, $foto, $cv)
    {
        $this->id = $id;
        $this->nombre = $nom;
        $this->apellido = $ape;
        $this->telefono = $tel;
        $this->provincia = $prov;
        $this->localidad = $loc;
        $this->direccion = $direccion;
        $this->foto = $foto;
        $this->cv = $cv;
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
