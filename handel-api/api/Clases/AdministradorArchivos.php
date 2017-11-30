<?php
namespace clases;
use mysqli;


class AdministradorArchivos
{
   
    public function subir($carpeta,$archivo)
    {
        $resultado = new Resultado();
        if($archivo!=null)
        {
            if(file_exists($carpeta) || @mkdir($carpeta))
            {           
                $origen=$archivo["tmp_name"];
                
                if($origen!=null && $origen!="")
                {
                    $destino=$carpeta.$archivo["name"];   
                    
                    if(copy($origen, $destino))
                    {
                        $resultado->Valor ="OK";            
                    }
                }
                else 
                {
                   
                    $resultado->MensajeError = "El archivo excede el tamaño maximo permitido. ";
                }
                   
            }
        }
        else
            $resultado->Valor ="OK"; 
        return $resultado;
    }
    
   
    
}

