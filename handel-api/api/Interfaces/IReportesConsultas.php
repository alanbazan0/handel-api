<?php
namespace api\Interfaces;

interface IReportesConsultas
{ 
    public function consultarReporteEvidenciasEmpleadoMes($empleadoId, $mes);
    public function consultarJustificacionesEmpleadoMes($empleadoId, $mes);
    
}

