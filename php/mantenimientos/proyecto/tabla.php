<?php
include_once("../../conexion.php");
$conTipoEquipo = mysqli_query($conexion, "SELECT * FROM gyt_proyectos WHERE pro_estado = 'ACTIVO'");
?>
<div class="table-responsive">
    <table id="tablaProyectos" class="table table-striped">
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
            $datos = $x["id_proyecto"]."|".$x["pro_descripcion"]."|".$x["pro_estado"];?>
                <tr>
                    <td><?php echo $x["id_proyecto"] ?></td>
                    <td><?php echo $x["pro_descripcion"] ?></td>
                    <td><center><a href="#"  data-bs-toggle="modal" data-bs-target="#modalProyectosAct" onclick="llenarDatosProyectos('<?php echo $datos ?>')"><i class="fas fa-edit text-dark""></i></a></center></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tablaProyectos').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>
