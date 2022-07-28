<?php 
session_start();
require_once("../../conexion.php");

$descripcionTipoDocumento = @$_POST["descripcionTipoDocumento"];

$consulta = "INSERT INTO gyt_tipodocumento (tdoc_descripcion, tdoc_estado) VALUES ('$descripcionTipoDocumento','ACTIVO')";
$addTipoDocumento = mysqli_query($conexion,$consulta);
echo ($addTipoDocumento) ? json_encode(true) : json_encode(false) ; 
$conexion->close();
?>
