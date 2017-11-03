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
                                'Id' =>  utf8_encode($id),
                                'CorreoElectronico' => utf8_encode($correoElectronico)                           
                            ];    
                            $resultado->Valor = $usuario;
                        }
                        else
                            $resultado->MensajeError = "La combinación de usuario y contraseña es incorrecta.";
                    }
                    else
                        $resultado->MensajeError = "Falló el enlace del resultado";
                }
                else
                    $resultado->MensajeError = "Falló la ejecución (" . $this->conexion->errno . ") " . $this->conexion->error;  
            }            
            else
                $resultado->MensajeError = "Falló el enlace de parámetros";             
        }       
        else
            $resultado->MensajeError = "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;  
        return $resultado;
    }    
    
}