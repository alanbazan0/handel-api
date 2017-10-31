<?php
namespace php\Interfaces;
use php\modelos\Usuario;

interface IUsuariosRepositorio
{   
    //data mapper
    public function insertar(Usuario $usuario);
    public function actualizar(Usuario $usuario);
    public function eliminar(Usuario $usuario);
    
    //query object
    public function consultarPorIdContrasena($id, $contrasena);
}

