<?php
require_once("../conexion.php");
require_once("parametros_diarios/alerta_mantenimiento.php");
require_once("parametros_diarios/obtenerMedicionPrincipal.php");
$conFamilia = mysqli_query($conexion, "SELECT DATE(PADI_fecha_tareo) as fecha,PADI_medicion_digital,PADI_medicion_kilometraje,PADI_medicion_analogico,PADI_man_siguiente,EQCO_id01,EQU_codigo,EQCO_id,EQU_id,FAM_descripcion,FAM_id,EQU_placa, CONTR_numero,CONTR_id,PROY_descripcion,CONTR_descripcion,EQCO_tipo_medicion,MOD_descripcion,CLIE_razon_social FROM (SELECT MAX(PADI_id) as maximoId FROM parametros_diarios GROUP BY EQCO_id01) as t1 
INNER JOIN parametros_diarios pd ON t1.maximoId=pd.PADI_id 
RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id 
INNER JOIN equipos e ON e.EQU_id=ec.EQU_id01 
INNER JOIN contratos c ON c.CONTR_id=ec.CONTR_id01 
INNER JOIN proyectos p ON p.PROY_id = c.PROY_id01
INNER JOIN familias f ON f.FAM_id=e.FAM_id01 
INNER JOIN modelos mo ON mo.MOD_id=e.MOD_id01 
INNER JOIN clientes cl ON cl.CLIE_id=c.CLIE_id01 
WHERE EQCO_estado=1 AND EQU_principal=1 AND EQCO_estado_contrato != 0 ORDER BY CONTR_descripcion DESC");
/* NOT IN('CIERRE Y FINALIZACIÓN') */
?>
<div>
  <h5> LISTADO EQUIPOS CONTRATO</h5>
</div>
<div class="container-fluid bg-white my-2 py-3">
  <div class="row d-flex justify-content-center">
    <div class="col-sm-10 col-md-12 col-lg-12">
      <div class="container-fluid ">
        <div class="table-responsive">
          <table id="tabla_lista_eContrato" class="table table-striped table-sm">
            <thead>
              <tr>
                <th>Código</th>
                <th>Familia</th>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Proyecto</th>
                <th>Cliente</th>
                <th>U. ingreso</th>
                <th>Alertas</th> 
                <th>D retraso</th>
                <th>P. Diario</th>
                <th>Mntto. sig</th>

                <th>B.hidraulico</th>
                <th>U. ingreso</th>
                <th>D retraso</th>
                <th>P. Diario</th>
                <th>Mntto. sig</th>
                <th>Alertas</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($conFamilia as $x) :
                $codigoBH = null;
                $existeBrazo = false;
                $codigoBH = null;
                $fechaUIngresoBH = null;
                $diasRetrasoBH = null;
                $medicionActualBH = null;
                $cambioSiguienteBH = null;
                $diaRetrazoPrincipal=null;
                $medicionActualEquipo=0;
                $idEquipoBH="";
                $conAlertaDiasPrincipal = mysqli_query($conexion,"SELECT DATEDIFF(NOW(),PADI_fecha_tareo) as diasRetraso FROM parametros_diarios pd RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01 = ec.EQCO_id WHERE EQU_id01 = '".$x["EQU_id"]."' ORDER BY PADI_id DESC LIMIT 1");
                foreach ($conAlertaDiasPrincipal as $y) { $diaRetrazoPrincipal = $y["diasRetraso"]; }
                $alertaPersonalizada = alertaMantenimiento($conexion, $x["FAM_id"], $x["EQU_id"]);
                $datosEquiContrato = $x["EQCO_id"] . "|" . $x["EQU_codigo"] . "|" . $x["CONTR_descripcion"] . "|" . $x["EQU_id"] . "|" . $x["CONTR_id"];
                $medicionActualEquipo = getMedicionPrincipal ($x["EQCO_tipo_medicion"],$x["PADI_medicion_digital"],$x["PADI_medicion_analogico"],$x["PADI_medicion_kilometraje"]);
                ($diaRetrazoPrincipal <= 0) ? $classCircle = "text-success" : $classCircle = "text-danger";
                $conBHidraulico = "SELECT DATEDIFF(NOW(),PADI_fecha_tareo) as diasRetrasoBH, DATE(PADI_fecha_tareo) as fecha,PADI_medicion_digital,PADI_man_siguiente,EQCO_id01,EQU_codigo,EQCO_id,EQU_id,FAM_descripcion,FAM_id,EQU_placa FROM (SELECT MAX(PADI_id) as maximoId FROM parametros_diarios GROUP BY EQCO_id01) as t1 INNER JOIN parametros_diarios pd ON t1.maximoId=pd.PADI_id RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id INNER JOIN equipos e ON e.EQU_id=ec.EQU_id01 INNER JOIN familias f ON f.FAM_id=e.FAM_id01 WHERE EQCO_estado=1 AND CONTR_id01= '".$x["CONTR_id"]."' AND EQU_union = '".$x["EQU_id"]."'";
                $resBHidraulico = mysqli_query($conexion, $conBHidraulico);
                if (mysqli_num_rows($resBHidraulico)>0) {
                  $existeBrazo = true;
                  foreach ($resBHidraulico as $k) {
                    $conAlertaDiasBH = mysqli_query($conexion,"SELECT DATEDIFF(NOW(),PADI_fecha_tareo) as diasRetraso FROM parametros_diarios pd RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01 = ec.EQCO_id WHERE EQU_id01 = '".$k["EQU_id"]."' ORDER BY PADI_id DESC LIMIT 1");
                    foreach ($conAlertaDiasBH as $y) { $diaRetrazoBH = $y["diasRetraso"]; }
                    $codigoBH = $k["EQU_codigo"];
                    $fechaUIngresoBH = $k["fecha"];
                    $diasRetrasoBH = $k["diasRetrasoBH"];
                    $medicionActualBH = $k["PADI_medicion_digital"];
                    $cambioSiguienteBH = $k["PADI_man_siguiente"];
                    $idEquipoContratoBH = $k["EQCO_id"];
                    $familiaBH = $k["FAM_id"];
                    $idEquipoBH = $k["EQU_id"];
                    ($diaRetrazoBH <= 0) ? $classCircleBH = "text-success" : $classCircleBH = "text-danger";
                  }
                  $alertaPersonalizadaBH = alertaMantenimiento($conexion,$familiaBH,$idEquipoBH);
                }
              ?>
                <tr>
                  <td data-bs-toggle="modal" data-bs-target="#modalPruebaZangdar"> <?php echo $x["EQU_codigo"] ?></td>
                  <td><?php echo $x["FAM_descripcion"] ?></td>
                  <td><?php echo $x["MOD_descripcion"] ?></td>
                  <td><?php echo $x["EQU_placa"] ?></td>
                  <td><?php echo $x["PROY_descripcion"] ?></td>
                  <td><?php echo $x["CLIE_razon_social"] ?></td>
                  <td><?php echo $x["fecha"] ?></td>
                  <td>
                    <span>
                      <?php echo $alertaPersonalizada[2] ?>
                      <span class=" text-dark text-decoration-none tooltips" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $alertaPersonalizada[0]["estado"] ?>">
                        <?php echo $alertaPersonalizada[0]["icono"] ?>
                      </span>
                      <span class="text-dark tooltips" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $alertaPersonalizada[1]["estado"] ?>">
                        <?php echo $alertaPersonalizada[1]["icono"] ?>
                      </span>
                    </span>
                  </td> 
                  <td>
                    <?php echo ($diaRetrazoPrincipal <= 0) ? "" : $diaRetrazoPrincipal ?>
                    <i class="fas fa-circle <?php echo $classCircle ?>"></i>
                  </td>
                  <td><?php echo $medicionActualEquipo ?></td>
                  <td class="border-end"><?php echo $x["PADI_man_siguiente"] ?></td>

                  <!-- campos del equipo secundario -->

                  <td><?php echo ($codigoBH) ? $codigoBH : ""; ?></td>
                  <td><?php echo $fechaUIngresoBH ?></td>
                  <td>
                    <?php echo ($diasRetrasoBH <= 0) ? "" : $diasRetrasoBH ?>
                    <?php echo ($existeBrazo) ? "<i class=\"fas fa-circle $classCircleBH\"></i>" : "" ?>
                    
                  </td>
                  <td><?php echo $medicionActualBH ?></td>
                  <td><?php echo $cambioSiguienteBH ?></td>
                  <td>
                    <?php if ($existeBrazo) { ?>
                      <span>
                        <span class=" text-dark text-decoration-none tooltips" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $alertaPersonalizada[0]["estado"] ?>">
                          <?php echo $alertaPersonalizadaBH[0]["icono"] ?>
                        </span>
                        <span class="text-dark tooltips" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $alertaPersonalizadaBH[1]["estado"] ?>">
                          <?php echo $alertaPersonalizadaBH[1]["icono"] ?>
                        </span>
                      </span>
                    </td>
                    <?php } ?>
                  <td><a href="#" data-bs-toggle="modal" data-bs-target="#modalAddPaDiario" data-idequipri="<?php echo  $x['EQU_id'] ?>"data-idequisec="<?php echo $idEquipoBH ?>" onclick="llenarDatosParametrosDiarios(this,true)">
                      <span class="badge rounded-pill bg-primary pb-1">P. diario</span>
                    </a>
                    <a href="#" onclick="verMantenimientosEquipo('<?php echo $x['EQU_id'] ?>','<?php echo $x['EQCO_id'] ?>')">
                      <i class="fas fa-chart-line text-dark"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- page-content" -->
<script>
  $(document).ready(function() {
    $('#tabla_lista_eContrato').DataTable({
      "info": false,
      "ordering":false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });
  });
  Tooltips();
</script>