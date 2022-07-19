<?php
function obtenerIdEquipoMantenimeinto($conexion,$idEquipoMantenimiento)
{
    $conConfiguracion = "SELECT EQMA_configuracion,EQU_id01 FROM equipo_mantenimiento WHERE EQMA_id='$idEquipoMantenimiento' AND  EQMA_configuracion IS NOT NULL";
    $resConConfiguracion = mysqli_query($conexion, $conConfiguracion);
    if ($resConConfiguracion) {
        foreach ($resConConfiguracion as $x) {
            $configuracionEquipo = $x["EQMA_configuracion"];
            $idEquipo = $x["EQU_id01"];
            $conIdEquMant = "SELECT EQMA_id FROM equipo_mantenimiento em INNER JOIN tiempo_mantenimiento tm ON em.TIMA_id01=tm.TIMA_id WHERE TIMA_tiempo = '$configuracionEquipo' AND EQU_id01='$idEquipo' ";
            foreach (mysqli_query($conexion, $conIdEquMant) as $k) {
                $idEquipoMantenimiento = $k["EQMA_id"];
            }
        }
    }
    return $idEquipoMantenimiento;
}
