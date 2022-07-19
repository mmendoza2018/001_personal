<?php 
require_once("../../conexion.php");
$idOrdenTrabajo = $_POST["idOrdenTrabajo"];

$listaImgOrdenes = mysqli_query($conexion,"SELECT IMOT_identificador,IMOT_id FROM imagen_ots i INNER JOIN ordenes_trabajo ot ON ot.ORTR_id=i.ORTR_id01  WHERE ORTR_id01='$idOrdenTrabajo' AND IMOT_estado=1");
?>
<div class="row">
   <?php foreach ($listaImgOrdenes as $x) :
    $ruta = "archivos/imagenOts/".$x["IMOT_identificador"];
    $idImagen = $x["IMOT_id"];  ?> 
        <div class="col-sm-4 mb-2">
            <div class="ratio ratio-16x9 container-img-ot">
                <img src="<?php echo $ruta ?>" class="img-fluid">
                <div class="opciones-img-ot">
                    <i class="fas fa-trash-alt fa-2x text-light" onclick="eliminarImagenOT('<?php echo $idImagen ?>')"></i>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>