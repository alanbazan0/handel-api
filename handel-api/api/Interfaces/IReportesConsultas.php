<?php
namespace api\Interfaces;

interface IReportesConsultas
{ 
    public function consultarEvidenciasEnviadasEmpleadoMes($empleadoId, $mes);
    public function consultarEvidenciasJustificadasEmpleadoMes($empleadoId, $mes);
    public function cunsultarProcedimientosAsignadosEmpleadoMes($empleadoId, $mes);
}

