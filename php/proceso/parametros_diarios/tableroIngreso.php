<?php
require_once("../../conexion.php");
require_once("obtenerUltimaMedicion.php");
$idEquipo = $_POST["idEquipo"];
$registro = @$_POST["registro"];
$modoPeticion = @$_POST["modoPeticion"];
$secuencia = @$_POST["secuencia"];
$fechaActualReferencia = @$_POST["fechaActualReferencia"];
$fechaBusquedaChange = @$_POST["fechaBusquedaChange"];
$medicionAnalogico = "0";
$medicionDigital = "0";
$estadoCompraRepuesto = 0;
$configuracionInicial = false;
$medicionAnalogico = 0;
$medicionDigital = 0;
$medicionKilometraje = 0;
$fechaTareoActual = null;
$medicionDigitalAnterior = 0;
$medicionAnalogicoAnterior = 0;
$medicionKilometrajeAnterior = 0;
$descripcionActual = null;
$estadoEquipoActual = "OPERATIVO";
$contador = 0;
$idPDActual = false;
$turnoActual = false;
$trabajadorActual = "";
$idContratoActual =null;
$operadores = mysqli_query($conexion, "SELECT TRAB_id,TRAB_nombres FROM trabajadores t INNER JOIN cargos c ON t.CAR_id01=c.CAR_id WHERE CAR_descripcion  LIKE '%OPERADOR%'");
$datosEquipo = mysqli_query($conexion, "SELECT EQU_codigo,EQU_placa,EQU_principal,FAM_descripcion FROM equipos e INNER JOIN familias f ON e.FAM_id01=f.FAM_id WHERE EQU_id='$idEquipo'");
foreach ($datosEquipo as $x) {
  $equipoCodigo = $x["EQU_codigo"];
  $equipoPlaca = $x["EQU_placa"];
  $equipoPrincipal = $x["EQU_principal"];
  $equipoFamilia = $x["FAM_descripcion"];
}

if ($registro == "false") {
  if ($modoPeticion == "date") {
    $conPDPersonalizado = "SELECT * FROM parametros_diarios pd 
    LEFT JOIN equipos_contrato ec ON pd.EQCO_id01 = ec.EQCO_id
    LEFT JOIN trabajadores t ON pd.TRAB_id01=t.TRAB_id
    WHERE PADI_fecha_tareo ='$fechaBusquedaChange' AND EQU_id01='$idEquipo'
    ORDER BY PADI_fecha_tareo DESC LIMIT 1";
  } else {
    if ($fechaActualReferencia == null) {
      if ($secuencia == "siguiente") {
        echo "false||No hay registros disponibles para $equipoCodigo";
        die();
      }
      $conPDPersonalizado = "SELECT * FROM parametros_diarios pd 
     LEFT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id
     LEFT JOIN trabajadores t ON pd.TRAB_id01=t.TRAB_id
     WHERE EQU_id01='$idEquipo' 
     ORDER BY PADI_id DESC LIMIT 1";
    } else {
      ($secuencia == "siguiente")
        ? $whereCustomize = " WHERE PADI_fecha_tareo > '$fechaActualReferencia' AND EQU_id01='$idEquipo' ORDER BY PADI_fecha_tareo ASC LIMIT 1"
        : $whereCustomize = " WHERE PADI_fecha_tareo < '$fechaActualReferencia' AND EQU_id01='$idEquipo' ORDER BY PADI_fecha_tareo DESC LIMIT 1";
      $conPDPersonalizado = "SELECT * FROM parametros_diarios pd 
      LEFT JOIN equipos_contrato ec ON pd.EQCO_id01 = ec.EQCO_id
      LEFT JOIN trabajadores t ON pd.TRAB_id01=t.TRAB_id
      $whereCustomize
      ";
    }
  }
  $respuestaConPD = mysqli_query($conexion, $conPDPersonalizado);
  mysqli_num_rows($respuestaConPD);

  if (mysqli_num_rows($respuestaConPD) <= 0) {
    echo "false||No hay registros disponibles para $equipoCodigo";
    die();
  }
  foreach (mysqli_query($conexion, $conPDPersonalizado) as $k) {
    $idPDActual = $k["PADI_id"];
    $equipoContratoActual = $k["EQCO_id01"];
    $tipoMdigitalActual = $k["PADI_tipo_m_digital"];
    $tipoMdAnalogicoActual = $k["PADI_tipo_m_analogico"];
    $tipoMdKilometrajeActual = $k["PADI_tipo_m_kilometraje"];
    $medicionDitalActual = floatval($k["PADI_medicion_digital"]);
    $medicionAnalogicoActual = floatval($k["PADI_medicion_analogico"]);
    $medicionKilometrajeActual = floatval($k["PADI_medicion_kilometraje"]);
    $horasTrabajoActual = floatval($k["PADI_horas_trabajo"]);
    $trabajadorActual = ($k["TRAB_nombres"] == null) ? "" : $k["TRAB_nombres"];
    $estadoEquipoActual = $k["PADI_estado_equipo"];
    $turnoActual = $k["PADI_turno"];
    $usuarioActual = $k["PADI_usuario"];
    $descripcionActual = $k["PADI_descripcion_estado"];
    $fechaTareoActual = $k["PADI_fecha_tareo"];
    $idContratoActual = $k["CONTR_id01"];
    $tipoMedicionEquipoHistorialActual = $k["EQCO_tipo_medicion"];
    
  }
  $listaMedicionesAnt = obtenerUltimasMediciones([["PADI_tipo_m_digital", "PADI_medicion_digital"], ["PADI_tipo_m_analogico", "PADI_medicion_analogico"], ["PADI_tipo_m_kilometraje", "PADI_medicion_kilometraje"]], $idEquipo, $idPDActual, $conexion);
  [$medicionGenDitalActual, $medicionGenAnalogicoActual, $medicionGenKilometrajeActual] = $listaMedicionesAnt;
}

$conUltimoPD = "SELECT PADI_medicion_digital,PADI_id,PADI_tipo_m_digital,PADI_tipo_m_analogico,PADI_tipo_m_kilometraje,PADI_medicion_analogico,PADI_medicion_kilometraje,PADI_estado_compra_r,CONTR_id01,PADI_compra_r,EQCO_id,EQCO_tipo_medicion,DATE(PADI_fecha_tareo) as ultimoFechaIngreso FROM parametros_diarios pd RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id WHERE EQU_id01='$idEquipo' ORDER BY PADI_id DESC LIMIT 1";
$ultimoIngreso = mysqli_query($conexion, $conUltimoPD);
foreach ($ultimoIngreso as $k) {
  $medicionDigital = floatval($k["PADI_medicion_digital"]);
  $medicionAnalogico = floatval($k["PADI_medicion_analogico"]);
  $medicionKilometraje = floatval($k["PADI_medicion_kilometraje"]);
  $tipoMdigital = $k["PADI_tipo_m_digital"];
  $tipoMdAnalogico = $k["PADI_tipo_m_analogico"];
  $tipoMdKilometraje = $k["PADI_tipo_m_kilometraje"];
  $idPD = $k["PADI_id"];
  $rangoCompraRepuesto = $k["PADI_compra_r"];
  $estadoCompraRepuesto = $k["PADI_estado_compra_r"];
  $idEquipoContratoAnterior = $k["EQCO_id"];
  $ultimoTipoMedicionEquipo = $k["EQCO_tipo_medicion"];
  $ultimoFechaIngreso = ($k["ultimoFechaIngreso"] == null) ? "Sin registros" : $k["ultimoFechaIngreso"];
}
if ($idPD != null) {
  $listaMedicionesAnt2 = obtenerUltimasMediciones([["PADI_tipo_m_digital", "PADI_medicion_digital"], ["PADI_tipo_m_analogico", "PADI_medicion_analogico"], ["PADI_tipo_m_kilometraje", "PADI_medicion_kilometraje"]], $idEquipo, $idPD, $conexion);
  [$medicionGenDigitalAnterior, $medicionGenAnalogicoAnterior, $medicionGenKilometrajeAnterior] = $listaMedicionesAnt2;
}

$conTipoMedicion = mysqli_query($conexion, "SELECT EQCO_tipo_medicion,EQCO_id,CONTR_id01 FROM equipos_contrato WHERE EQU_id01='$idEquipo' ORDER BY EQCO_id DESC LIMIT 1");
foreach ($conTipoMedicion as $x) {
  $tipoMedicionEquipoActual = $x["EQCO_tipo_medicion"];
  $idEquipoContratoActual = $x["EQCO_id"];
  $idContratoReciente = $x["CONTR_id01"];
}
$estadoActualizacion = ($idContratoActual == $idContratoReciente ) ? 'true' : 'false';
if ($registro == "false") {
  $tipoMedicionEquipoActual = $tipoMedicionEquipoHistorialActual;
}
//obtenemos con que frecuencia se trabajara
$tipoFrecuenciaActual = ($tipoMedicionEquipoActual == "Horometro digital" || $tipoMedicionEquipoActual == "Horometro analogico")
  ? "Horometro"
  : "Kilometraje";

//asignamos la medicion con la que trabajara
if ($tipoMedicionEquipoActual == "Horometro digital") {
  $medicionActualPrincipal = $medicionDigital;
} else if ($tipoMedicionEquipoActual == "Horometro analogico") {
  $medicionActualPrincipal = $medicionAnalogico;
} else {
  $medicionActualPrincipal = $medicionKilometraje;
}

 $conIngreso = "SELECT EQCO_fecha_ingreso_contrato,PADI_medicion_digital, PADI_medicion_analogico,EQCO_id,PADI_medicion_kilometraje FROM equipos_contrato ec LEFT JOIN parametros_diarios pd ON ec.EQCO_id = pd.EQCO_id01 WHERE EQCO_id=$idEquipoContratoActual AND EQCO_estado=1 GROUP BY EQCO_id01";
  foreach (mysqli_query($conexion, $conIngreso) as $k) {
    $fechaIngresoContrato = $k["EQCO_fecha_ingreso_contrato"];
    $medicionDigitalIngresoContrato = $k["PADI_medicion_digital"];
    $medicionAnalogicoIngresoContrato = $k["PADI_medicion_analogico"];
    $medicionKilometrajeIngresoContrato = $k["PADI_medicion_kilometraje"];
  }
  //asignamos la medicion con la que trabajara
  if ($tipoMedicionEquipoActual == "Horometro digital") {
    $medicionIngresoContrato = $medicionDigitalIngresoContrato;
  } else if ($tipoMedicionEquipoActual == "Horometro analogico") {
    $medicionIngresoContrato = $medicionAnalogicoIngresoContrato;
  } else {
    $medicionIngresoContrato = $medicionKilometrajeIngresoContrato;
  }
  $medicionIngresoContrato = $medicionIngresoContrato === NULL ? 'Sin registros': $medicionIngresoContrato;
#determina si se cambio de contrato el equipo o es el primer ingreso en los parametros diarios
if ((($idEquipoContratoAnterior != $idEquipoContratoActual)) || $medicionActualPrincipal == 0) {
  $configuracionInicial = true;
}
$esUltimoRegistro = $idPD === $idPDActual;
//$existePDiario = mysqli_num_rows($ultimoIngreso);
$conListaMantenimiento = "SELECT EQMA_id,TIMA_tiempo,TIMA_tipo_medicion FROM equipo_mantenimiento em 
            INNER JOIN tiempo_mantenimiento tm ON em.TIMA_id01=tm.TIMA_id 
            WHERE EQU_id01='$idEquipo' AND TIMA_estado=1 AND EQMA_estado=1 AND TIMA_tipo_medicion='$tipoFrecuenciaActual'";

$listaMantenimientos = mysqli_query($conexion, $conListaMantenimiento);
$claseColumna = ($equipoPrincipal == "1") ? 'col-sm-6' : 'col-sm-12';
  #var_dump($configuracionInicial);
?>
  <span 
  tabindex="0" 
  class="badge bg-secondary popover-pd<?php echo $idEquipoContratoActual?>" 
  role="button" 
  data-bs-toggle="popover" 
  data-bs-trigger="focus" 
  title="Fecha de ingreso: <?php echo $fechaIngresoContrato  ?>" 
  data-bs-content="<?php echo $tipoMedicionEquipoActual . " : " . $medicionIngresoContrato ?>">Ingreso al contrato</span>
<form id="formAgregaParametroDiario<?php echo $idEquipoContratoActual ?>">
  <div class="row g-3">
    <div class="text-center mb-2">
      <b class="fw-bold"><?php echo $equipoFamilia . " / " . $equipoPlaca; ?></b>
    </div>
    <input type="hidden" name="ingresoMedicionActual" id="keyNuevoIngresoPD" value="<?php echo ($medicionActualPrincipal === null) ? "true" : "false"; ?>">
    <!--  -->
    <?php if (($configuracionInicial && $registro == "true") || ($trabajadorActual === "" && $idPD === $idPDActual)) { ?>
      <div class="row">
        <div class="<?php echo  $claseColumna ?>">
          <label class="mb-1"> Mantenimiento siguiente del equipo</label>
          <select list="listaTiempoMantenimiento" id="idTimaPD" data-validate class="form-select form-select-sm">
            <option value="" selected disabled>Seleccione un mantenimiento</option>
            <?php foreach ($listaMantenimientos as $x) : ?>
              <option value="<?php echo $x["EQMA_id"] ?>"><?php echo $x["TIMA_tiempo"] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="<?php echo $claseColumna ?>">
          <label class="mb-1"><?php echo $tipoFrecuenciaActual ?> <span class="fw-bold">general</span> del ultimo mantenimiento</label>
          <input type="number" class="form-control form-control-sm mb-2" id="configMedicionUltMant" data-validate name="medicionUltimoMantenimiento">
        </div>
      </div>
    <?php } ?>
    <!--  -->
    <div class=" <?php
                  echo ($equipoPrincipal == "1") ? 'col-sm-4' : 'col-sm-12';
                  echo ($tipoMedicionEquipoActual == 'Horometro digital' && $equipoPrincipal == '1') ? ' bg-primary-opacity border border-1' : ''; ?>">
      <input type="hidden" name="idEquipoContrato" value="<?php echo $idEquipoContratoActual ?>">
      <input type="hidden" id="dataConfigPD" name="idEquipo" value="<?php echo $idEquipo ?>">
      <input type="hidden" name="idParametroDiario" value="<?php echo $idPDActual ?>">
      <p class="text-center"><b>H. Digital</b></p>
      <label class="mb-1">Horometro anterior</label>
      <input 
      type="number" 
      class="form-control form-control-sm mb-2" 
      name="MedicionAnterior" 
      value="<?php if ($registro == "true") {
                      echo $medicionDigital - $medicionGenDigitalAnterior;
                    } else {
                      echo ($tipoMedicionEquipoActual == 'Horometro digital')
                        ? ($medicionDitalActual) - ($medicionGenDitalActual) - ($horasTrabajoActual)
                        : "";
                    } ?>" 
      id="medicionAnteriorDigital<?php echo $idEquipoContratoActual ?>" 
      readonly>
      <label class="mb-1">Horas trabajadas </label>
      <input 
      type="number" 
      onkeyup="limitarLongitudNumero(this)" 
      oninput="sumarMedicionActual(this,'<?php echo 'medicionAnteriorDigital' . $idEquipoContratoActual ?>','<?php echo 'medicionActualDigital' . $idEquipoContratoActual ?>','<?php echo 'medicionGDigital' . $idEquipoContratoActual  ?>')" class="form-control form-control-sm mb-2" 
      id="horaKiloMedicionAddAnalogico" 
      data-mecion_trab_val = "<?php ($idPD === $idPDActual) ? $horasTrabajoActual : "0";  ?>"
      data-general="<?php echo $medicionDigital ?>" 
      <?php
      if($registro == "true") {
        if ($configuracionInicial) {
          echo "readonly";
        } else {
          if ($tipoMedicionEquipoActual == 'Horometro digital') {
            echo "data-validate";
          } else {
            echo "readonly";
          }
        }
      }else
      if($idPD === $idPDActual) {
        if ($trabajadorActual !== "") {
          if ($tipoMedicionEquipoActual == 'Horometro digital') {
            echo "data-validate";
          } else {
            echo "readonly";
          }
        } else {
          echo "readonly";
        }
      }else {
        echo "readonly";
      }
      ?>  
      name="<?php echo ($tipoMedicionEquipoActual == 'Horometro digital') ? 'medicionHoy' : '' ?>" 
      value="<?php if ($registro == "true") {
                      echo "";
                    } else {
                      echo ($tipoMedicionEquipoActual == 'Horometro digital')
                        ? $horasTrabajoActual
                        : "";
                    }
                    ?>">
      <label class="mb-1">Horometro actual</label>
      <input 
      type="number" 
      class="form-control form-control-sm mb-2" 
      value="<?php 
      echo ($registro == "true") 
      ? $medicionDigital - $medicionGenDigitalAnterior 
      : ($medicionDitalActual) - ($medicionGenDitalActual)?>" 
      readonly 
      data-med_actual_principal="<?php echo ($tipoMedicionEquipoActual == 'Horometro digital') ? "true" : "false";  ?>" oninput="sumaActmedicionActual(this,'<?php echo 'medicionGDigital' . $idEquipoContratoActual  ?>')" ; data-med_actual_val="<?php 
      echo ($registro == "true") 
      ? $medicionDigital - $medicionGenDigitalAnterior 
      : ($medicionDitalActual) - ($medicionGenDitalActual)?>" 
      <?php echo ($registro == "true" && $configuracionInicial) ? "" : "data-validate" ?> 
      id="medicionActualDigital<?php echo $idEquipoContratoActual ?>">
      <label class="mb-1">Medidor digital</label>
      <select class="form-select form-select-sm" data-validate name="medidorDigital" id="medidorDigitalPD<?php echo $idEquipoContratoActual ?>" <?php echo ($registro == "true" || $idPD === $idPDActual) ? "" : "disabled" ?>>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
      </select>
      <label class="mb-1">Horometro general</label>
      <input 
      type="number" 
      value="<?php echo ($registro == "true") ? $medicionDigital : $medicionDitalActual ?>" 
      data-med_general_val="<?php echo ($registro == "true") ? $medicionDigital : $medicionDitalActual ?>" 
      <?php echo ($configuracionInicial || ($trabajadorActual === "" && $idPD === $idPDActual)) 
        ? "data-validate" 
        : "readonly"; ?> 
      name="medicionActualDigital" class="form-control form-control-sm" 
      data-medicionprincipal="<?php echo ($tipoMedicionEquipoActual == 'Horometro digital') ? "true" : "false";  ?>" 
      id="<?php echo 'medicionGDigital' . $idEquipoContratoActual  ?>">
      <div class="text-center">
        <a href="#" data-bs-toggle="modal" data-bs-target="#modalHistorialMedidoresPD" onclick="verHistorialMedidores('PADI_medicion_digital','PADI_tipo_m_digital','<?php echo $idEquipo ?>')">
          ver historial
        </a>
      </div>
    </div>
    <?php if ($equipoPrincipal == "1") { ?>
      <div class="col-sm-4 <?php echo ($tipoMedicionEquipoActual == 'Horometro analogico') ? 'bg-primary-opacity border border-1' : '' ?>">
        <p class="text-center"><b>H. Analogico</b></p>
        <label class="mb-1">Horometro anterior</label>
        <input 
        type="number" 
        class="form-control form-control-sm mb-2" 
        name="MedicionAnterior" 
        value="<?php if ($registro == "true") {
            echo $medicionAnalogico - $medicionGenAnalogicoAnterior;
          } else {
            echo ($tipoMedicionEquipoActual == 'Horometro analogico')
              ? ($medicionAnalogicoActual) - ($medicionGenAnalogicoActual) - ($horasTrabajoActual)
              : "";
          } ?>" id="medicionAnteriorAnalogico<?php echo $idEquipoContratoActual ?>" 
        readonly>
        <label class="mb-1"> Horas trabajadas </label>
        <input 
        type="number" 
        oninput="sumarMedicionActual(this,'<?php echo 'medicionAnteriorAnalogico' . $idEquipoContratoActual ?>','<?php echo 'medicionActualAnalogico' . $idEquipoContratoActual ?>','<?php echo 'medicionGAnalogico' . $idEquipoContratoActual  ?>')" 
        data-mecion_trab_val = "<?php echo ($idPD === $idPDActual) ? $horasTrabajoActual : "0";  ?>"
        class="form-control form-control-sm mb-2" 
        data-general="<?php echo $medicionAnalogico ?>" 
        <?php
      if($registro == "true") {
        if ($configuracionInicial) {
          echo "readonly";
        } else {
          if ($tipoMedicionEquipoActual == 'Horometro analogico') {
            echo "data-validate";
          } else {
            echo "readonly";
          }
        }
      }else
      if($idPD === $idPDActual) {
        if ($trabajadorActual !== "") {
          if ($tipoMedicionEquipoActual == 'Horometro analogico') {
            echo "data-validate";
          } else {
            echo "readonly";
          }
        } else {
          echo "readonly";
        }
      }else {
        echo "readonly";
      }
      ?>  
        id="horaKiloMedicionAddDigital" 
        name="<?php echo ($tipoMedicionEquipoActual == 'Horometro analogico') ? 'medicionHoy' : '' ?>" 
        value="<?php if ($registro == "true") {
                       echo "";
                      } else {
                        echo ($tipoMedicionEquipoActual == 'Horometro analogico')
                         ? $horasTrabajoActual
                         : ""; 
                      } ?>" 
        onkeyup="limitarLongitudNumero(this)">
        <label class="mb-1">Horometro actual</label>
        <input 
        type="number" 
        class="form-control form-control-sm mb-2" 
        value="<?php 
        echo ($registro == "true") 
          ? $medicionAnalogico - $medicionGenAnalogicoAnterior 
          : ($medicionAnalogicoActual) - ($medicionGenAnalogicoActual)?>" 
        readonly 
        data-med_actual_principal="<?php echo ($tipoMedicionEquipoActual == 'Horometro analogico') ? "true" : "false";  ?>" oninput="sumaActmedicionActual(this,'<?php echo 'medicionGAnalogico' . $idEquipoContratoActual  ?>')" ; data-med_actual_val="<?php 
        echo ($registro == "true") 
          ? $medicionAnalogico - $medicionGenAnalogicoAnterior 
          : ($medicionAnalogicoActual) - ($medicionGenAnalogicoActual)?>" 
        <?php echo ($registro == "true" && $configuracionInicial) ? "" : "data-validate" ?> 
        id="medicionActualAnalogico<?php echo $idEquipoContratoActual ?>">
        <label class="mb-1">Medidor Analogico</label>
        <select class="form-select form-select-sm" data-validate name="medidorAnalogico" id="medidorAnalogicoPD<?php echo $idEquipoContratoActual ?>" <?php echo ($registro == "true" || $idPD === $idPDActual) ? "" : "disabled" ?>>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>
        <label class="mb-1">Horometro general</label>
        <input 
        type="number" 
        value="<?php echo ($registro == "true") ? $medicionAnalogico : $medicionAnalogicoActual ?>" 
        <?php echo ($configuracionInicial || ($trabajadorActual === "" && $idPD === $idPDActual)) 
        ? "data-validate" 
        : "readonly"; ?>  
        name="medicionActualAnalogico" class="form-control form-control-sm" 
        data-med_general_val="<?php echo ($registro == "true") ? $medicionAnalogico : $medicionAnalogicoActual ?>" data-medicionprincipal="<?php echo ($tipoMedicionEquipoActual == 'Horometro analogico') ? "true" : "false";  ?>" 
        id="<?php echo 'medicionGAnalogico' . $idEquipoContratoActual  ?>">
        <div class="text-center">
          <a href="#" data-bs-toggle="modal" data-bs-target="#modalHistorialMedidoresPD" onclick="verHistorialMedidores('PADI_medicion_analogico','PADI_tipo_m_analogico','<?php echo $idEquipo ?>')">
            ver historial
          </a>
        </div>
      </div>
      <div class="col-sm-4 <?php echo ($tipoMedicionEquipoActual == 'Kilometraje') ? 'bg-primary-opacity border border-1' : '' ?>">
        <p class="text-center"><b>Kilometraje</b></p>
        <label class="mb-1">Kilometraje anterior</label>
        <input 
        type="number" 
        class="form-control form-control-sm mb-2" 
        name="MedicionAnterior" 
        value="<?php if ($registro == "true") {
              echo $medicionKilometraje - $medicionGenKilometrajeAnterior;
            } else {
              echo ($tipoMedicionEquipoActual == 'Kilometraje')
                ? ($medicionKilometrajeActual) - ($medicionGenKilometrajeActual) - ($horasTrabajoActual)
                : "";
            } ?>" 
        id="medicionAnteriorKilometraje<?php echo $idEquipoContratoActual ?>" 
        readonly>
        <label class="mb-1"> Kilometros trabajados </label>
        <input 
        type="number" 
        oninput="sumarMedicionActual(this,'<?php echo 'medicionAnteriorKilometraje' . $idEquipoContratoActual ?>','<?php echo 'medicionActualKilometraje' . $idEquipoContratoActual ?>','<?php echo 'medicionGKilometro' . $idEquipoContratoActual  ?>')" 
        data-mecion_trab_val = "<?php echo ($idPD === $idPDActual) ? $horasTrabajoActual : "0";  ?>"
        data-general="<?php echo $medicionKilometraje ?>" 
        class="form-control form-control-sm mb-2" 
        <?php
      if($registro == "true") {
        if ($configuracionInicial) {
          echo "readonly";
        } else {
          if ($tipoMedicionEquipoActual == 'Kilometraje') {
            echo "data-validate";
          } else {
            echo "readonly";
          }
        }
      }else
      if($idPD === $idPDActual) {
        if ($trabajadorActual !== "") {
          if ($tipoMedicionEquipoActual == 'Kilometraje') {
            echo "data-validate";
          } else {
            echo "readonly";
          }
        } else {
          echo "readonly";
        }
      }else {
        echo "readonly";
      }
      ?> 
          id="horaKiloMedicionAddDigital" 
          name="<?php echo ($tipoMedicionEquipoActual == 'Kilometraje') ? 'medicionHoy' : '' ?>" 
          value="<?php if ($registro == "true") {
                          echo '';
                        } else {
                          echo ($tipoMedicionEquipoActual == 'Kilometraje')
                            ? $horasTrabajoActual
                            : '';
                        } ?>">
        <label class="mb-1">Kilometraje actual</label>
        <input 
        type="number" 
        class="form-control form-control-sm mb-2" 
        value="<?php 
        echo ($registro == "true") 
          ? $medicionKilometraje - $medicionGenKilometrajeAnterior 
          : ($medicionKilometrajeActual) - ($medicionGenKilometrajeActual)?>" 
        readonly 
        data-med_actual_principal="<?php echo ($tipoMedicionEquipoActual == 'Kilometraje') ? "true" : "false";  ?>" oninput="sumaActmedicionActual(this,'<?php echo 'medicionGKilometro' . $idEquipoContratoActual  ?>')" ; data-med_actual_val="<?php 
        echo ($registro == "true") 
          ? $medicionKilometraje - $medicionGenKilometrajeAnterior 
          : ($medicionKilometrajeActual) - ($medicionGenKilometrajeActual)?>" 
        <?php echo ($registro == "true" && $configuracionInicial) ? "" : "data-validate" ?> 
        id="medicionActualKilometraje<?php echo $idEquipoContratoActual ?>">
        <label class="mb-1">Medidor kilometraje</label>
        <select class="form-select form-select-sm" data-validate name="medidorKilometraje" id="medidorKilometrajePD<?php echo $idEquipoContratoActual ?>" <?php echo ($registro == "true" || $idPD === $idPDActual) ? "" : "disabled" ?>>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>
        <label class="mb-1">Kilometraje general</label>
        <input 
        type="number" 
        value="<?php echo ($registro == "true") ? $medicionKilometraje : $medicionKilometrajeActual ?>" 
        <?php echo ($configuracionInicial || ($trabajadorActual === "" && $idPD === $idPDActual)) 
        ? "data-validate" 
        : "readonly"; ?> 
        name="medicionActualKilometraje" 
        class="form-control form-control-sm" 
        data-med_general_val="<?php echo ($registro == "true") ? $medicionKilometraje : $medicionKilometrajeActual ?>" data-medicionprincipal="<?php echo ($tipoMedicionEquipoActual == 'Kilometraje') ? "true" : "false";  ?>" 
        id="<?php echo 'medicionGKilometro' . $idEquipoContratoActual  ?>">
        <div class="text-center">
          <a href="#" data-bs-toggle="modal" class="mx-0 text" data-bs-target="#modalHistorialMedidoresPD" onclick="verHistorialMedidores('PADI_medicion_kilometraje','PADI_tipo_m_kilometraje','<?php echo $idEquipo ?>')">
            ver historial
          </a>
        </div>
      </div>
    <?php } ?>

  </div>
  <hr>
  <div class="row align-items-start">
    <?php if (!$configuracionInicial && ($trabajadorActual !== "" || $registro == "true")) { ?>
      <div class="<?php echo $claseColumna; ?>">
        <label class="mb-1">Operador</label>
        <input 
        type="text" 
        class="form-control form-control-sm mb-2 listaOperadorPDOt" 
        autocomplete="off" 
        list="listaOperadorPDOt" 
        data-validate 
        id="trabajadorOTAct" 
        <?php if ($registro == "true") {
           echo "";
           } else {
             if ($idPD === $idPDActual) {
              echo strlen($turnoActual) <= 0 ? "readonly" : "";
              } else {
                echo "readonly";
                }
           } ?>
        value="<?php echo ($registro == "false") ? $trabajadorActual : "";  ?>">
        <datalist id="listaOperadorPDOt">
          <?php foreach ($operadores as $x) : ?>
            <option data-value="<?php echo $x["TRAB_id"] ?>"><?php echo $x["TRAB_nombres"] ?></option>
          <?php endforeach; ?>
        </datalist>
        <label class="mb-1">Turno</label>
        <select class="form-select form-select-sm" name="turno" data-validate id="turnoPD<?php echo $idEquipoContratoActual ?>" <?php if ($registro == "true") {
           echo "";
           } else {
             if ($idPD === $idPDActual) {
              echo strlen($turnoActual) <= 0 ? "disabled" : "";
              } else {
                echo "disabled";
                }
           } ?>>
          <option value="" selected>Seleccione una opcion</option>
          <option value="DÍA">DÍA</option>
          <option value="NOCHE">NOCHE</option>
        </select>
      </div>
    <?php } ?>
    <div class="<?php echo $claseColumna ?> ">
      <label class="mb-1">Estado</label>
      <select class="form-select form-select-sm mb-2" name="estadoEquipo" id="estadoEquipo<?php echo $idEquipoContratoActual ?>" <?php echo ($registro == "true" || $idPD === $idPDActual) ? "" : "disabled" ?>>
        <option value="OPERATIVO">OPERATIVO</option>
        <option value="INOPERATIVO">INOPERATIVO</option>
        <option value="STANDBY">STANDBY</option>
      </select>
      <!-- <div id="llegaTextAreaCreado<?php echo $idEquipoContratoActual ?>"></div> -->
      <label class="mb-1">Fecha de tareo</label>
      <input type="date" data-validate name="fechaTareo" value="<?php echo $fechaTareoActual ?>" id="fechaTareoPD<?php echo $idEquipoContratoActual ?>" class="form-control form-control-sm" <?php echo ($registro == "true") ? "" : "readonly" ?>>
      <input type="hidden" id="fechaTareoHiddenPD" data-formfecharef="<?php echo $fechaTareoActual ?>">
    </div>
    <?php if ($estadoCompraRepuesto == 0 && $rangoCompraRepuesto == 1 && $configuracionInicial == false && $registro == "true") { ?>
      <div class="<?php echo $claseColumna ?> mt-2">
        <div class="form-check" id="checkCompraRepuestosPd">
          <input class="form-check-input" name="compraRepuestos" type="checkbox" value="1" id="flexCheckDefault">
          <label class="form-check-label fw-bold" for="flexCheckDefault">
            Compra de repuestos, finalizado
          </label>
        </div>
      </div>
    <?php } ?>
    <div class="<?php echo $claseColumna ?>">
      <textarea name="descripcionEstado" placeholder="Ingrese una descripción" class="form-control form-control-sm mt-3" rows="2" <?php echo ($registro == "true" || $idPD === $idPDActual) ? "" : "readonly" ?>><?php echo ($descripcionActual == null) ? "" : $descripcionActual  ?></textarea>
    </div>
    <div class="<?php echo $claseColumna ?> mt-2">
      <?php if ($registro == "true") echo "<label class=\"text-secondary\">Ultimo ingreso : $ultimoFechaIngreso</label>" ?>
    </div>
  </div>
  <?php if ($registro == "true") {  ?>
    <button type="button" class="btn btn-sm btn-blue-gyt float-end my-2" onclick="agregaParametroDiario('<?php echo 'formAgregaParametroDiario' . $idEquipoContratoActual ?>')">Guardar</button>
  <?php  } ?>
  <?php if ($idPD === $idPDActual) {  ?>
    <button type="button" class="btn btn-sm btn-success float-end my-2" onclick="actualizaParametroDiario('<?php echo 'formAgregaParametroDiario' . $idEquipoContratoActual ?>','<?php echo $estadoActualizacion?>')">Actualizar</button>
  <?php  } ?>
</form>
<script>
  date = new Date();
  hoy = String(date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0'));
  document.getElementById("fechaTareoPD<?php echo $idEquipoContratoActual ?>").setAttribute("max", hoy)
  medidorAnalogicoPD = document.getElementById("medidorAnalogicoPD<?php echo $idEquipoContratoActual ?>");
  if (medidorAnalogicoPD !== null) medidorAnalogicoPD.value =
    "<?php echo ($registro == "true") ? $tipoMdAnalogico : $tipoMdAnalogicoActual ?>";
  medidorKilometrajePD = document.getElementById("medidorKilometrajePD<?php echo $idEquipoContratoActual ?>");
  if (medidorKilometrajePD !== null) medidorKilometrajePD.value =
    "<?php echo ($registro == "true") ? $tipoMdKilometraje : $tipoMdKilometrajeActual ?>";
  document.getElementById("medidorDigitalPD<?php echo $idEquipoContratoActual ?>").value =
    "<?php echo ($registro == "true") ? $tipoMdigital : $tipoMdigitalActual  ?>"

  ultimoRegistro = '<?php echo $esUltimoRegistro ?>';
  registro = '<?php echo $registro ?>';
  configuracionInicial = '<?php echo $configuracionInicial ?>';

  trabajadorActual = '<?php echo $trabajadorActual ?>';
  lista = document.querySelectorAll("[data-med_actual_principal=false]");
  if (ultimoRegistro) {
    if (trabajadorActual !== "") {
      lista.forEach(e => e.removeAttribute("readonly") )
    }
  }
  if (registro == 'true') {
    if (!configuracionInicial) {
      lista.forEach(e => e.removeAttribute("readonly"))
    }
  } 

  selectTurno = document.getElementById("turnoPD<?php echo $idEquipoContratoActual ?>")
  if (selectTurno !== null) selectTurno.value = "<?php echo $turnoActual !== false ? $turnoActual : ""  ?>";
  document.getElementById("estadoEquipo<?php echo $idEquipoContratoActual ?>").value = "<?php echo $estadoEquipoActual ?>"
  /* popover */
  if (document.querySelector('.popover-pd<?php echo $idEquipoContratoActual ?>') !== null) {
    let popover = new bootstrap.Popover(document.querySelector('.popover-pd<?php echo $idEquipoContratoActual ?>'), {
      trigger: 'focus'
    })
  }
</script>