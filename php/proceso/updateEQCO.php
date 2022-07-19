<?php 
require_once("../conexion.php");
$ingreso="";
$consulta = "SELECT * FROM equipos_contrato ec INNER JOIN equipos e ON e.EQU_id = ec.EQU_id01 WHERE EQU_principal = 1";
foreach (mysqli_query($conexion,$consulta) as $k) {
  $consulta2 = "SELECT * FROM equipos e WHERE EQU_principal = 0 AND EQU_union='".$k["EQU_id"]."'";
  $respuesta = mysqli_query($conexion,$consulta2);
  if (mysqli_num_rows($respuesta)>0) {
      foreach ($respuesta as $t) {
        $ingreso .= "INSERT INTO equipos_contrato (CONTR_id01,EQU_id01) VALUES ('".$k["CONTR_id01"]."','".$t["EQU_id"]."');";
      }
  }
}
$ingreso = substr($ingreso, 0, -1);
echo mysqli_multi_query($conexion,$ingreso);
echo $ingreso;
echo "hola";  
?>  