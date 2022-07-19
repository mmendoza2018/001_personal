<?php
session_start();
require("../../conexion.php");
$usuario = $_SESSION["nombre_trabajador"];
$idAct = @$_POST["idAct"];
$equipoAct=@$_POST["equipoAct"];
$descripcionAct = @$_POST["descripcionAct"];
(isset($_POST["vencimientoAct"])) ? $vencimientoAct= $_POST["vencimientoAct"] : $vencimientoAct= "0000-00-00";
$estadoAct = @$_POST["estadoAct"];
$tipoDocumento="";
$identificadorDoc = "";
$idEquipo= mysqli_query($conexion,"SELECT EQU_id,EQU_codigo FROM equipos WHERE EQU_codigo='$equipoAct'");
foreach ($idEquipo as $x) { $equipoAct=$x["EQU_id"]; }

$datosDocumento = "SELECT DOEQ_identificador,TIDO_descripcion,DOEQ_id FROM tipo_doc_equipos te 
                   INNER JOIN documento_equipos de ON de.TIDO_id01=te.TIDO_id WHERE DOEQ_id='$idAct'";
$resDatosDocumento = mysqli_query($conexion, $datosDocumento);
foreach ($resDatosDocumento as $x) {

  $identificadorDoc = $x["DOEQ_identificador"];
  $tipoDocumento = $x["TIDO_descripcion"];

}
if ($_FILES['archivoAct']['name']!=null) {

  $arrayIdentificador = explode(".", $identificadorDoc);
  $nuevoNombreArchivo = $arrayIdentificador[0] . rand(1,10) . "." . $arrayIdentificador[1];

  $ruta_doc_act = "../../../archivos/equipos/documentos/" . $tipoDocumento . "/" . $identificadorDoc; // ruta del archivo
  unlink($ruta_doc_act); //eliminamos el archivo 
  $contenidoArchivo = file_get_contents($_FILES["archivoAct"]['tmp_name']); //contenido del archivo
  $guarda_doc =  file_put_contents("../../../archivos/equipos/documentos/".$tipoDocumento . "/" . $nuevoNombreArchivo, $contenidoArchivo);
  if(!$guarda_doc) return "false";
  $identificadorDoc=$nuevoNombreArchivo;
}
$consulta = "UPDATE documento_equipos SET 
                                                EQU_id01='$equipoAct',
                                                DOEQ_descripcion='$descripcionAct',	
                                                DOEQ_identificador='$identificadorDoc',		
                                                DOEQ_vencimiento='$vencimientoAct',	
                                                DOEQ_estado='$estadoAct'
                                                WHERE DOEQ_id='$idAct';";
$consulta.="CALL agregar_historial('documento_equipos','$idAct','actualizo','$usuario')";
$resActualizaDocEquipo = mysqli_multi_query($conexion,$consulta);
echo ($resActualizaDocEquipo) ? json_encode(["true",$equipoAct]) : json_encode(["false",$equipoAct]);
$conexion->close();
?>
