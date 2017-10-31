<?php
namespace repositorios;

use interfaces\IUsuariosConsultas;



include "../Interfaces/IUsuariosConsultas.php";
include "../modelos/Usuario.php";


class UsuariosRepositorio implements IUsuariosConsultas
{    
    protected $conexion;   
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }
    
  

    public function consultarPorIdContrasena($id, $contrasena)
    {       
        $usuario = null;
        $consulta = "SELECT BTUSUARIOID id, BTUSUARIONCOMPLETO nombre " .
                    "FROM BTUSUARIO " .
                    "WHERE BTUSUARIOID = ? AND BTUSUARIOPSW = ?";
        if($sentencia = $this->conexion->prepare($consulta))
        {   
            $sentencia->bind_param("ss",$id,$contrasena);
            if($sentencia->execute())
            {
                
                if ($sentencia->bind_result($id, $nombre))
                {           
                    
                    if ($row = $sentencia->fetch())
                    {
                        $usuario = (object) [
                            'id' =>  utf8_encode($id),
                            'nombre' => utf8_encode($nombre)                           
                        ];  
                                     
                    }
                }
              
            }
           
        }       
        else
            echo "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;
        return $usuario;
    }    
    
}