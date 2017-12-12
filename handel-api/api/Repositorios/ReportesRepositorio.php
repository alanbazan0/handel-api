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
    
    public function reporteEvidenciasEmpleadoMes($empleadoId, $mes)
    {
        $resultado = new Resultado();
        $consulta = "SELECT 'EvidenciasEnviadas' as nombre, COUNT(Ev_id) as valor " .
                    "FROM Evidencia " .
                    "WHERE Emp_id= ? " .
                    "AND Ev_fecha >=  CONCAT( YEAR( NOW( ) ) ,  '/', MONTH( NOW( ) ) ,  '/',  '01' ) " .
                    "AND Ev_fecha <= CONCAT( YEAR( NOW( ) ) ,  '/', MONTH( NOW( ) ) ,  '/',  '30' ) " .
                    "UNION " .
                    "SELECT 'ProcedimientosAsignados ' as nombre, COUNT( Pr_emp ) AS valor " .
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
                    "AND Ev_fecha >=  CONCAT( YEAR( NOW( ) ) ,  '/', MONTH( NOW( ) ) ,  '/',  '01' ) " .
                    "AND Ev_fecha <= CONCAT( YEAR( NOW( ) ) ,  '/', MONTH( NOW( ) ) ,  '/',  '30' )";
        
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
    
    public function consultarEvidenciasJustificadasEmpleadoMes($empleadoId, $mes)
    {
        
    }

    public function cunsultarProcedimientosAsignadosEmpleadoMes($empleadoId, $mes)
    {}

    public function consultarEvidenciasEnviadasEmpleadoMes($empleadoId, $mes)
    {}

  
    
  

    
}