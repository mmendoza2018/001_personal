<?php
require_once("../../conexion.php");
$idEquipo = $_POST["idEquipo"];
$medicionActual = $_POST["medicionActual"];
$tipoMedicion = $_POST["tipoMedicion"];
$contador = 1;
$medicionAnterior = 0;
$consulta = "SELECT $medicionActual,PADI_id,PADI_fecha_tareo, $tipoMedicion FROM (SELECT MAX(PADI_id) as maximoId ,EQCO_id,EQCO_tipo_medicion FROM parametros_diarios pd RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id WHERE EQU_id01=$idEquipo GROUP BY $tipoMedicion ORDER BY $tipoMedicion) as t1 INNER JOIN parametros_diarios pd ON t1.maximoId=pd.PADI_id"; 
$resConsulta = mysqli_query($conexion,$consulta);
$numFilas = mysqli_num_rows($resConsulta);
?>
<div class="table-responsive">
  <table id="tabla_registro_equipos" class="table table-striped">
    <thead>
      <tr>
        <th>Modulo</th>
        <th>Inicio</th>
        <th>Termino</th>
        <th>Total</th>
        <th>Fecha de Cambio</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($resConsulta as $k) : ?>
        <tr>
        <td><?php echo $k[$tipoMedicion] ?></td>
        <td><?php echo $medicionAnterior  ?></td>
        <td><?php echo $k[$medicionActual] ?></td>
        <td><?php echo $k[$medicionActual]-$medicionAnterior ?></td>
        <td><?php echo ($contador==$numFilas) ? '<span class="badge bg-success">Actual</span>' :$k['PADI_fecha_tareo'] ?></td>
        </tr>
      <?php $medicionAnterior = $k[$medicionActual]; $contador++; endforeach; ?>
    </tbody>
  </table>
</div>