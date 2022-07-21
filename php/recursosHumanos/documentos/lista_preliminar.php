<?php
    $codigoEquipo=@$_POST["codigoEquipo"];
    include_once("../../conexion.php");
    include_once("../../calculo_tiempo.php");
    $ConIdEquipo = mysqli_query($conexion,"SELECT EQU_id,FAM_descripcion FROM equipos e INNER JOIN familias f ON e.FAM_id01=f.FAM_id WHERE EQU_codigo='$codigoEquipo'");
    if($ConIdEquipo->num_rows<=0) { echo "5"; die();};
    
    foreach ($ConIdEquipo as $x) { $idEquipo = $x["EQU_id"]; $familia = $x["FAM_descripcion"]; }

    $consulta =  "SELECT * FROM documento_equipos de INNER JOIN tipo_doc_equipos te ON de.TIDO_id01=te.TIDO_id 
                                                     INNER JOIN equipos e ON de.EQU_id01=e.EQU_id  WHERE EQU_id01='$idEquipo'  AND DOEQ_estado = 1";
    $conDocEquipo = mysqli_query($conexion,$consulta);
    $alertaOt= mysqli_query($conexion,"SELECT * FROM anticipacion_alertas WHERE ALE_seccion='documento_equipos'");
    foreach ($alertaOt as $x) {  $regular= $x["ALE_regular"]; $malo= $x["ALE_malo"]; }
?>
<div class="container-fluid bg-white my-2 py-3">
    <p class="fw-bold text-center">Documentos <?php echo $codigoEquipo ." - ".$familia ?></p>
<div class="table-responsive">
    <table class="table table-sm table-striped">
        <thead >
            <tr>
                <th>Tipo documento</th>
                <th>F. vencimiento</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $classCircle="";
            foreach ($conDocEquipo as $x) : 
             $datosDocEquipo = $x["DOEQ_id"]."|".$x["EQU_codigo"]."|".$x["DOEQ_descripcion"]."|".$x["DOEQ_vencimiento"]."|".$x["TIDO_estado"];
             $classCircle = calculoFechaPersonalizado(new DateTime($x["DOEQ_vencimiento"]),$regular,$malo)?> 
                <tr>
                    <td><?php echo $x["TIDO_descripcion"] ?></td>
                    <td>
                        <?php if ($x["DOEQ_vencimiento"]=="0000-00-00"){
                            echo "";
                        }else{ ?>
                            <i class="fas fa-circle <?php echo $classCircle ?>"></i>
                        <?php echo $x["DOEQ_vencimiento"]; } ?>
                        <a href="#"  data-bs-toggle="modal" data-bs-target="#modalDocEquipo" onclick="verDocEquipo('<?php echo $x['DOEQ_id'] ?>')"><i class="fas fa-file-pdf text-dark"></i></a> 
                  </td>
                  
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>

