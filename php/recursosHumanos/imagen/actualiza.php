<?php
session_start();
require("../../conexion.php");
$idAct = @$_POST["idAct"];
$equipoAct=@$_POST["equipoAct"];
$descripcionAct = @$_POST["descripcionAct"];
$estadoAct = @$_POST["estadoAct"];
$tipoImagen="";
$identificadorImg = "";
$usuario = $_SESSION["nombre_trabajador"];
$idEquipo= mysqli_query($conexion,"SELECT EQU_id,EQU_codigo FROM equipos WHERE EQU_codigo='$equipoAct'");
foreach ($idEquipo as $x) { $equipoAct=$x["EQU_id"]; }

$datosImagen = "SELECT IMEQ_identificador,TIIM_descripcion,IMEQ_id FROM tipo_img_equipos te 
                   INNER JOIN imagen_equipos ie ON te.TIIM_id=ie.TIIM_id01 WHERE IMEQ_id='$idAct'";
$resDatosImagen = mysqli_query($conexion, $datosImagen);
foreach ($resDatosImagen as $x) {

  $identificadorImg = $x["IMEQ_identificador"];
  $tipoImagen = $x["TIIM_descripcion"];

}
if ($_FILES['archivoAct']['name']!=null) {

  $arrayIdentificador = explode(".", $identificadorImg);
  $nuevoNombreArchivo = $arrayIdentificador[0] . rand(1,10) . "." . $arrayIdentificador[1];

  $rutaImg = "../../../archivos/equipos/imagenes/" . $tipoImagen . "/" . $identificadorImg; // ruta del archivo
  unlink($rutaImg); //eliminamos el archivo 
  $contenidoArchivo = file_get_contents($_FILES["archivoAct"]['tmp_name']); //contenido del archivo
  $guardaImg =  file_put_contents("../../../archivos/equipos/imagenes/".$tipoImagen . "/" . $nuevoNombreArchivo, $contenidoArchivo);
  $identificadorImg=$nuevoNombreArchivo;
}
$consulta = "UPDATE imagen_equipos SET 
                                                EQU_id01='$equipoAct',
                                                IMEQ_descripcion='$descripcionAct',	
                                                IMEQ_identificador='$identificadorImg',		
                                                IMEQ_estado='$estadoAct'
                                                WHERE IMEQ_id='$idAct';";
$consulta.="CALL agregar_historial('imagen_equipos','$idAct','actualizo','$usuario')";
$resActualizaImgEquipo = mysqli_multi_query($conexion,$consulta);
echo ($resActualizaImgEquipo) ? json_encode(["true",$equipoAct]) : json_encode(["false",$equipoAct]); 
$conexion->close();
?>