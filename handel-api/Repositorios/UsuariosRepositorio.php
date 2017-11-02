<?php
namespace repositorios;

use interfaces\IUsuariosConsultas;
use clases\Resultado;

include "../Interfaces/IUsuariosConsultas.php";

class UsuariosRepositorio implements IUsuariosConsultas
{    
    protected $conexion;   
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }
    
  

    public function consultarAcceso($credenciales)
    {       
        $resultado = new Resultado();
        $consulta = "SELECT Us_id id, Us_mail correoElectronico " .
                    "FROM Usuarios " .
                    "WHERE Us_mail = ? AND Us_contrasena = ?";
       
        if($sentencia = $this->conexion->prepare($consulta))
        {   
            if($sentencia->bind_param("ss",$credenciales->id,$credenciales->contrasena))
            {
                if($sentencia->execute())
                {                
                    if ($sentencia->bind_result($id, $correoElectronico))
                    {                               
                        if ($sentencia->fetch())
                        {
                            $usuario = (object) [
                                'id' =>  utf8_encode($id),
                                'correoElectronico' => utf8_encode($correoElectronico)                           
                            ];    
                            $resultado->valor = $usuario;
                        }
                        else
                            $resultado->mensajeError = "La combinación de usuario y contraseña es incorrecta.";
                    }
                    else
                        $resultado->mensajeError = "Falló el enlace del resultado";
                }
                else
                    $resultado->mensajeError = "Falló la ejecución (" . $this->conexion->errno . ") " . $this->conexion->error;  
            }            
            else
                $resultado->mensajeError = "Falló el enlace de parámetros";             
        }       
        else
            $resultado->mensajeError = "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;  
        return $resultado;
    }    
    
}