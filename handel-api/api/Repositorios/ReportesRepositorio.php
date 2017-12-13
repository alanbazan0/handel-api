<?php
namespace repositorios;

use interfaces\IEvidenciasMapeoDatos;
use api\Interfaces\IReportesConsultas;
use clases\Resultado;

include "../Interfaces/IReportesConsultas.php";

class ReportesRepositorio implements IReportesConsultas
{    
    protected $conexion;   
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }
    
    public function consultarReporteEvidenciasEmpleadoMes($empleadoId, $mes)
    {
        $resultado = new Resultado();
        $consulta = "SELECT 'EvidenciasEnviadas' as nombre, COUNT(Ev_id) as valor " .
                    "FROM Evidencia " .
                    "WHERE Emp_id= ? " .
                    "AND MONTH(Ev_fecha)='" . $mes . "' and  YEAR(Ev_fecha) = YEAR(NOW( )) " .
                    "UNION " .
                    "SELECT 'ProcedimientosAsignados' as nombre, COUNT( Pr_emp ) AS valor " .
                    "FROM Empleado_procedimiento " .
                    "INNER JOIN Empleado ON Empleado.Emp_id = Empleado_procedimiento.Emp_id " .
                    "INNER JOIN Empresa ON Empresa.Em_id = Empleado.Em_id " .
                    "WHERE Empleado.Emp_id = ? " .
                    "AND Empleado_procedimiento.Pr_estatus='A' " .
                    "UNION " .
                    "SELECT 'Justificaciones' as nombre, COUNT( Ju_id ) AS valor " .
                    "FROM Evidencia " .
                    "INNER JOIN Det_evidencia ON Det_evidencia.Ev_id = Evidencia.Ev_id " .
                    "WHERE Evidencia.Emp_id = ? " .
                    "AND Ju_id > 0 " .
                    "AND MONTH(Ev_fecha)='" . $mes . "' and  YEAR(Ev_fecha) = YEAR(NOW())";
        
        if($sentencia = $this->conexion->prepare($consulta))
        {
            if($sentencia->bind_param("iii",$empleadoId,$empleadoId,$empleadoId))
            {
                if($sentencia->execute())
                {
                    if ($sentencia->bind_result($indicador, $valor))
                    {
                        $registros = array();
                        while($sentencia->fetch())
                        {
                            $registro = (object) [
                                'Nombre' =>  $indicador,
                                'Valor' => $valor
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
                $resultado->MensajeError = "Falló el enlace de parámetros";     
        }
        else
            $resultado->MensajeError = "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;
       return $resultado;
    }
    
    public function consultarJustificacionesEmpleadoMes($empleadoId, $mes)
    {
        $resultado = new Resultado();
        $consulta = "SELECT Ju_desc as nombre, " .
                    "(SELECT COUNT( Evidencia.Ev_id ) " .
                    "FROM Evidencia " .
                    "INNER JOIN Det_evidencia ON Det_evidencia.Ev_id = Evidencia.Ev_id " .
                    "WHERE Ju_id = J.Ju_id " .
                    "    AND Evidencia.Emp_id= ? " .
                    "AND MONTH(Ev_fecha)= ? and  YEAR(Ev_fecha) = YEAR(NOW( ))) as enviadas, (SELECT COUNT( Pr_emp ) " .
					"				FROM Empleado_procedimiento " .
					"				INNER JOIN Empleado ON Empleado.Emp_id = Empleado_procedimiento.Emp_id " .
					"				INNER JOIN Empresa ON Empresa.Em_id = Empleado.Em_id " .
					"				WHERE Empleado.Emp_id = ? " .
					"				AND Empleado_procedimiento.Pr_estatus='A' ) as asignadas " .
                    "FROM Justificacion J WHERE Ju_estatus='A'  " .
                    "GROUP BY Ju_id  " ;
        
        if($sentencia = $this->conexion->prepare($consulta))
        {
            if($sentencia->bind_param("isi",$empleadoId,$mes,$empleadoId))
            {
                if($sentencia->execute())
                {
                    if ($sentencia->bind_result($nombre, $enviadas,$asignadas))
                    {
                        $registros = array();
                        while($sentencia->fetch())
                        {
                            if($enviadas>0)
                                $valor = $enviadas * 100 / $asignadas;
                            else
                                $valor = 0;
                            $registro = (object) [
                                'Nombre' =>  $nombre,
                                'Valor' => $valor
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
                $resultado->MensajeError = "Falló el enlace de parámetros";
        }
        else
            $resultado->MensajeError = "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;
        return $resultado;
    }
  


  
    
  

    
}