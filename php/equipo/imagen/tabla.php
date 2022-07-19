<?php
    include_once("../../conexion.php");
    $consulta =  "SELECT * FROM equipos e   INNER JOIN marcas ma ON ma.MAR_id=e.MAR_id01 
    INNER JOIN modelos mo ON mo.MOD_id=e.MOD_id01
    INNER JOIN propietarios p ON p.PROP_id=e.PROP_id01
    INNER JOIN familias fa ON fa.FAM_id=e.FAM_id01  WHERE e.EQU_estado = 1 AND EQU_principal=1 GROUP BY EQU_id";
    $conDocEquipo = mysqli_query($conexion,$consulta);
?>
<div><h5> REGISTRO TIPO EQUIPOS</h5></div>
<div class="container-fluid bg-white my-2 py-3">
<div class="table-responsive">
    <table id="tabla_imagenes_equipo" class="table table-striped">
        <thead >
            <tr>
                <th>codigo Equipo</th>
                <th>Familia</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cont=0;
            foreach ($conDocEquipo as $x) : ?> 
                <tr>
                    <td><?php echo $x["EQU_codigo"] ?></td>
                    <td><?php echo $x["FAM_descripcion"] ?></td>
                    <td><?php echo $x["MOD_descripcion"] ?></td>
                    <td><?php echo $x["MAR_descripcion"] ?></td>
                    <td class="text-center"><a href="#"  data-bs-toggle="modal" data-bs-target="#modalVerListaImagenes" onclick="verListaImagenes('<?php echo $x['EQU_id'] ?>')"><i class="far fa-images text-dark"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#tabla_imagenes_equipo').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>
