<?php
    include_once("../../conexion.php");
    $consulta =  "SELECT EQU_codigo,FAM_descripcion,MOD_descripcion,MAR_descripcion,MAR_descripcion,EQU_id,EQU_placa FROM equipos e  INNER JOIN marcas ma ON ma.MAR_id=e.MAR_id01 
    INNER JOIN modelos mo ON mo.MOD_id=e.MOD_id01
    INNER JOIN propietarios p ON p.PROP_id=e.PROP_id01
    INNER JOIN familias fa ON fa.FAM_id=e.FAM_id01  WHERE e.EQU_estado = 1 AND EQU_principal=1 GROUP BY EQU_id";
    $conDocEquipo = mysqli_query($conexion,$consulta);
    function NumDocumentos ($conexion,$idEquipo) {
        $numDocEquipo = mysqli_query($conexion,"SELECT COUNT($idEquipo) AS numDocs FROM documento_equipos de INNER JOIN equipos e ON de.EQU_id01=e.EQU_id WHERE (EQU_id='$idEquipo' OR EQU_union='$idEquipo') AND DOEQ_estado=1 ");
        foreach ($numDocEquipo as $y) { return $numDocs = $y["numDocs"];}
    }
    
?>
<div><h5> REGISTRO TIPO EQUIPOS</h5></div>
<div class="container-fluid bg-white my-2 py-3">
<div class="table-responsive">
    <table id="tabla_documento_equipo" class="table table-striped">
        <thead >
            <tr>
                <th>codigo Equipo</th>
                <th>Familia</th>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>N° de documentos</th>
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
                    <td><?php echo $x["EQU_placa"] ?></td>
                    <td><?php echo $x["MOD_descripcion"] ?></td>
                    <td><?php echo $x["MAR_descripcion"] ?></td>
                    <td class="text-center"><?php echo NumDocumentos($conexion,$x["EQU_id"]) ?></td>
                    <td class="text-center"><a href="#" onclick="verListaDocumentos('<?php echo $x['EQU_id'] ?>',true)"><i class="fas fa-folder-open text-dark"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#tabla_documento_equipo').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>