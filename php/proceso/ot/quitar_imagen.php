<?php 
session_start();
require_once("../../conexion.php");
$idImagen = @$_POST["idImagen"];
$usuario = $_SESSION["nombre_trabajador"];

$consulta = "UPDATE imagen_ots SET IMOT_estado=0, IMOT_usuario = '$usuario' WHERE IMOT_id='$idImagen'";

echo (mysqli_query($conexion,$consulta)) ? true :false;
mysqli_close($conexion);
?>