<?php 
session_start();
require_once("../../conexion.php");

$id_proyecto = @$_POST["id_proyecto"];
$pro_descripcion = @$_POST["pro_descripcion"];
$pro_estado = @$_POST["pro_estado"];

$consulta="UPDATE gyt_proyectos SET pro_descripcion='$pro_descripcion', pro_estado='$pro_estado' WHERE id_proyecto='$id_proyecto';";
$updateProyecto = mysqli_query($conexion,$consulta);

echo ($updateProyecto) ? json_encode(true) : json_encode(false) ;  
$conexion->close();

?>