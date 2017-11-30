<?php
use clases\AdministradorConexion;
use clases\AdministradorArchivos;
use clases\Resultado;
use repositorios\EvidenciasRepositorio;
use clases\AdministradorCorreo;



error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Clases/Utilidades.php';
include '../Clases/AdministradorConexion.php';
include '../Clases/AdministradorArchivos.php';
include '../Clases/AdministradorCorreo.php';
include '../Clases/Resultado.php';
include '../Repositorios/EvidenciasRepositorio.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

$administradorConexion = new AdministradorConexion();

$conexion;
$resultado = new Resultado();
try
{
    $conexion = $administradorConexion->abrir();
    if($conexion)
    {
        $accion = REQUEST('accion');
        $repositorio = new EvidenciasRepositorio($conexion);
        switch ($accion)
        {        
            case 'insertar':
                $archivo =
                $evidencia = json_decode(REQUEST('evidencia'));                   
                $resultado = $repositorio->insertar($evidencia);   
                if($resultado->MensajeError=="")
                {
                    $adminstradorArchivos = new AdministradorArchivos();
                    $archivo = FILES("archivo");            
                    $carpeta = "../../uploads/";
                    $resultado = $adminstradorArchivos->subir($carpeta,$archivo);
                    if($resultado->MensajeError=="")
                    {                    
                        $nombreArchivo = "";
                        if($archivo!=null)
                            $nombreArchivo = $archivo["name"];
                        $resultado = $repositorio->insertarDetalle($evidencia,$nombreArchivo);   
                        if($resultado->MensajeError=="")
                        {                            
                           $adminstradorCorreo = new AdministradorCorreo();
                           $resultado = $adminstradorCorreo->enviarRecordatorio($evidencia->CorreoElectronico, $evidencia->NombreUsuario, $evidencia->NombreProcedimiento, $archivo["name"]);
                        }
                    }
                }
               
            break;
            default:
                $resultado->MensajeError = "Acción no válida";
            break;
        }
        
    }
        
}
catch(Exception $e)
{
    $resultado->MensajeError = $e->getMessage();
}
finally
{
    if($resultado!=null)
        echo json_encode($resultado);
    $administradorConexion->cerrar($conexion);    
}


