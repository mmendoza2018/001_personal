<?php 
session_start();
require_once("../../conexion.php");

$descripcionPuesto = @$_POST["descripcionPuesto"];
$detallePuesto = @$_POST["detallePuesto"];

$consulta = "INSERT INTO gyt_puesto (pue_descripcion,pue_detalle,pue_estado) 
             VALUES ('$descripcionPuesto','$detallePuesto','ACTIVO')";
$addPuestoLaboral = mysqli_query($conexion,$consulta);
echo ($addPuestoLaboral) ? json_encode(true) : json_encode(false) ; 
$conexion->close();
?>
