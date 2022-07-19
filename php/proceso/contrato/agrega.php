<?php 
session_start();
require_once("../../conexion.php");
$usuario = $_SESSION["nombre_trabajador"];
$idProyecto = $_POST["idProyecto"];
$descripcion = $_POST["descripcion"];
$contrato = $_POST["contrato"];
$cliente = $_POST["cliente"];
$fechaInicio = $_POST["fechaInicio"];

$consulta = "INSERT INTO contratos (CONTR_descripcion,CONTR_numero,CLIE_id01,PROY_id01,CONTR_f_inicio,CONTR_estado,CONTR_usuario)
             VALUES('$descripcion','$contrato','$cliente','$idProyecto','$fechaInicio','INICIO DE CONTRATO','$usuario')";
$resConsulta = mysqli_query($conexion,$consulta);
echo ($resConsulta) ? "true" : "false";
?>