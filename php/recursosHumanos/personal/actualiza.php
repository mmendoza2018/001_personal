<?php 
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

$queryUpdate = mysqli_query($conexion, "UPDATE gyt_personas SET per_tipodoc = '$tipdoc',per_nombres = '$nombres',per_apellidos='$apellidos',per_sexo = '$sexo',per_fechanac = '$fecha_nac',per_lugarnac = '$lugar_nac',per_estadociv = '$estado_civ',per_hijos = '$hijo',per_email = '$email',per_estudios = '$estudios',per_direccion = '$direccion',country_id = '$Departamento',state_id = '$Provincia',city_id = '$Distrito',per_telefono = '$telefono',per_sangre = '$sangre',id_puesto = '$puesto',id_departamento = '$depart',per_fechaingreso = '$fecha',per_sueldo = '$sueldo',per_bono = '$bono',per_regimen = '$regimen',per_regimen_tra = '$regimen_tra',per_pension = '$pension',per_cuspp = '$cuspp',per_afp = '$afp',per_flujo = '$flujo',per_nombre1 = '$nombre1',per_parentesco1 = '$parentesco1',per_celular1 = '$celular1',per_nombre2 = '$nombre2',per_parentesco2 = '$parentesco2',per_celular2 = '$celular2',per_nombre3 = '$nombre3',per_parentesco3 = '$parentesco3',per_celular3 = '$celular3',per_banco = '$banco',per_cuenta = '$cuenta', per_cci = '$cci' WHERE id_persona='$numdoc'");
echo ($queryUpdate) ? json_encode(true) : json_encode(false); 

$conexion->close();
?>