<?php

namespace App\core\model;


class Empresa
{
    private $id;
    private $username;
    private $password;
    private $nombre;
    private $telefono;
    private $direccion;
    private $provincia;
    private $localidad;
    private $nombrePersona;
    private $telPersona;
    private $logo;
    private $validacion;

    //array

    private $ofertas = [];

    public function __construct($id, $username, $pass, $nombre, $telefono, $direccion, $provincia, $localidad, $nombrePersona, $telPersona, $logo, $validacion)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $pass;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->provincia = $provincia;
        $this->localidad = $localidad;
        $this->nombrePersona = $nombrePersona;
        $this->telPersona = $telPersona;
        $this->logo = $logo;
        $this->validacion = $validacion;
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
