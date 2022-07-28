<?php 
session_start();
require_once("../../conexion.php");

$descripcionPro = @$_POST["descripcionPro"];

$consulta = "INSERT INTO gyt_proyectos (pro_descripcion, pro_estado) VALUES ('$descripcionPro','ACTIVO')";
$addProyectos = mysqli_query($conexion,$consulta);
echo ($addProyectos) ? json_encode(true) : json_encode(false) ; 
$conexion->close();
?>