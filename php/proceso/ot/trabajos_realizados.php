<?php
require_once("../../conexion.php");
$idOrdenTrabajo = $_POST["idOrdenTrabajo"];
$resCambiosRealizados = mysqli_query($conexion, "SELECT * FROM trabajos_realizados tr INNER JOIN trabajadores t ON tr.TRAB_id01=t.TRAB_id WHERE ORTR_id01='$idOrdenTrabajo' AND TRRE_estado=1");

?>
<div class="table-responsive">
    <table id="" class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>Descripción</th>
                <th>Trabajador</th>
                <th>Duración</th>
                <th>Actualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sumaTotal = 0;
            foreach ($resCambiosRealizados as $x) :
            $datosCRealizados = $x["TRRE_id"]."|".$x["TRRE_descripcion"]."|".$x["TRRE_duracion"]."|".$x["TRAB_nombres"];
            $sumaTotal += $x["TRRE_duracion"] ?> 
                <tr>
                    <td><?php echo $x["TRRE_descripcion"] ?></td>
                    <td><?php echo $x["TRAB_nombres"] ?></td>
                    <td><?php echo $x["TRRE_duracion"] ?></td>
                    <td class="text-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalTrabajoRealizadoAct" onclick="llenarDatosCRealizadosOt('<?php echo $datosCRealizados ?>')"><i class="fas fa-edit text-dark"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>    
                <td colspan="2"></td>
                <td class="border border-secondary"><?php echo $sumaTotal?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>