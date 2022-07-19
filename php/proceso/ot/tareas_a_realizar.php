<?php 
require_once("../../conexion.php");
$idOrdenTrabajo = $_POST["idOrdenTrabajo"];

$listaDetalleOrdenes = mysqli_query($conexion,"SELECT TISI_descripcion FROM trabajos_realizar do INNER JOIN tipo_sistemas ts ON do.TISI_id01=ts.TISI_id  WHERE ORTR_id01='$idOrdenTrabajo'");
?>
<div class="table-responsive">
    <table  class="table table-bordered table-sm">
        <tbody>
            <?php
            foreach ($listaDetalleOrdenes as $x) :?> 
                <tr>
                    <td><?php echo $x["TISI_descripcion"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>