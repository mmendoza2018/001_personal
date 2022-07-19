<?php
require_once("../../conexion.php");
require_once("obtenerMedicionPrincipal.php");

$idEquipo = @$_POST["idEquipo"];
$idContrato = @$_POST["idContrato"];
if ($idContrato == "false") {
    $conParametrosDiarios = "SELECT CONTR_descripcion,TRAB_nombres,PROY_descripcion,PADI_id,PADI_medicion_digital,PADI_medicion_analogico,PADI_medicion_kilometraje,EQCO_tipo_medicion,PADI_man_siguiente,PADI_horas_trabajo,DATE(PADI_fecha_tareo) as fecha FROM parametros_diarios pd 
LEFT JOIN trabajadores t ON t.TRAB_id=pd.TRAB_id01 
INNER JOIN equipos_contrato ec ON ec.EQCO_id=pd.EQCO_id01 
INNER JOIN contratos c ON ec.CONTR_id01=c.CONTR_id 
INNER JOIN proyectos p ON c.PROY_id01=p.PROY_id 
WHERE EQU_id01 = $idEquipo";
} else {
    $fInicio = @$_POST["fInicio"];
    $hoy = @$_POST["hoy"];

    $condicionalFecha = ($hoy == " " || $fInicio == " ")
        ? "AND DATE(PADI_fecha_tareo) BETWEEN concat(year(now()),'-01-01') AND concat(year(now()),'-12-31')"
        : "AND DATE(PADI_fecha_tareo) BETWEEN '$fInicio' AND  '$hoy'";
    $conParametrosDiarios = "SELECT CONTR_descripcion,TRAB_nombres,PROY_descripcion,PADI_id,PADI_medicion_digital,PADI_medicion_analogico,PADI_medicion_kilometraje,EQCO_tipo_medicion,PADI_man_siguiente,PADI_horas_trabajo,DATE(PADI_fecha_tareo) as fecha FROM parametros_diarios pd 
LEFT JOIN trabajadores t ON t.TRAB_id=pd.TRAB_id01 
INNER JOIN equipos_contrato ec ON ec.EQCO_id=pd.EQCO_id01 
INNER JOIN contratos c ON ec.CONTR_id01=c.CONTR_id 
INNER JOIN proyectos p ON c.PROY_id01=p.PROY_id 
WHERE EQU_id01 = $idEquipo AND CONTR_id01 = $idContrato $condicionalFecha";
}
$resParametrosDiarios = mysqli_query($conexion, $conParametrosDiarios)
?>

<div class="table-responsive">
    <table id="tabla_lista_pd_equipo" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Proyecto</th>
                <th>Contrato</th>
                <th>klmt/hrm</th>
                <th>Mntto. Sig</th>
                <th>H. trabajo</th>
                <th>Operador</th>
                <th>Fecha tareo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($resParametrosDiarios as $x) :
                $medicionActual = getMedicionPrincipal (
                    $x["EQCO_tipo_medicion"],
                    $x["PADI_medicion_digital"],
                    $x["PADI_medicion_analogico"],
                    $x["PADI_medicion_kilometraje"],
                   ) ?>
                <tr>
                    <td><?php echo $x["PADI_id"] ?></td>
                    <td><?php echo $x["PROY_descripcion"] ?></td>
                    <td><?php echo $x["CONTR_descripcion"] ?></td>
                    <td><?php echo $medicionActual ?></td>
                    <td><?php echo $x["PADI_man_siguiente"] ?></td>
                    <td><?php echo $x["PADI_horas_trabajo"] ?></td>
                    <td><?php echo $x["TRAB_nombres"] ?></td>
                    <td><?php echo $x["fecha"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>