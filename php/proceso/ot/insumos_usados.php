<?php
require_once("../../conexion.php");
$idOrdenTrabajo = $_POST["idOrdenTrabajo"];
$resInsumos = mysqli_query($conexion, "SELECT * FROM insumos_ordenes WHERE ORTR_id01='$idOrdenTrabajo' AND INOR_estado=1");

?>
<div class="table-responsive">
    <table id="" class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>Descripción</th>
                <th># parte</th>
                <th>Marca</th>
                <th>Cantidad</th>
                <th>U. medida</th>
                <th>Moneda</th>
                <th>Precio</th>
                <th>Observación</th>
                <th>Actualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sumaTotal=0;
            foreach ($resInsumos as $x) :
            $datosInsumos=$x["INOR_id"]."|".$x["INOR_descripcion"]."|".$x["INOR_codigo"]."|".$x["INOR_marca"]."|".$x["INOR_cantidad"]."|".$x["INOR_observacion"]."|".$x["INOR_moneda"]."|".$x["INOR_precio"]."|".$x["INOR_umedida"];
            $sumaTotal += 0;  ?> 
                <tr>
                    <td><?php echo $x["INOR_descripcion"] ?></td>
                    <td><?php echo $x["INOR_codigo"] ?></td>
                    <td><?php echo $x["INOR_marca"] ?></td>
                    <td><?php echo $x["INOR_cantidad"] ?></td>
                    <td><?php echo $x["INOR_umedida"] ?></td>
                    <td><?php echo $x["INOR_moneda"] ?></td>
                    <td><?php echo $x["INOR_precio"] ?></td>
                    <td><?php echo $x["INOR_observacion"] ?></td>
                    <td class="text-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalInsumosOtAct" onclick="llenarInsumosOt('<?php echo $datosInsumos  ?>')"><i class="fas fa-edit text-dark"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
          <!--   <tr>
                <td colspan="6"></td>
                <td class="border border-secondary"><?php  echo $sumaTotal?></td>
                <td colspan="1"></td>
            </tr> -->
        </tbody>
    </table>
</div>