<?php
session_start();
require_once("../../utilidades/randon_string.php");
require_once("../../conexion.php");

$usuario = $_SESSION["nombre_trabajador"];

$numid = @$_POST["numid"];
$tipdoc = @$_POST["tipdoc"];
$fechaInicio = @$_POST["fechaInicio"];
$fechaFin = (isset($_POST["fechaFin"])) ? $_POST["fechaFin"] : "0000-00-00";
$numerodoc = @$_POST["numerodoc"];
$descripcion = @$_POST["descripcion"];
$empresa = @$_POST["empresa"];
$observacion = @$_POST["observacion"];
$adjunto = @$_FILES["adjunto"];

$nombreArchivo = "";

$claveAleatoria = generateRandomString();  // OR: generateRandomString(24)
$contenidoArchivo = file_get_contents($adjunto['tmp_name']); //contenido del archivo

$rutaDoc = "../../../archivos"; // ruta del archivo
$nombreIdentificador = $numid . "-" . $claveAleatoria . ".pdf";
$guardaDoc =  file_put_contents($rutaDoc . "/" . $nombreIdentificador, $contenidoArchivo);
if ($guardaDoc) {
    $consulta = "INSERT INTO gyt_documentos (id_persona,id_tipodocumento,doc_fecha1,doc_fecha2,doc_numdoc,doc_descripcion,doc_empresa,doc_observa,doc_nomdoc,doc_loguser) VALUES 
    ('$numid','$tipdoc','$fechaInicio','$fechaFin','$numerodoc','$descripcion','$empresa','$observacion','$nombreIdentificador','$usuario')";

    $resConAgregaDoc = mysqli_multi_query($conexion, $consulta);

    echo ($resConAgregaDoc) ? json_encode(true) : json_encode(false);
} else {
    echo json_encode(false); // ocurrio un error al subir el archivo
}
