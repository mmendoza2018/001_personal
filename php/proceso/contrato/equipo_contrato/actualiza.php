<?php 
session_start();
date_default_timezone_set("America/Lima");
require_once("../../../conexion.php");
$usuario = $_SESSION["nombre_trabajador"];
$idEquipoContrato = $_POST["idEquipoContrato"];
$idEquipo = @$_POST["idEquipo"];
$idContrato = @$_POST["idContrato"];
$tipoAccion = @$_POST["tipoAccion"];
$hoy = date("Y-m-d");
$fechaCierre = (isset($_POST["fechaCierre"]) ? $_POST["fechaCierre"] : NULL);
if ($tipoAccion=="eliminacion") {
  $campoActualizar = "EQCO_estado = 0, EQCO_estado_contrato = 0";
  $campoFechaAct = ",EQCO_fecha_cierre = '$hoy'";
}else if ($tipoAccion=="finalizacion") {
  $campoActualizar = "EQCO_estado_contrato = 0";
  $campoFechaAct = ",EQCO_fecha_cierre = '$fechaCierre'";
}else{
  echo "false";
  die();
}

$consulta = "";
$conEquipoSecundario = "SELECT EQCO_id FROM equipos_contrato ec INNER JOIN equipos e ON ec.EQU_id01 = e.EQU_id WHERE EQU_principal=0 AND EQU_union='$idEquipo' AND CONTR_id01='$idContrato'";
$resEquipoSecundario = mysqli_query($conexion,$conEquipoSecundario);
if (mysqli_num_rows($resEquipoSecundario)>0) {
  foreach (mysqli_query($conexion,$conEquipoSecundario) as $k) {
    $idEQCOSecundario = $k["EQCO_id"];
  }
  $consulta.="UPDATE equipos_contrato SET $campoActualizar, EQCO_usuario='$usuario' $campoFechaAct  WHERE EQCO_id = '$idEQCOSecundario';";
}
$consulta.="UPDATE equipos_contrato SET $campoActualizar, EQCO_usuario='$usuario' $campoFechaAct  WHERE EQCO_id = '$idEquipoContrato'";
$actualiza = mysqli_multi_query($conexion,$consulta);
echo ($actualiza) ? "true" : "false";
?>