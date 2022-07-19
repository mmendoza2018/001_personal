<?php
$idContrato = $_POST["idContrato"];
include_once("../../conexion.php");
$consulta =  "SELECT EQU_codigo,EQU_placa,FAM_descripcion,MOD_descripcion,EQCO_tipo_medicion,EQCO_fecha_cierre,EQCO_estado_contrato,EQCO_id,EQU_id01,CONTR_id01,EQU_id,EQCO_fecha_ingreso_contrato,PADI_medicion_digital, PADI_medicion_analogico,PADI_medicion_kilometraje 
FROM equipos_contrato ec 
LEFT JOIN parametros_diarios pd ON ec.EQCO_id = pd.EQCO_id01 
right JOIN equipos e ON ec.EQU_id01=e.EQU_id 
INNER JOIN familias f ON f.FAM_id=e.FAM_id01 
INNER JOIN modelos m ON m.MOD_id=e.MOD_id01 
WHERE CONTR_id01=$idContrato  AND EQCO_estado=1 AND EQU_principal=1 GROUP BY EQCO_id01 ORDER BY FAM_descripcion ASC";
$listadoEquipos = mysqli_query($conexion, $consulta);
$consultaConProy = "SELECT PROY_descripcion, CONTR_descripcion FROM contratos c INNER JOIN proyectos p ON c.PROY_id01 = p.PROY_id WHERE CONTR_id='$idContrato'";
foreach (mysqli_query($conexion,$consultaConProy) as $x) {
    $proyecto = $x["PROY_descripcion"];
    $contrato = $x["CONTR_descripcion"];
}
?>
<div class="container-fluid bg-white my-2 py-3">
    <div class="col-12 col-md-10 col-lg-8 mx-auto py-2 border-2 border-bottom border-top mb-2">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <span class="fw-bold">Proyecto: </span><span><?php echo $proyecto ?></span>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <span class="fw-bold">Contrato: </span><span><?php echo $contrato ?></span>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="tabla_listaEquiposC" class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Placa</th>
                    <th>Familia</th>
                    <th>modelo</th>
                    <th>B. hidraulico</th>
                    <th>medición</th>
                    <th>F. ingreso</th>
                    <th>F. Cierre</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($listadoEquipos as $x) :
                    $codigoE2 = null;
                    $arrayEstado = ($x["EQCO_estado_contrato"] == 1) ? ["bg-success-opacity", "Activo"] : ["bg-secondary-opacity", "Inactivo"];
                    $datosEquipo = $x["EQCO_id"] . "|" . $x["EQU_id01"] . "|" . $x["CONTR_id01"] . "|" . $x["EQU_codigo"];
                    $consultaE2 =  "SELECT EQU_codigo,EQU_placa,FAM_descripcion,MOD_descripcion,EQCO_tipo_medicion,EQCO_fecha_cierre,EQCO_estado_contrato,EQCO_id,EQU_id01,CONTR_id01,EQU_id FROM equipos_contrato ec INNER JOIN equipos e ON ec.EQU_id01=e.EQU_id INNER JOIN familias f ON f.FAM_id=e.FAM_id01 INNER JOIN modelos m ON m.MOD_id=e.MOD_id01 INNER JOIN contratos c ON ec.CONTR_id01=c.CONTR_id  WHERE EQU_union='" . $x["EQU_id"] . "' AND EQCO_estado=1 AND EQU_principal=0";
                    $conBrazoHidra = mysqli_query($conexion, $consultaE2);
                    foreach ($conBrazoHidra as $k) {
                        $codigoE2 = $k["EQU_codigo"];
                        $placaE2 = $k["EQU_placa"];
                        $familiaE2 = $k["FAM_descripcion"];
                        $modeloE2 = $k["MOD_descripcion"];
                    } ?>
                    <tr>
                        <td><?php echo $x["EQU_codigo"] ?></td>
                        <td><?php echo $x["EQU_placa"] ?></td>
                        <td><?php echo $x["FAM_descripcion"] ?></td>
                        <td><?php echo $x["MOD_descripcion"] ?></td>
                        <td><?php
                            echo $codigoE2
                                ? "<span class='badge rounded-pill bg-light text-dark'>$codigoE2</span>"
                                : "";  ?>
                        </td>
                        <td><?php echo $x["EQCO_tipo_medicion"]." : "; 
                        if ($x["EQCO_tipo_medicion"] == "Horometro digital") {
                            echo $x["PADI_medicion_digital"] != null ? $x["PADI_medicion_digital"] : "Sin registros";
                        } else if ($x["EQCO_tipo_medicion"] == "Horometro analogico") {
                            echo $x["PADI_medicion_analogico"] != null ? $x["PADI_medicion_analogico"] : "Sin registros";
                        }else {
                            echo $x["PADI_medicion_kilometraje"] != null ? $x["PADI_medicion_kilometraje"] : "Sin registros";
                        }
                        ?></td>
                        <td><?php echo $x["EQCO_fecha_ingreso_contrato"] ?></td>
                        <td><?php echo $x["EQCO_fecha_cierre"] ?></td>
                        <td>
                            <span class="badge rounded-pill text-dark <?php echo $arrayEstado[0] ?>"><?php echo $arrayEstado[1] ?></span>
                        </td>
                        <td class="text-center">
                            <?php if ($x["EQCO_estado_contrato"] == 1) { ?>
                                <a href="#" class="text-decoration-none" onclick="abreConfirmacionEquipoCont('<?php echo $datosEquipo ?>','finalizacion','finalizar contrato')">
                                    <span class="badge rounded-pill bg-primary">Finalizar</span>
                                </a>
                            <?php } ?>
                            <a href="#" class="text-danger" onclick="abreConfirmacionEquipoCont('<?php echo $datosEquipo ?>','eliminacion','eliminar')">
                                <i class="fas fa-minus-circle"></i>
                            </a>
                        </td>
                    </tr>
                    <?php if ($codigoE2 != null) { ?>
                        <tr>
                            <td> <?php echo $codigoE2 ?> </td>
                            <td> <?php echo $placaE2 ?> </td>
                            <td> <?php echo $familiaE2 ?> </td>
                            <td> <?php echo $modeloE2 ?> </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>