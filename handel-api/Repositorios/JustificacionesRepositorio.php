<?php
namespace repositorios;


use Clases\Resultado;
use Interfaces\IJustificacionesConsultas;

include "../Interfaces/IJustificacionesConsultas.php";

class JustificacionesRepositorio implements IJustificacionesConsultas
{    
    protected $conexion;   
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }  
  
    public function consultar()
    {       
        $resultado = new Resultado();     
        $consulta = "SELECT Ju_id Id, Ju_desc Nombre " .
                    "FROM Justificacion ";
                    
        if($sentencia = $this->conexion->prepare($consulta))
        {               
            if($sentencia->execute())
            {                
                if ($sentencia->bind_result($id, $nombre))
                {     
                    $registros = array();
                    while($sentencia->fetch())
                    {
                        $registro = (object) [
                            'Id' =>  utf8_encode($id),                          
                            'Nombre' => $nombre 
                        ];    
                        array_push($registros, $registro);
                    }
                    $resultado->Valor = $registros;
                }
                else
                    $resultado->MensajeError = "Falló el enlace del resultado";
            }
            else
                $resultado->MensajeError = "Falló la ejecución (" . $this->conexion->errno . ") " . $this->conexion->error;                    
        }       
        else
            $resultado->MensajeError = "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;  
        return $resultado;
    }    
    
}