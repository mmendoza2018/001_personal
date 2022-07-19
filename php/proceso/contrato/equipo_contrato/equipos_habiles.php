<?php
require_once("../../../conexion.php");
$arrayEquipos = [/* ["id"=>[],"descripcion"=>[]] */];
$equiposAct = "SELECT EQU_codigo,FAM_descripcion,FAM_id,EQU_placa,EQU_modelo_motor FROM equipos e INNER JOIN familias fa ON fa.FAM_id = e.FAM_id01 WHERE EQU_id NOT IN (SELECT EQU_id01 FROM equipos_contrato ec INNER JOIN contratos c ON ec.CONTR_id01=c.CONTR_id WHERE EQCO_estado_contrato=1) AND EQU_principal=1";
$ResEquiposAct = mysqli_query($conexion, $equiposAct); ?>
<datalist id="listaEquipos">
<?php foreach ($ResEquiposAct as $x) : ?>
        <option value="<?php echo $x["EQU_codigo"] ?>">
            <?php echo $x["EQU_placa"] ."-".$x["FAM_descripcion"]; ?>
        </option>
    <?php endforeach; ?>
</datalist>