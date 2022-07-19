<?php 
# [[campo,idEquupo,idPADiactual],hola,hola]
function obtenerUltimasMediciones ($listaTipoMediciones, $idEquipo, $idParametroDiarioActual,$conexion)  {
  $arrayFinal = [];
  for ($i=0; $i < count($listaTipoMediciones); $i++) { 
    $consulta = "SELECT ".$listaTipoMediciones[$i][0].",PADI_id, ".$listaTipoMediciones[$i][1]." FROM (SELECT MAX(PADI_id) as maximoId ,EQCO_id,EQCO_tipo_medicion FROM parametros_diarios pd RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id WHERE EQU_id01=$idEquipo AND PADI_id <=$idParametroDiarioActual GROUP BY ".$listaTipoMediciones[$i][0]." ORDER BY ".$listaTipoMediciones[$i][0]." DESC LIMIT 2) as t1 INNER JOIN parametros_diarios pd ON t1.maximoId=pd.PADI_id";
    $response = mysqli_query($conexion, $consulta);
    foreach ($response as $x) {
      $medicion = $x[$listaTipoMediciones[$i][1]];
    }
    if (mysqli_num_rows($response)==1) {
      $medicion=0;
    }
    array_push($arrayFinal,$medicion);
  }
return $arrayFinal;
}
?>