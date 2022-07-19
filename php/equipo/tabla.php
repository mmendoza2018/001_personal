<?php
    include_once("../conexion.php");
    $consulta =  "SELECT * FROM equipos e   INNER JOIN marcas ma ON ma.MAR_id=e.MAR_id01 
                                            INNER JOIN modelos mo ON mo.MOD_id=e.MOD_id01
                                            INNER JOIN propietarios p ON p.PROP_id=e.PROP_id01
                                            INNER JOIN familias fa ON fa.FAM_id=e.FAM_id01  WHERE EQU_estado = 1 AND EQU_principal=1";
    $conEquipo = mysqli_query($conexion,$consulta);
?>
<div><h5> REGISTRO TIPO EQUIPOS</h5></div>
<div class="container-fluid bg-white my-2 py-3">
    <div class="row justify-content-end mt-0 mb-2">
        <div class="col-sm-4 col-lg-2  col-xs-5">
        <i class="fas fa-exclamation-circle text-warning fa-2x"></i>
        <button type="button" class="btn btn-red-gyt btn-sm float-end" onclick="modalEnvioAdjunto()" > enviar archivos <i class="fas fa-envelope-open-text text-light"></i></button>
        </div>
    </div>
<div class="table-responsive">
    <table id="tabla_registro_equipos" class="table table-striped">
        <thead >
            <tr>
                <th>codigo</th>
                <th>Equipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Capacidad</th>
                <th>Propietario</th>
                <th>Detalle</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($conEquipo as $x) :
            $idEquipo = $x["EQU_id"];
            $datos = $x["EQU_id"]."|".$x["EQU_codigo"]."|".$x["FAM_id"]."|".$x["MAR_id"]."|".$x["MOD_id"]."|".$x["EQU_placa"]."|".$x["EQU_modelo_motor"]."|".$x["EQU_numero_motor"]."|".$x["EQU_a_fabricacion"]."|".$x["EQU_a_fabricacion_pluma"]."|".$x["EQU_serie_chasis"]."|".$x["EQU_capacidad"]."|".$x["PROP_id"]."|".$x["EQU_f_ingreso"]."|".$x["EQU_f_salida"]."|".$x["FAM_descripcion"]."|".$x["PROP_descripcion"]."|".$x["EQU_centro_costo"]."|".$x["EQU_marca_motor"]."|".$x["EQU_medicion"]; ?> 
                <tr>
                    <td><input type="checkbox" data-check="<?php echo $x["EQU_id"] ?>" class="mx-2"><?php echo $x["EQU_codigo"] ?> </td>
                    <td><?php echo $x["FAM_descripcion"] ?></td>
                    <td><?php echo $x["MAR_descripcion"] ?></td>
                    <td><?php echo $x["MOD_descripcion"] ?></td>
                    <td><?php echo $x["EQU_placa"] ?></td>
                    <td><?php echo $x["EQU_capacidad"] ?></td>
                    <td><?php echo $x["PROP_descripcion"] ?></td>
                    <td><a href="#" onclick="verDetalleEquipo('<?php echo $idEquipo ?>')"><span class="badge rounded-pill bg-primary">detalle</span></a></td>
                    <td class="text-center">
                   <!--  <a href="#"><i class="fas fa-charging-station text-dark"></i></a> -->
                    <a href="#" onclick="verPdfEquipo('<?php echo $x['EQU_id'] ?>')"><i class="fas fa-file-pdf text-dark"></i></a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalEquipoAct" onclick="llenarDatosEquipo('<?php echo $datos ?>')"><i class="fas fa-edit text-dark"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script>
    $(document).ready(function() {
        var table=$('#tabla_registro_equipos').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    })
</script>