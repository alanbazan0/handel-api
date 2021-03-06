<?php
use clases\AdministradorConexion;
use clases\Resultado;
use clases\AdministradorCorreo;

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Clases/Utilidades.php';
include '../Clases/AdministradorConexion.php';
include '../Clases/AdministradorCorreo.php';
include '../Clases/Resultado.php';

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
        
        switch ($accion)
        {        
            case 'enviar':   
                $nombre = REQUEST('nombre');
                $correoElectronico = REQUEST('correoElectronico');
                $telefono = REQUEST('telefono');
                $mensaje = REQUEST('mensaje');                    
                $adminstradorCorreo = new AdministradorCorreo();
                $resultado = $adminstradorCorreo->enviarContacto($nombre,$correoElectronico, $telefono, $mensaje);
              
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


