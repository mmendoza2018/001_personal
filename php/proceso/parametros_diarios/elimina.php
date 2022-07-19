<?php
require_once("../../conexion.php");
$idParametroDiario = $_POST["idParametroDiario"];
$resOT = mysqli_query($conexion, "SELECT ORTR_id01 FROM parametros_diarios WHERE PADI_id = $idParametroDiario");
foreach ($resOT as $k) {
  $existeOT = $k["ORTR_id01"];
}
$consulta = "DELETE FROM parametros_diarios WHERE PADI_id = $idParametroDiario";
$resConsulta = mysqli_query($conexion, $consulta);
echo $resConsulta ? json_encode([true, $existeOT]) : json_encode(false);