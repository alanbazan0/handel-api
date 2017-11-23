<?php
namespace clases;
use mysqli;


class AdministradorArchivos
{
   
    public function subir($carpeta,$archivo)
    {
        $resultado = new Resultado();
        if(file_exists($carpeta) || @mkdir($carpeta))
        {           
            $origen=$archivo["tmp_name"];
            $destino=$carpeta.$archivo["name"];   
            
            if(copy($origen, $destino))
            {
                $resultado->Valor ="OK";            
            }
               
        }
        return $resultado;
    }
    
   
    
}

