
<?php 
require("../../conexion.php");
$idArchivo=$_POST['idEquipo'];
$carpetaEquipo="";
$identificadorDoc="";
$traerDocumento="SELECT * FROM imagen_equipos ei INNER JOIN tipo_img_equipos ti ON 
                ei.TIIM_id01=ti.TIIM_id  WHERE IMEQ_id='$idArchivo'";
$resTraerDocumento=mysqli_query($conexion,$traerDocumento);
foreach ($resTraerDocumento as $x) {

    $carpetaEquipo=$x["TIIM_descripcion"];
    $identificadorDoc=$x["IMEQ_identificador"];
}
  $ruta= "archivos/equipos/imagenes/".$carpetaEquipo."/".$identificadorDoc;
  if(file_exists("../../../".$ruta)){
    echo  "<img src=\"$ruta\" class=\"img-fluid\"/>";
  }
  else{
    echo " <div class=\"card bg-danger text-white\">
    <div class=\"card-body\">El archivo no fue ubicado, es posible que haya ocurrido un error al guardar o se haya eliminado.</div>
  </div>";
  }


 ?>