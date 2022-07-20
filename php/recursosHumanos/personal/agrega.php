<?php 
session_start();
require_once("../../conexion.php");

$tipdoc = @$_POST['tipdoc'];
$numdoc = @$_POST['numdoc'];
$nombres = @$_POST['nombres'];
$apellidos = @$_POST['apellidos'];
$sexo = @$_POST['sexo'];
$fecha_nac = @$_POST['fecha_nac'];
$lugar_nac = @$_POST['lugar_nac'];
$estado_civ = @$_POST['estado_civ'];
$hijo = @$_POST['hijo'];
$email = @$_POST['email'];
$estudios = @$_POST['estudios'];
$direccion = @$_POST['direccion'];
$Departamento = @$_POST['Departamento'];
$Provincia = @$_POST['Provincia'];
$Distrito = @$_POST['Distrito'];
$telefono = @$_POST['telefono'];
$sangre = @$_POST['sangre'];
$puesto = @$_POST['puesto'];
$depart = @$_POST['depart'];
$fecha = @$_POST['fecha'];
$sueldo = @$_POST['sueldo'];
$bono = @$_POST['bono'];
$regimen = @$_POST['regimen'];
$regimen_tra = @$_POST['regimen_tra'];
$pension = @$_POST['pension'];
$cuspp = @$_POST['cuspp'];
$afp = @$_POST['afp'];
$flujo = @$_POST['flujo'];
$nombre1 = @$_POST['nombre1'];
$parentesco1 = @$_POST['parentesco1'];
$celular1 = @$_POST['celular1'];
$nombre2 = @$_POST['nombre2'];
$parentesco2 = @$_POST['parentesco2'];
$celular2 = @$_POST['celular2'];
$nombre3 = @$_POST['nombre3'];
$parentesco3 = @$_POST['parentesco3'];
$celular3 = @$_POST['celular3'];
$banco = @$_POST['banco'];
$cuenta = @$_POST['cuenta'];
$cci = @$_POST['cci'];
$resPersona = mysqli_query($conexion, "SELECT id_persona FROM gyt_personas WHERE id_persona=$numdoc");
if (mysqli_num_rows($resPersona)>0) {
  echo json_encode([false,'Un Trabajador ya fue registrado con este DNI']);
  die();
}

$query_insert = mysqli_query($conexion, "INSERT INTO gyt_personas(id_persona,per_tipodoc,per_nombres,per_apellidos,per_sexo,per_fechanac,per_lugarnac,per_estadociv,per_hijos,per_email,per_estudios,per_direccion,country_id,state_id,city_id,per_telefono,per_sangre,id_puesto,id_departamento,per_fechaingreso,per_sueldo,per_bono,per_regimen,per_regimen_tra,per_pension,per_cuspp,per_afp,per_flujo,per_nombre1,per_parentesco1,per_celular1,per_nombre2,per_parentesco2,per_celular2,per_nombre3,per_parentesco3,per_celular3,per_banco,per_cuenta,per_cci)

VALUES('$numdoc','$tipdoc','$nombres','$apellidos','$sexo','$fecha_nac','$lugar_nac','$estado_civ','$hijo','$email','$estudios','$direccion','$Departamento','$Provincia','$Distrito','$telefono','$sangre','$puesto','$depart','$fecha','$sueldo','$bono','$regimen','$regimen_tra','$pension','$cuspp','$afp','$flujo','$nombre1','$parentesco1','$celular1','$nombre2','$parentesco2','$celular2','$nombre3','$parentesco3','$celular3','$banco','$cuenta','$cci')");
echo ($query_insert) ? json_encode([true,null]) : json_encode([false,null]); 

$conexion->close();
?>