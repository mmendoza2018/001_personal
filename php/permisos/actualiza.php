<?php 
session_start();
require_once("../../conexion.php");

$id_motivo = @$_POST["id_motivo"];
$mot_descripcion = @$_POST["mot_descripcion"];
$mot_estado = @$_POST["mot_estado"];

$consulta="UPDATE gyt_motivos SET mot_descripcion='$mot_descripcion', mot_estado='$mot_estado' WHERE id_motivo='$id_motivo';";
$updateMotivo = mysqli_query($conexion,$consulta);
echo ($updateMotivo) ? json_encode(true) : json_encode(false) ;  
$conexion->close();

?>