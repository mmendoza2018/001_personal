<?php
session_start();
require("../../conexion.php");

$usuario = $_SESSION["nombre_trabajador"];
$idDocumento = @$_POST["idDocumento"];
$idPersona = @$_POST["idPersona"];
$idTipoDocumento = @$_POST["idTipoDocumento"];
$fInicio = @$_POST["fInicio"];
$fFin = isset($_POST["fFin"]) ? $_POST["fFin"] : "0000-00-00"; ;
$numero = @$_POST["numero"];
$descripcion = @$_POST["descripcion"];
$empresa = @$_POST["empresa"];
$observaciones = @$_POST["observaciones"];
$estado = @$_POST["estado"];
$equipoAct=@$_POST["equipoAct"];
$descripcion = @$_POST["descripcion"];

$tipoDocumento="";
$identificadorDoc = "";

$datosDocumento = "SELECT doc_nomdoc FROM gyt_documentos WHERE id_documento='$idDocumento'";
$resDatosDocumento = mysqli_query($conexion, $datosDocumento);
foreach ($resDatosDocumento as $x) {
  $identificadorDoc = $x["doc_nomdoc"];
}
if ($_FILES['documento']['name']!=null) {

  $arrayIdentificador = explode(".", $identificadorDoc);
  $nuevoNombreArchivo = $arrayIdentificador[0] . rand(1,10) . "." . $arrayIdentificador[1];

  $ruta_doc_act = "../../../archivos/" . $identificadorDoc; // ruta del archivo
  unlink($ruta_doc_act); //eliminamos el archivo 
  $contenidoArchivo = file_get_contents($_FILES["documento"]['tmp_name']); //contenido del archivo
  $guarda_doc =  file_put_contents("../../../archivos/" . $nuevoNombreArchivo, $contenidoArchivo);
  if(!$guarda_doc) {
    echo json_encode(false);
    die();
  };
  $identificadorDoc=$nuevoNombreArchivo;
}
$consulta = "UPDATE gyt_documentos SET 
                                          id_persona = '$idPersona',
                                          id_tipodocumento = '$idTipoDocumento',	
                                          doc_fecha1 = '$fInicio',		
                                          doc_fecha2 = '$fFin',		
                                          doc_numdoc = '$numero',		
                                          doc_descripcion = '$descripcion',	
                                          doc_empresa = '$empresa',	
                                          doc_observa = '$observaciones',	
                                          doc_nomdoc = '$identificadorDoc',	
                                          doc_loguser = '$usuario'
                                          WHERE id_documento='$idDocumento'";

echo (mysqli_query($conexion,$consulta)) ? json_encode(true) : json_encode(false);
$conexion->close();
?>
