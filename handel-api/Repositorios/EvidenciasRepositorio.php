<?php
namespace repositorios;

use interfaces\IEvidenciasMapeoDatos;
use clases\Resultado;

include "../Interfaces/IEvidenciasMapeoDatos.php";

class EvidenciasRepositorio implements IEvidenciasMapeoDatos
{    
    protected $conexion;   
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }
    
  

    public function insertar($evidencia)
    {       
        $resultado = new Resultado();
        $resultado->Valor="NO_OK";
       
        $consulta = "INSERT INTO Evidencia(Ev_fecha,Em_id, Es_id,Ea_id,Pr_ID,Emp_id)" .
                    "VALUES(NOW(),?,?,?,?,?)";
       
        if($sentencia = $this->conexion->prepare($consulta))
        {   
            if($sentencia->bind_param("iiiii",$evidencia->EmpresaId,$evidencia->SedeId,$evidencia->AreaId, $evidencia->ProcedimientoId, $evidencia->EmpleadoId))
            {
                if($sentencia->execute())
                {                
                   $resultado->Valor="OK";
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