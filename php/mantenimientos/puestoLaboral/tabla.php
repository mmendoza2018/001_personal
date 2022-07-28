<?php
include_once("../../conexion.php");
$resPuestoLaboral = mysqli_query($conexion, "SELECT *, UPPER(pue_estado) as estado FROM gyt_puesto WHERE pue_estado='ACTIVO'");
?>
<div class="table-responsive">
    <table id="tabla_puesto_laboral" class="table table-striped">
        <thead>
            <tr>
                <th>Nro</th>
                <th>Descripción</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($resPuestoLaboral as $x) : 
            $datos = $x["id_puesto"]."|".$x["pue_descripcion"]."|".$x["estado"]."|".$x["pue_detalle"];?>
                <tr>
                    <td><?php echo $x["id_puesto"] ?></td>
                    <td><?php echo $x["pue_descripcion"] ?></td>
                    <td><center><a href="#"  data-bs-toggle="modal" data-bs-target="#modalPuestoLaboral" onclick="llenarDatosPuestoLaboral('<?php echo $datos ?>')"><i class="fas fa-edit text-dark"></i></a></center></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tabla_puesto_laboral').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>
