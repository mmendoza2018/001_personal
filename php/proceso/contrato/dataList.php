<?php
require_once("../../conexion.php");
$descripcionContratoActual ="";
$html ="";
if (isset($_POST["idEquipoContrato"])) {
  $idEquipoContrato = @$_POST["idEquipoContrato"];
  # code...
  $contratoActual =  mysqli_query($conexion, "SELECT CONTR_descripcion FROM equipos_contrato ec INNER JOIN contratos c ON ec.CONTR_id01 = c.CONTR_id WHERE EQCO_id=$idEquipoContrato");
  foreach ($contratoActual as $k) { $descripcionContratoActual = $k["CONTR_descripcion"]; }
}
if(isset($_POST["idProyecto"])) {
  $idProyecto = $_POST["idProyecto"];
  $condicionalWhere = "WHERE PROY_id01 = $idProyecto";
} else {
  $condicionalWhere = "";
}
$contratos = mysqli_query($conexion, "SELECT CONTR_id,CONTR_descripcion FROM contratos $condicionalWhere");
foreach ($contratos as $x) :
  $id = $x["CONTR_id"];
  $descripcion = $x["CONTR_descripcion"];
  $html .= "<option data-value=\"$id\">$descripcion</option>";
 endforeach; 
 echo $descripcionContratoActual ."||".$html?>