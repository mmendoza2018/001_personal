<?php
include_once("../../conexion.php");
$resTipoDocumento = mysqli_query($conexion, "SELECT * FROM gyt_tipodocumento WHERE tdoc_estado='ACTIVO'");
?>
<div class="table-responsive">
    <table id="tabla_tipo_documento" class="table table-striped">
        <thead>
            <tr>
                <th>Nro</th>
                <th>Descripción</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($resTipoDocumento as $x) : 
            $datos = $x["id_tipodocumento"]."|".$x["tdoc_descripcion"]."|".$x["tdoc_estado"];?>
                <tr>
                    <td><?php echo $x["id_tipodocumento"] ?></td>
                    <td><?php echo $x["tdoc_descripcion"] ?></td>
                    <td><center><a href="#"  data-bs-toggle="modal" data-bs-target="#modalTipoDocumento" onclick="llenarDatosTipoDocumento('<?php echo $datos ?>')"><i class="fas fa-edit text-dark"></i></a></center></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tabla_tipo_documento').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>
