<?php 
session_start();
require_once("../../../conexion.php");
require_once("../../../general/ultimoId.php");

$usuario = $_SESSION["nombre_trabajador"];
$id = ultimoId($conexion,"tipo_doc_equipos","TIDO_id");
$descripcion = @$_POST["descripcion"];

$consulta = "INSERT INTO tipo_doc_equipos (TIDO_descripcion) VALUES ('$descripcion');";
$consulta.="CALL agregar_historial('tipo_doc_equipos','$id','agrego','$usuario')";
$addDocEquipo = mysqli_multi_query($conexion,$consulta);
echo ($addDocEquipo) ? "true" : "false"; 
$conexion->close();
?>