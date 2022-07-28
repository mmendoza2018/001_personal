<?php 
session_start();
require_once("../../conexion.php");

$id_tipodocumento = @$_POST["id_tipodocumento"];
$tdoc_descripcion = @$_POST["tdoc_descripcion"];
$tdoc_estado = @$_POST["tdoc_estado"];

$consulta="UPDATE gyt_tipodocumento SET tdoc_descripcion='$tdoc_descripcion', tdoc_estado='$tdoc_estado' WHERE id_tipodocumento='$id_tipodocumento';";
$updateTipoDocumento = mysqli_query($conexion,$consulta);
echo ($updateTipoDocumento) ? json_encode(true) : json_encode(false) ;  
$conexion->close();
?>