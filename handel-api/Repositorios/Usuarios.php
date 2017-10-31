<?php
use php\clases\AdministradorConexion;
use php\repositorios\UsuariosRepositorio;

error_reporting(E_ALL);
ini_set('display_errors', 1);


include '../Clases/Utilidades.php';
include '../Clases/AdministradorConexion.php';
include '../repositorios/UsuariosRepositorio.php';



header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

$administrador_conexion = new AdministradorConexion();

$conexion;
try
{
    $conexion = $administrador_conexion->abrir();
    if($conexion)
    {
        $accion = REQUEST('accion');
        $repositorio = new UsuariosRepositorio($conexion);
        switch ($accion)
        {        
            case 'consultarPorIdContrasena':
                $id = REQUEST('id');
                $contrasena = REQUEST('contrasena');
                $usuario = $repositorio->consultarPorIdContrasena($id, $contrasena) ;     
                if($usuario!=null)
                    echo json_encode($usuario);
            break;
        }
    }
        
}
catch(Exception $e)
{
    echo $e->getMessage(), '\n';
}
finally
{
    $administrador_conexion->cerrar($conexion);    
}


