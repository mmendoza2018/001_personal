<?php
function ultimoId ($conexion,$tabla,$idTabla) {
    $ultimoId=mysqli_query($conexion, "SELECT $idTabla FROM $tabla ORDER BY $idTabla DESC LIMIT 1");
    foreach ($ultimoId as $x) {  return $x[$idTabla]+1; }
}

?>