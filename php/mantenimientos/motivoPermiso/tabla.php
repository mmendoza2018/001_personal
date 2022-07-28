<?php
include_once("../../conexion.php");
$conTipoEquipo = mysqli_query($conexion, "SELECT * FROM gyt_motivos WHERE MOT_estado='ACTIVO'");
?>
<div class="table-responsive">
    <table id="tablaMotivoSalida" class="table table-striped">
        <thead>
            <tr>
                <th>Nro</th>
                <th>Descripción</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($conTipoEquipo as $x) : 
            $datos = $x["id_motivo"]."|".$x["mot_descripcion"]."|".$x["mot_estado"];?>
                <tr>
                    <td><?php echo $x["id_motivo"] ?></td>
                    <td><?php echo $x["mot_descripcion"] ?></td>
                    <td><center><a href="#"  data-bs-toggle="modal" data-bs-target="#modalMotivoAct" onclick="llenarDatosMotivo('<?php echo $datos ?>')"><i class="fas fa-edit text-dark"></i></a></center></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tablaMotivoSalida').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>
