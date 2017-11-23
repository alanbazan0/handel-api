<?php
use Clases\AdministradorConexion;
use Clases\Resultado;
use Repositorios\ProcedimientosRepositorio;


error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Clases/Utilidades.php';
include '../Clases/AdministradorConexion.php';
include '../Clases/Resultado.php';
include '../Repositorios/ProcedimientosRepositorio.php';

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
        $repositorio = new ProcedimientosRepositorio($conexion);
        switch ($accion)
        {        
            case 'consultarPorEmpleado':
                $empleadoId = REQUEST('empleadoId');              
                $resultado = $repositorio->consultarPorEmpleado($empleadoId);   
               
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


