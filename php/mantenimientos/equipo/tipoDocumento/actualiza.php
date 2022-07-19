<?php 
session_start();
require_once("../../../conexion.php");

$usuario = $_SESSION["nombre_trabajador"];
$idAct = @$_POST["idAct"];
$descripcionAct = @$_POST["descripcionAct"];
$estadoAct = @$_POST["estadoAct"];
$consulta="UPDATE tipo_doc_equipos SET TIDO_descripcion='$descripcionAct', TIDO_estado='$estadoAct' WHERE TIDO_id='$idAct';";
$consulta.="CALL agregar_historial('tipo_doc_equipos','$idAct','actualizo','$usuario')";
$updateModelo = mysqli_multi_query($conexion,$consulta);
echo ($updateModelo) ? "true" : "false"; 
$conexion->close();

?>