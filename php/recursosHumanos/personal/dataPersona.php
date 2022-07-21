<?php 
require_once "../../conexion.php";

$idPersona = @$_POST["idPersona"];
$dataPersona = []; 
$resPersona = mysqli_query($conexion, "SELECT * FROM gyt_personas WHERE id_persona=$idPersona");
foreach ($resPersona as $k) {
  $dataPersona = [
    "idPersona" => $k["id_persona"],	
    "password" => $k["per_contrasenia"],		
    "tipoDoc" => $k["per_tipodoc"],	
    "nombres" => $k["per_nombres"],	
    "apellidos" => $k["per_apellidos"],	
    "sexo" => $k["per_sexo"],	
    "fNacimiento" => $k["per_fechanac"],		
    "lNacimiento" => $k["per_lugarnac"],		
    "estadoCivil" => $k["per_estadociv"],	
    "hijos" => $k["per_hijos"],		
    "email" => $k["per_email"],	
    "estudios" => $k["per_estudios"],	
    "direccion" => $k["per_direccion"],		
    "idDepartamento1" => $k["country_id"],		
    "idProvincia" => $k["state_id"],		
    "idDdistrito" => $k["city_id"],	
    "telefono" => $k["per_telefono"],	
    "sangre" => $k["per_sangre"],	
    "idPuesto" => $k["id_puesto"],	
    "idDepartamento2" => $k["id_departamento"],	
    "fechaIngreso" => $k["per_fechaingreso"],		
    "sueldo" => $k["per_sueldo"],	
    "bono" => $k["per_bono"],	
    "regimen" => $k["per_regimen"],	
    "regimenTransp" => $k["per_regimen_tra"],	
    "pension" => $k["per_pension"],	
    "cuspp" => $k["per_cuspp"],		
    "afp" => $k["per_afp"],	
    "flujo" => $k["per_flujo"],	
    "famNombre1" => $k["per_nombre1"],		
    "famParentesco1" => $k["per_parentesco1"],	
    "famCelular1" => $k["per_celular1"],	
    "famNombre2" => $k["per_nombre2"],		
    "famParentesco2" => $k["per_parentesco2"],	
    "famCelular2" => $k["per_celular2"],		
    "famNombre3" => $k["per_nombre3"],		
    "famParentesco3" => $k["per_parentesco3"],		
    "famCelular3" => $k["per_celular3"],		
    "banco" => $k["per_banco"],	
    "cuenta" => $k["per_cuenta"],		
    "cci" => $k["per_cci"]
  ];
}
echo json_encode($dataPersona);
?>