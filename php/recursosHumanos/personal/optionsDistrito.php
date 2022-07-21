
<?php
include("../../conexion.php");
$idProvincia = $_POST["idSelect"];
$resDistrito = mysqli_query($conexion,"SELECT * FROM city WHERE state_id=$idProvincia");
$optionDistrito="";
$optionDistrito .="<option value=''>-- SELECCIONE --</option>";
foreach ($resDistrito as $k) {
  $idDistrito = $k["id"];
  $descriptionDistrito = $k["city_name"];
  $optionDistrito .="<option value='$idDistrito'>$descriptionDistrito</option>";
}
echo $optionDistrito;
?>