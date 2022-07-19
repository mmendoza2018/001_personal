<?php 
require_once("../conexion.php");

$consulta = "SELECT EQU_id, EQU_codigo, FAM_descripcion,EQU_placa FROM equipos e INNER JOIN familias f ON e.FAM_id01=f.FAM_id WHERE EQU_estado=1 AND EQU_principal=1";

$listaEquipos = mysqli_query($conexion,$consulta);
?>
<datalist id="listaEquipoGeneral">
<?php foreach ($listaEquipos as $x) : ?>
        <option value="<?php echo $x["EQU_codigo"] ?>">
            <?php echo $x["EQU_placa"] ."-".$x["FAM_descripcion"]; ?>
        </option>
    <?php endforeach; ?>
</datalist>