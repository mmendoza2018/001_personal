<?php
include_once("../../conexion.php");
include_once("../../calculo_tiempo.php");
$whereConsulta = "";
    $idEquipo=$_POST["idEquipo"];
    if ($idEquipo != "false") {
        $whereConsulta = " WHERE (EQU_id01='$idEquipo' OR EQU_union='$idEquipo') AND DOEQ_estado = 1";
    }else {
        $whereConsulta = " WHERE DOEQ_estado = 1";
    }

    $actualizar = isset($_POST["actualizar"]) ? $_POST["actualizar"] : true;
    $consulta =  "SELECT * FROM documento_equipos de INNER JOIN tipo_doc_equipos te ON de.TIDO_id01=te.TIDO_id 
                                                     INNER JOIN equipos e ON de.EQU_id01=e.EQU_id $whereConsulta";
    $conDocEquipo = mysqli_query($conexion,$consulta);
    $alertaOt= mysqli_query($conexion,"SELECT * FROM anticipacion_alertas WHERE ALE_seccion='documento_equipos'");
    foreach ($alertaOt as $x) {  $regular= $x["ALE_regular"]; $malo= $x["ALE_malo"]; }
?>
<div class="container-fluid bg-white my-2 py-3">
    <div class="row justify-content-end mt-0 mb-2">
        <div class="col-sm-4 col-lg-3  col-xs-5">
        <!-- <i class="fas fa-exclamation-circle text-warning fa-2x"></i> -->
        <button 
        type="button" 
        class="btn btn-red-gyt btn-sm float-end" 
        onclick="<?php echo ($idEquipo != 'false') ? 'modalEnvioDocumentoEquipo()' : 'modalEnvioDocumentosMulti()'?>" >
         enviar archivos 
         <i class="fas fa-envelope-open-text text-light"></i>
        </button>
        </div>
    </div>
<div class="table-responsive">
    <table id="tabla_listaDoc" class="table table-striped">
        <thead >
            <tr>
                <th>Codigo Equipo</th>
                <th>Placa</th>
                <th>Tipo documento</th>
                <th>Descripción</th>
                <th>F. ingreso</th>
                <th>F. vencimiento</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $classCircle="";
            foreach ($conDocEquipo as $x) : 
             $datosDocEquipo = $x["DOEQ_id"]."|".$x["EQU_codigo"]."|".$x["DOEQ_descripcion"]."|".$x["DOEQ_vencimiento"]."|".$x["TIDO_estado"];
             $classCircle = calculoFechaPersonalizado(new DateTime($x["DOEQ_vencimiento"]),$regular,$malo)?> 
                <tr>
                    <td>
                        <input 
                        type="checkbox" 
                        onclick="<?php echo $idEquipo == 'false' ? 'capturarIddocEquipoMultiple(this)' :'' ?>"
                        data-check="<?php echo $x["DOEQ_id"] ?>" 
                        class="mx-2"
                        data-codigo="<?php echo $x["EQU_codigo"] ?>" 
                        data-documento="<?php echo $x["TIDO_descripcion"] ?>" 
                        >
                        <?php echo $x["EQU_codigo"] ?> 
                    </td>
                    <td><?php echo $x["EQU_placa"] ?></td>
                    <td><?php echo $x["TIDO_descripcion"] ?></td>
                    <td><?php echo $x["DOEQ_descripcion"] ?></td>
                    <td><?php echo $x["DOEQ_ingreso"] ?></td>
                    <td>
                        <?php if ($x["DOEQ_vencimiento"]=="0000-00-00"){
                            echo "";
                        }else{ ?>
                            <i class="fas fa-circle <?php echo $classCircle ?>"></i>
                        <?php echo $x["DOEQ_vencimiento"]; } ?> 
                  </td>
                    <td class="text-center">
                        <a href="#"  data-bs-toggle="modal" data-bs-target="#modalDocEquipo" onclick="verDocEquipo('<?php echo $x['DOEQ_id'] ?>')"><i class="fas fa-file-pdf text-dark"></i></a>
                        <?php if ($actualizar=="true") { ?>
                            <a href="#"  data-bs-toggle="modal" data-bs-target="#modalDocEquipoAct" onclick="llenarDatosDocEquipo('<?php echo $datosDocEquipo ?>')"><i class="fas fa-edit text-dark"></i></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script>
       var table = $('#tabla_listaDoc').DataTable({
            "info":false,
            "paging": <?php echo ($idEquipo != "false") ? 'false' : 'true'; ?>,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
</script>

