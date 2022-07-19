<?php
    $codigoEquipo=$_POST["codigoEquipo"];
    $familia = "";
    $placa = "";
    include_once("../../conexion.php");
    $conCodigoEquipo = mysqli_query($conexion,"SELECT EQU_id,EQU_codigo,EQU_placa,FAM_descripcion,EQU_modelo_motor FROM equipos e INNER JOIN familias f ON f.FAM_id = e.FAM_id01  WHERE EQU_codigo='$codigoEquipo'");
    if(mysqli_num_rows($conCodigoEquipo)<=0 && $codigoEquipo != "data-falsa") {
        echo 0;
        die();
    }else{
        foreach ($conCodigoEquipo as $k) {
            $familia = $k["FAM_descripcion"];
            $placa = $k["EQU_placa"];
        }
    }
    $consulta =  "SELECT * FROM contratos co    INNER JOIN equipos_contrato ec ON ec.CONTR_id01=co.CONTR_id 
                                                INNER JOIN equipos e ON ec.EQU_id01=e.EQU_id 
                                                INNER JOIN clientes cl ON co.CLIE_id01=cl.CLIE_id 
                                                INNER JOIN proyectos p ON co.PROY_id01=p.PROY_id  WHERE EQU_codigo='$codigoEquipo' ORDER BY CONTR_f_inicio DESC";
    $listadoHistorial = mysqli_query($conexion,$consulta);
?>
<div class="container-fluid bg-white my-2 py-3">
    <div class="border-top">
        <div class="table-responsive">
            <table class="table table-sm">
                <tr>
                    <td class="fw-bold">Familia:</td>
                    <td><?php echo $familia  ?></td>
                    <td class="fw-bold">Placa:</td>
                    <td><?php echo $placa  ?></td>
                </tr>
            </table>
        </div>
    </div>
<div class="table-responsive">
    <table id="tablaHistorialEquiposContrato" class="table table-sm table-striped">
        <thead >
            <tr>
                <th>Proyecto</th>
                <th>Contrato</th>
                <th>Cliente</th>
                <th>F. inicio</th>
                <th>F. termino</th>
                <th>F. finalizacion equipo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($listadoHistorial as $x) : ?> 
                <tr>
                    <td><?php echo $x["PROY_descripcion"] ?></td>
                    <td><?php echo $x["CONTR_descripcion"] ?></td>
                    <td><?php echo $x["CLIE_razon_social"] ?></td>
                    <td><?php echo $x["CONTR_f_inicio"] ?></td>
                    <td><?php echo $x["CONTR_f_fin"] ?></td>
                    <td><?php echo ($x["EQCO_fecha_cierre"]==null) ? "<span class=\"badge rounded-pill bg-success\">En proceso</span>" : $x["EQCO_fecha_cierre"] ?></td>
                    <td><?php echo $x["CONTR_estado"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#tablaHistorialEquiposContrato').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>

