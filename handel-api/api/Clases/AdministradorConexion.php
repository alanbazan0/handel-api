<?php
namespace clases;
use mysqli;


class AdministradorConexion
{
    private $servidor = "alanbazan.com.mx";
    private	$basedatos = "handel_db";
    private	$usuario = "handelscenet";
    private	$contrasena ="NOem0307!";
    public function abrir()
    {
        return new mysqli($this->servidor,$this->usuario,$this->contrasena,$this->basedatos);
    }
    
    public function cerrar($connection)
    {
        if($connection)         
            $connection->close();
    }
    
}

