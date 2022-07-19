<?php 
function ExisteOtsPendientes ($conexion,$idEquipo){
  $estadoPenultimaOtCreada = null;
  /* comprobar si no hay una ot retrasada y por culminar */
  $dosUltimasOts = mysqli_query($conexion, "SELECT ORTR_id,ORTR_estado FROM ordenes_trabajo ot INNER JOIN equipos_contrato ec ON ot.EQCO_id01=ec.EQCO_id WHERE ORTR_tipo_evento='PREVENTIVO' AND EQU_id01='$idEquipo' ORDER BY ORTR_id DESC LIMIT 2");
  $existeDosOT = true;
  if(mysqli_num_rows($dosUltimasOts)<2) $existeDosOT = false;
  
  foreach ($dosUltimasOts as $k) {
          $estadoPenultimaOtCreada = $k["ORTR_estado"];
  }
  if ($estadoPenultimaOtCreada != "FINALIZADO" && $existeDosOT==true) {
    return true;
  }
  return false;
}
?>