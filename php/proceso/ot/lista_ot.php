<?php
require_once("../../conexion.php");
$idEquipo = @$_POST["idEquipo"];
$idContrato = @$_POST["idContrato"];
if ($idContrato == "false") {
    $conOts = "SELECT CONTR_descripcion,PROY_descripcion,ORTR_id,ORTR_tipo_evento,ORTR_medicion,DATE(ORTR_f_creacion) as fCreacion,ORTR_estado   FROM ordenes_trabajo ot INNER JOIN equipos_contrato ec ON ot.EQCO_id01=ec.EQCO_id
LEFT JOIN insumos_ordenes oi ON ot.ORTR_id=oi.ORTR_id01 INNER JOIN contratos c ON ec.CONTR_id01=c.CONTR_id INNER JOIN proyectos p ON c.PROY_id01=p.PROY_id WHERE EQU_id01 = $idEquipo GROUP BY ORTR_id";

} else {
    $fInicio = (isset($_POST["fInicio"])) ? $_POST["fInicio"] : false;
    $fFinal = (isset($_POST["fFinal"])) ? $_POST["fFinal"] : false;;
    $condicionalESecudanrio = "";

    $condicionalFecha = ($fFinal == false || $fInicio == false)
        ? "AND ORTR_f_creacion BETWEEN concat(year(now()),'-01-01') AND concat(year(now()),'-12-31')"
        : "AND ORTR_f_creacion BETWEEN '$fInicio' AND '$fFinal'";

    $conOts = "SELECT CONTR_descripcion,PROY_descripcion,ORTR_id,ORTR_tipo_evento,ORTR_medicion,DATE(ORTR_f_creacion) as fCreacion,ORTR_estado   FROM ordenes_trabajo ot INNER JOIN equipos_contrato ec ON ot.EQCO_id01=ec.EQCO_id
LEFT JOIN insumos_ordenes oi ON ot.ORTR_id=oi.ORTR_id01 INNER JOIN contratos c ON ec.CONTR_id01=c.CONTR_id INNER JOIN proyectos p ON c.PROY_id01=p.PROY_id WHERE (EQU_id01 = $idEquipo AND CONTR_id01=$idContrato) $condicionalFecha GROUP BY ORTR_id";
}
$resConOt = mysqli_query($conexion,$conOts);
?>

<div class="table-responsive">
    <table id="tabla_lista_ot_equipo" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Proyecto</th>
                <th>Contrato</th>
                <th>Evento</th>
                <th>klmt/hrm</th>
                <th>F. creación</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($resConOt as $x) :
                $datosEquiContrato = $x["ORTR_id"]; ?>
                <tr>
                    <td><?php echo $x["ORTR_id"] ?></td>
                    <td><?php echo $x["PROY_descripcion"] ?></td>
                    <td><?php echo $x["CONTR_descripcion"] ?></td>
                    <td><?php echo $x["ORTR_tipo_evento"] ?></td>
                    <td><?php echo $x["ORTR_medicion"] ?></td>
                    <td><?php echo $x["fCreacion"] ?></td>
                    <td><?php echo $x["ORTR_estado"] ?></td>
                    <td class="text-center">
                        <a href="#" data-idotpdf="<?php echo $x['ORTR_id'] ?>" onclick="verPdfOrdenTrabajo(this)">
                            <i class="fas fa-file-pdf text-dark"></i>
                        </a>
                        <a href="#" class="ListaPopover" data-bs-toggle="modal" data-bs-target="#modalActOt" onclick="llenarDatosOTAct('<?php echo $datosEquiContrato ?>')">
                            <i class="fas fa-edit text-dark"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>