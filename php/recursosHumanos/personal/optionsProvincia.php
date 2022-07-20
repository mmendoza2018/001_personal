
<?php
include("../../conexion.php");
$idDepartamento = $_POST["idSelect"];
$resCity=mysqli_query($conexion,"SELECT * FROM `state` WHERE country_id=$idDepartamento");
$optionCity .="<option value=''>-- SELECCIONE --</option>";
foreach ($resCity as $k) {
  $idCity = $k["id"];
  $descriptionCity = $k["state_name"];
  $optionCity .="<option value='$idCity'>$descriptionCity</option>";
}
echo $optionCity;
?>