<?php
namespace repositorios;


use Clases\Resultado;
use Interfaces\IProcedimientosConsultas;

include "../Interfaces/IProcedimientosConsultas.php";

class ProcedimientosRepositorio implements IProcedimientosConsultas
{    
    protected $conexion;   
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }  
    public function codificar($cadena)
    {       
       // $utf8 = 'ÄÖÜ'; // file must be UTF-8 encoded
        $iso88591_1 = utf8_decode($utf8);
        $iso88591_2 = iconv('UTF-8', 'ISO-8859-1', $utf8);
        $iso88591_2 = mb_convert_encoding($utf8, 'ISO-8859-1', 'UTF-8');
        
        return $iso88591_2;
    }
    public function consultarPorEmpleado($empleadoId)
    {       
        $resultado = new Resultado();
      // $this-> conexion->query("SET NAMES utf8");
        $consulta = "SELECT Procedimiento.Pr_id id,Pr_codigo codigo, Pr_nombre nombre ".
                    "FROM Empleado_procedimiento ".
                    "JOIN Procedimiento ON Procedimiento.Pr_id = Empleado_procedimiento.Pr_id ".
                    "WHERE Empleado_procedimiento.Emp_id = ? AND Empleado_procedimiento.Pr_estatus =  'A' ".
                    "AND Procedimiento.Pr_id NOT ".
                    "IN ( ".
                    "SELECT Evidencia.Pr_id ".
                    "FROM Evidencia ".
                    "INNER JOIN Empleado_procedimiento ON Empleado_procedimiento.Pr_id = Evidencia.Pr_id ".
                    "WHERE Ev_fecha >= CONCAT( YEAR( NOW( ) ) ,  '/', MONTH( NOW( ) ) ,  '/',  '01' ) ".
                    "AND Ev_fecha <= CONCAT( YEAR( NOW( ) ) ,  '/', MONTH( NOW( ) ) ,  '/',  '29' ) ".
                    "AND Evidencia.Emp_id = Empleado_procedimiento.Emp_id)";
       
        if($sentencia = $this->conexion->prepare($consulta))
        {   
            if($sentencia->bind_param("i",$empleadoId))
            {
                if($sentencia->execute())
                {                
                    if ($sentencia->bind_result($id, $codigo, $nombre))
                    {     
                        $registros = array();
                        while($sentencia->fetch())
                        {
                            $registro = (object) [
                                'Id' =>  utf8_encode($id),
                                'Codigo' => utf8_encode($codigo),  
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
                $resultado->MensajeError = "Falló el enlace de parámetros";             
        }       
        else
            $resultado->MensajeError = "Falló la preparación: (" . $this->conexion->errno . ") " . $this->conexion->error;  
        return $resultado;
    }    
    
}