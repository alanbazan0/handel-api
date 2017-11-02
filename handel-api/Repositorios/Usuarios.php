<?php
use clases\AdministradorConexion;
use clases\Resultado;
use repositorios\UsuariosRepositorio;


error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Clases/Utilidades.php';
include '../Clases/AdministradorConexion.php';
include '../Clases/Resultado.php';
include '../repositorios/UsuariosRepositorio.php';

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
        $repositorio = new UsuariosRepositorio($conexion);
        switch ($accion)
        {        
            case 'consultarAcceso':
                $credenciales = json_decode(REQUEST('credenciales'));              
                $resultado = $repositorio->consultarAcceso($credenciales);   
               
            break;
            default:
                $resultado->mensajeError = "Acción no válida";
            break;
        }
        
    }
        
}
catch(Exception $e)
{
    $resultado->mensajeError = $e->getMessage();
}
finally
{
    if($resultado!=null)
        echo json_encode($resultado);
    $administradorConexion->cerrar($conexion);    
}


