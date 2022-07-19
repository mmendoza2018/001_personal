<?php 
require_once("../../conexion.php");
require_once("../../general/ultimoId.php");
require_once("../parametros_diarios/obtenerMedicionPrincipal.php");
$idContratoEquipo =@$_POST["idContratoEquipo"];
$idEquipo =@$_POST["idEquipo"];
$evento = @$_POST["evento"];
$tiposSistemas = @$_POST["idSistemas"];
$ultimaMedicionPrincipal = 0;
$arrayTipoSistemas = explode(",",$tiposSistemas);
    

$consulta="";
$ultimoParametro = mysqli_query($conexion, "SELECT PADI_medicion_digital,PADI_medicion_cambio,PADI_medicion_analogico,PADI_medicion_kilometraje,EQCO_tipo_medicion FROM parametros_diarios pd RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id WHERE EQU_id01='$idEquipo' ORDER BY PADI_id DESC LIMIT 1");
foreach ($ultimoParametro as $k) {
    $ultimaMedicionDigital = $k["PADI_medicion_digital"];
    $ultimaMedicionAnalogico = $k["PADI_medicion_analogico"];
    $ultimaMedicionKilometraje = $k["PADI_medicion_kilometraje"];
    $tipoMedicion = $k["EQCO_tipo_medicion"];
}
$ultimaMedicionPrincipal = getMedicionPrincipal ($tipoMedicion,$ultimaMedicionDigital,$ultimaMedicionAnalogico,$ultimaMedicionKilometraje);

$ultimoOrndeTrabajo = ultimoId($conexion,"ordenes_trabajo","ORTR_id");

$consulta = "INSERT INTO ordenes_trabajo (EQCO_id01,ORTR_tipo_evento,ORTR_medicion) VALUES ($idContratoEquipo,'$evento','$ultimaMedicionPrincipal');";

for ($i=0; $i < count($arrayTipoSistemas); $i++) { 
    $consulta.="INSERT INTO trabajos_realizar (TISI_id01,ORTR_id01) VALUES ('$arrayTipoSistemas[$i]','$ultimoOrndeTrabajo');";
}
substr($consulta, 0, -1);
echo (mysqli_multi_query($conexion,substr($consulta, 0, -1))) ? json_encode(["true",$ultimoOrndeTrabajo]) : json_encode(["false",null]);
$conexion->close();
?>