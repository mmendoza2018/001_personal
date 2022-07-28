<?php 
session_start();
require_once("../../conexion.php");

$idPuestoLaboral = @$_POST["idPuestoLaboral"];
$descripcionPuestoLaboral = @$_POST["descripcionPuestoLaboral"];
$detallePuestoLaboral = @$_POST["detallePuestoLaboral"];
$estadoPuestoLaboral = @$_POST["estadoPuestoLaboral"];

$consulta="UPDATE gyt_puesto SET pue_descripcion='$descripcionPuestoLaboral', pue_detalle='$detallePuestoLaboral', pue_estado='$estadoPuestoLaboral' WHERE id_puesto='$idPuestoLaboral';";
$updatepuesto = mysqli_query($conexion,$consulta);
///cho ($updateModelo) ? "true" : "false";
echo ($updatepuesto) ? json_encode(true) : json_encode(false) ;  
$conexion->close();
?>