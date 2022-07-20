
<?php 
require("../../conexion.php");
$idArchivo=$_POST['idEquipo'];
$carpetaEquipo="";
$identificadorDoc="";
$traerDocumento="SELECT * FROM documento_equipos de INNER JOIN tipo_doc_equipos te ON 
                de.TIDO_id01=te.TIDO_id  WHERE DOEQ_id='$idArchivo'";
$resTraerDocumento=mysqli_query($conexion,$traerDocumento);
foreach ($resTraerDocumento as $x) {

    $carpetaEquipo=$x["TIDO_descripcion"];
    $identificadorDoc=$x["DOEQ_identificador"];
}
  $ruta= "archivos/equipos/documentos/".$carpetaEquipo."/".$identificadorDoc;
  if(file_exists("../../../".$ruta)){
    echo  "<object data=\"$ruta\" type=\"application/pdf\"  height=\"500\" class=\" w-100\"/>";
  }
  else{
    echo " <div class=\"card bg-danger text-white\">
    <div class=\"card-body\">El archivo no fue ubicado, es posible que haya ocurrido un error al guardar o se haya eliminado.</div>
  </div>";
  }

 ?>