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
        $consulta = "SELECT U.Us_id id, Us_mail correoElectronico, U.Emp_id empleadoId, Emp_nombre nombre, Emp_apellido_paterno apellidoPaterno, EM.Em_id empresaId, Em_nombre nombreEmpresa, S.Es_id sedeId, A.Ea_id areaId, Up_opcion permiso " .
                    "FROM Usuarios U " .
                    "   INNER JOIN Empleado E ON U.Emp_id = E.Emp_id " .
                    "   INNER JOIN Empresa EM ON E.Em_id = EM.Em_id " .
                    "   INNER JOIN Empresa_area A ON A.Ea_id = E.Ea_id " .
                    "   INNER JOIN Empresa_sede S ON S.Es_id = E.Es_id " .
                    "   INNER JOIN Usuario_permiso P ON P.Us_id = U.Us_id " .
                    "WHERE Us_mail = ? AND Us_contrasena = ?";
       
        if($sentencia = $this->conexion->prepare($consulta))
        {   
            if($sentencia->bind_param("ss",$credenciales->id,$credenciales->contrasena))
            {
                if($sentencia->execute())
                {                
                    if ($sentencia->bind_result($id, $correoElectronico, $empleadoId, $nombre, $apellidoPaterno, $empresaId, $nombreEmpresa, $sedeId, $areaId, $permiso))
                    {                               
                        if ($sentencia->fetch())
                        {
                            $usuario = (object) [
                                'Id' =>  utf8_encode($id),
                                'CorreoElectronico' => utf8_encode($correoElectronico),
                                'EmpleadoId' => utf8_encode($empleadoId),
                                'Nombre' => $nombre,
                                'ApellidoPaterno' => $apellidoPaterno,
                                'EmpresaId' => utf8_encode($empresaId),
                                'NombreEmpresa' => $nombreEmpresa,
                                'SedeId' => utf8_encode($sedeId),
                                'AreaId' => utf8_encode($areaId),
                                'Permiso' => $permiso
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