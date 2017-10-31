<?php
namespace php\clases;
use mysqli;


class AdministradorConexion
{
    private $servidor = "alanbazan.com.mx";
    private	$basedatos = "BSTNTRN";
    private	$usuario = "bastiaan";
    private	$contrasena ="WJi_4}Jr~0$$";
    public function abrir()
    {
        return new mysqli($this->servidor,$this->usuario,$this->contrasena,$this->basedatos);
    }
    
    public function cerrar($connection)
    {
        if($connection)
            //mysqli_close($connection);
            $connection->close();
    }
    
}

