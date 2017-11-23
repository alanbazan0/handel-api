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
                    $resultado->Valor = "OK";                                       
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
    
    public function consultarUltimaEvidenciaId()
    {
        $resultado = new Resultado();
        $consulta = "SELECT DISTINCT MAX(Ev_id) evidenciaId FROM Evidencia";
        if($sentencia = $this->conexion->prepare($consulta))
        {
            if($sentencia->execute())
            {
                if ($sentencia->bind_result($evidenciaId))
                {
                    if($sentencia->fetch())
                    {                        
                        $resultado->Valor = $evidenciaId;
                    }
                    else
                        $resultado->MensajeError = "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;
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
    
    public function insertarDetalle($evidencia,$nombreArchivo)
    {
        $resultado = $this->consultarUltimaEvidenciaId();
        if($resultado->MensajeError=="" && $resultado->Valor>0)
        {
            $evidenciaId = $resultado->Valor;
            $consulta = "INSERT INTO Det_evidencia(Ev_id, Dev_id, Dev_imagen, Dev_observacion, Ju_id, Dev_realizado) " .
                         "VALUES(?, 1, ?, ?, ?, ?)";
            
            if($sentencia = $this->conexion->prepare($consulta))
            {
                if($sentencia->bind_param("issis",$evidenciaId,$nombreArchivo,$evidencia->Comentarios, $evidencia->JustificacionId, $evidencia->Realizado))
                {
                    if($sentencia->execute())
                    {
                        $resultado->Valor = "OK";
                    }
                    else
                        $resultado->MensajeError = "Falló la ejecución (" . $this->conexion->errno . ") " . $this->conexion->error;
                }
                else
                    $resultado->MensajeError = "Falló el enlace de parámetros";
            }
            else
                $resultado->MensajeError = "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;  
        }               
        return $resultado;
    }
    
}