<?php
include_once("../../../conexion.php");
$conTipoEquipo = mysqli_query($conexion, "SELECT * FROM tipo_doc_equipos WHERE TIDO_estado = 1");
?>
<div class="table-responsive">
    <table id="tabla_tipo_equipo" class="table table-striped">
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
            $datos = $x["TIDO_id"]."|".$x["TIDO_descripcion"]."|".$x["TIDO_estado"];?>
                <tr>
                    <td><?php echo $x["TIDO_id"] ?></td>
                    <td><?php echo $x["TIDO_descripcion"] ?></td>
                    <td><a href="#"  data-bs-toggle="modal" data-bs-target="#modalTipoDocEquipoAct" onclick="llenarDatosTipoDocEquipo('<?php echo $datos ?>')"><i class="fas fa-edit text-dark""></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tabla_tipo_equipo').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>
