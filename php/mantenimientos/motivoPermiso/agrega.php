<?php 
session_start();
require_once("../../conexion.php");

$descripcionMotivo = @$_POST["descripcionMotivo"];
$consulta = "INSERT INTO gyt_motivos (mot_descripcion,mot_estado) VALUES ('$descripcionMotivo','ACTIVO')";
$addMotivoSalida = mysqli_query($conexion,$consulta);
echo ($addMotivoSalida) ? json_encode(true) : json_encode(false) ; 
$conexion->close();
?>