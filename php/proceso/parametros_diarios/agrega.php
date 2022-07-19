<?php
require_once("../../conexion.php");
require_once("../../mantenimiento/equipo/obtener_equipo_mantenimiento.php");
require_once("validaOts.php");
session_start();
$usuario = $_SESSION["nombre_trabajador"];
if($_POST["actualizarEstado"] == "false") {
  echo json_encode([0, "No es posible actualizar un parametro diario de un contrato anterior"]);
  die();
} 
$MedicionAnterior =@ $_POST["MedicionAnterior"];
$medidorDigital = @$_POST["medidorDigital"];
$existeOtActualizar = $_POST["existeOtActualizar"];
$turno = @$_POST["turno"];
$fechaTareo = @$_POST["fechaTareo"];
$medidorAnalogico = (isset($_POST["medidorAnalogico"])) ? $_POST["medidorAnalogico"] : null;
$medidorKilometraje = (isset($_POST["medidorKilometraje"])) ? $_POST["medidorKilometraje"] : null;
$estadoCompraRepuestos = (isset($_POST["compraRepuestos"])) ? 1 : 0;
$idEquipoContrato = $_POST["idEquipoContrato"];
$medicionActualDigital  = $_POST["medicionActualDigital"];
$medicionActualAnalogico = (isset($_POST["medicionActualAnalogico"])) ? $_POST["medicionActualAnalogico"] : 0;
$medicionActualKilometraje = (isset($_POST["medicionActualKilometraje"])) ? $_POST["medicionActualKilometraje"] : 0;
$idOperador = (($_POST["idOperador"])==null) ? "NULL" : $_POST["idOperador"];
$ingresoMedicionActual = @$_POST["ingresoMedicionActual"];
$primerCambioSistema  = @$_POST["primerCambioSistema"];
$idPrimerCambioSistema  = @$_POST["idPrimerCambioSistema"];
$medicionUltimoMantenimiento  = @$_POST["medicionUltimoMantenimiento"];
$idEquipo = $_POST["idEquipo"];
$medicionHoy = $_POST["medicionHoy"];
$estadoEquipo = @$_POST["estadoEquipo"];
$descripcionEstado = (isset($_POST["descripcionEstado"])) ? $_POST["descripcionEstado"] : null;
$cambioAnterior = null;
$medicionCambioSiguiente = null;
$cambioSiguiente = null;
$idCambioSiguiente = null;
$idFamilia = null;
$estadoOt = 0;
$idOt = null;
$idOtGeneraOT = null;
$idUltimoOt = 1;
$consulta = "";
$auxiliarCambioAnterior = null;
$compraRepuestos=0;

//medicion con la que esta haciendo el trabajo
$medicionPrincipal = 0;
//validación de mantenimientos por equipo
$resVerificaMtto = mysqli_query($conexion, "SELECT EQMA_id FROM equipo_mantenimiento WHERE EQU_id01='$idEquipo'");
if (mysqli_num_rows($resVerificaMtto) <= 0) {
  echo json_encode([0, "El equipo no tiene mantenimientos asignados"]);
  die();
}
$ultimoParametro = "SELECT ORTR_estado,PADI_medicion_digital,PADI_medicion_cambio,PADI_man_siguiente,ORTR_id01,FAM_id01,PADI_estado_compra_r,EQCO_tipo_medicion,PADI_fecha_tareo,CONTR_id01,PADI_id FROM parametros_diarios pd 
RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id
INNER JOIN equipos e ON ec.EQU_id01=e.EQU_id
LEFT JOIN ordenes_trabajo ot ON pd.ORTR_id01=ot.ORTR_id
 WHERE EQU_id='$idEquipo' ORDER BY PADI_id DESC LIMIT 1";
$resUltimoParametro = mysqli_query($conexion, $ultimoParametro);
foreach ($resUltimoParametro as $x) {
  $medicionAnterior = $x["PADI_medicion_digital"];
  $medicionCambioSiguiente = $x["PADI_medicion_cambio"];
  $cambioAnterior = intval($x["PADI_man_siguiente"]);
  $estadoCompraRepuestos = ($estadoCompraRepuestos == "1") ? $estadoCompraRepuestos : $x["PADI_estado_compra_r"];
  $idOt = $x["ORTR_id01"];
  $idPD = $x["PADI_id"];
  $idFamilia = $x["FAM_id01"];
  $estadoOt = ($x["ORTR_estado"] == "FINALIZADO") ? 1 : 0;
  $tipoMedicionAnterior = ($cambioAnterior==null) ? null : $x["EQCO_tipo_medicion"];
  $fechaTareoBD = $x["PADI_fecha_tareo"];
  $idContratoAnterior = ($idPD==null) ? null : $x["CONTR_id01"];
}

$conTipoFrecuencia = mysqli_query($conexion, "SELECT EQCO_tipo_medicion,CONTR_id01 FROM equipos_contrato WHERE EQCO_id='$idEquipoContrato'");
foreach ($conTipoFrecuencia as $x) {
  $tipoMedicionActual = $x["EQCO_tipo_medicion"];
  $idContratoActual = $x["CONTR_id01"]; 
}
//valida el ingreso de parametros con fechas correlattivas
//si el mismo contrato debera respetar el correlativo
if($fechaTareoBD != null  && ($idContratoActual == $idContratoAnterior)) {
  $fechaSiguienteTareo = date("Y-m-d",strtotime($fechaTareoBD."+ 1 days"));
  if($fechaTareo != $fechaSiguienteTareo && $fechaTareoBD != null) {
    echo json_encode([0, "Debe ingresar el tareo de la fecha $fechaSiguienteTareo"]);
    die();
  } 
}else{
  $ListaFechas = mysqli_query($conexion,"SELECT PADI_fecha_tareo FROM parametros_diarios pd 
  RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id WHERE EQU_id01= '$idEquipo'");
  foreach ($ListaFechas as $x) {
    if ($x["PADI_fecha_tareo"] == $fechaTareo) {
      echo json_encode([0, "La fecha ingresada ya ha sido registrada anteriormente"]);
      die();
    }
  }
}

//asignamos la medicion con la que trabajara
if ($tipoMedicionActual == "Horometro digital") {
  $medicionActualPrincipal = $medicionActualDigital;
} else if ($tipoMedicionActual == "Horometro analogico") {
  $medicionActualPrincipal = $medicionActualAnalogico;
} else {
  $medicionActualPrincipal = $medicionActualKilometraje;
}
//obtenemos con que frecuencia se trabajara
$tipoFrecuenciaActual = ($tipoMedicionActual == "Horometro digital" || $tipoMedicionActual == "Horometro analogico")
  ? "Horometro"
  : "Kilometraje";
//si no existe un cambios anterior (PD) o cuando se cambio de medidor en los demas casos toma el ultimo ingreso (PD)
if (($cambioAnterior == null || ($idContratoActual != $idContratoAnterior)) ) {
  // || $tipoMedicionActual != $tipoMedicionAnterior
  $conCambioAnterior = "SELECT TIMA_tiempo FROM equipo_mantenimiento em INNER JOIN tiempo_mantenimiento tm ON em.TIMA_id01 = tm.TIMA_id WHERE EQU_id01='$idEquipo' AND TIMA_tiempo < '$primerCambioSistema' AND TIMA_tipo_medicion = '$tipoFrecuenciaActual' AND EQMA_estado=1 ORDER BY TIMA_tiempo DESC LIMIT 1";
  $resCambioAnterior = mysqli_query($conexion, $conCambioAnterior);
  foreach ($resCambioAnterior as $k) {
    $auxiliarCambioAnterior = $k["TIMA_tiempo"];
  }
  // si no existe un cambio anterior en la consulta, asignamos el cambio anterior el ingresado recientemente
  $cambioAnterior = ($auxiliarCambioAnterior == null) ? $primerCambioSistema : $auxiliarCambioAnterior;
  //validar el correcto ingreso de la primera configuracion
  if ($medicionUltimoMantenimiento + $primerCambioSistema < $medicionActualPrincipal) {
    echo json_encode([0, "El $tipoFrecuenciaActual del ultimo mantenimiento o el $tipoFrecuenciaActual actual no son coherentes"]);
    die();
  }
}
//obtenemos el id del e. mantenimiento del siguiente cambio que se hara 
$conIdEMantenimientoActual = mysqli_query($conexion,"SELECT EQMA_id FROM equipo_mantenimiento em INNER JOIN tiempo_mantenimiento tm ON em.TIMA_id01=tm.TIMA_id WHERE EQU_id01='$idEquipo' AND  TIMA_tiempo = '$cambioAnterior'");
foreach ($conIdEMantenimientoActual as $y) {
  $idEMantenimientoActual = $y["EQMA_id"];
}

$listaCambios = mysqli_query($conexion, "SELECT TIMA_tiempo,TIMA_id,EQMA_id FROM equipos_contrato ec INNER JOIN equipo_mantenimiento em ON ec.EQU_id01=em.EQU_id01 INNER JOIN tiempo_mantenimiento tm ON tm.TIMA_id=em.TIMA_id01 WHERE ec.EQCO_id='$idEquipoContrato' AND TIMA_tiempo > $cambioAnterior AND TIMA_tipo_medicion = '$tipoFrecuenciaActual' AND EQMA_estado=1 ORDER BY TIMA_tiempo ASC LIMIT 1");
if (mysqli_num_rows($listaCambios) > 0) {

  foreach ($listaCambios as $x) {
    $idEquipoMantenimiento = $x["EQMA_id"];
    $cambioSiguiente = intval($x["TIMA_tiempo"]);
    $cambioSiguiente = ($primerCambioSistema != "") ? $primerCambioSistema : $cambioSiguiente;
    $idCambioSiguiente = $x["TIMA_id"];
  }
} else { // si no existe un mantenimiento siguiente capturamos el primero (2000-250)
  $conPrimerCambioEquipo = "SELECT TIMA_tiempo,TIMA_id,EQMA_id FROM equipo_mantenimiento em INNER JOIN tiempo_mantenimiento tm ON em.TIMA_id01 = tm.TIMA_id INNER JOIN equipos_contrato ec ON ec.EQU_id01=em.EQU_id01 WHERE ec.EQU_id01='$idEquipo' AND TIMA_tipo_medicion = '$tipoFrecuenciaActual' AND EQMA_estado=1 ORDER BY TIMA_tiempo ASC LIMIT 1";

  foreach (mysqli_query($conexion, $conPrimerCambioEquipo) as $y) {
    $cambioSiguiente = $y["TIMA_tiempo"]; // primer Cambio Sistema DB
    $cambioSiguiente = ($primerCambioSistema != "") ? $primerCambioSistema : $cambioSiguiente;
    $idCambioSiguiente = $y["TIMA_id"]; // id Primer Cambio Sistema DB
    $idEquipoMantenimiento = $y["EQMA_id"];
  }
}

//echo $medicionActualPrincipal,$medicionCambioSiguiente, $tipoMedicionActual, $tipoMedicionAnterior;

$cambioSiguiente = ($cambioSiguiente == null) ? $primerCambioSistema : $cambioSiguiente;
$medicionCambioSiguiente = ($medicionCambioSiguiente == null) ? 0 : $medicionCambioSiguiente;
#die();
  //valida si la medicion del ultimo ingreso es igual a la actuaL
  if ($idContratoActual != $idContratoAnterior) {
    $estadoOt = 0;
    $estadoCompraRepuestos = 0;
    $idOt = null;
    if (ExisteOtsPendientes($conexion,$idEquipo)) {
      echo json_encode([0, "Hay dos Ordenes de trabajo pendientes"]);
      die();
    }
    $Horastranscurridas = $medicionActualPrincipal  - $medicionUltimoMantenimiento;
  if ($cambioSiguiente == $cambioAnterior) { // asumimos que el siguiente mantenimiento es el valor de ambos (250)
    $medicionCambioSiguiente = abs($medicionActualPrincipal  + $cambioSiguiente - $Horastranscurridas);
  } else {
    $medicionCambioSiguiente = abs(($medicionActualPrincipal  + ($cambioSiguiente - $cambioAnterior)) - $Horastranscurridas);
  }
} else {
  #super el limite para cambiar a una frecuencia nueva
  if ($medicionActualPrincipal > $medicionCambioSiguiente) {
    $estadoOt = 0;
    $estadoCompraRepuestos = 0;
    $idOt = null;
    
    if (ExisteOtsPendientes($conexion,$idEquipo)) {
      echo json_encode([0, "Hay dos Ordenes de trabajo pendientes"]);
      die();
    }
    /* si es la primera configuracion al ingresar (a un contrato o en general)  */
    if ($ingresoMedicionActual == "true") {
      #horas transcurridas desde el ultimo matenimiento
      $Horastranscurridas = $medicionActualPrincipal  - $medicionUltimoMantenimiento;
      
      $medicionCambioSiguiente = abs(($medicionActualPrincipal  + ($cambioSiguiente - $cambioAnterior)) - $Horastranscurridas);
    } else {
      //cuando el equipo vuelva a comenzar desde el principio sus mantenimientos o el equipo solo tiene un mantenimiento
      if ($cambioAnterior > $cambioSiguiente) {
        $medicionCambioSiguiente = abs($medicionCambioSiguiente + $cambioSiguiente);
      }else {
        $medicionCambioSiguiente = abs($medicionCambioSiguiente + ($cambioSiguiente - $cambioAnterior));
      }
      //echo "CS -> ".$cambioSiguiente."CA -> ". $cambioAnterior."MAP -> ".$medicionActualPrincipal."MCS -> ".$medicionCambioSiguiente;
    }
    /* echo $medicionCambioSiguiente."---". abs($medicionCambioSiguiente + ($cambioSiguiente - $cambioAnterior)); */
  } else {
    $cambioSiguiente = $cambioAnterior;
  }
}

if ($cambioSiguiente == NULL) $cambioSiguiente = $primerCambioSistema;
// comprueba que el campo estado ot sea 0 para ingresar la ot
//comprueba de que no haya una diferencia de 50 hrs/klmts de diferencia para crear la OT
$tiempoRestanteCreacionOt = $medicionCambioSiguiente - $medicionActualPrincipal;

/* consulta de la primera alerta establecida */
$conAlertasEquipo = "SELECT FAM_alerta02_kilometraje,FAM_alerta02_horometro,FAM_alerta01_kilometraje,FAM_alerta01_horometro FROM familias WHERE FAM_id='$idFamilia'";
$alertasEquipo = mysqli_query($conexion, $conAlertasEquipo);
if (mysqli_num_rows($alertasEquipo) > 0) {
  foreach ($alertasEquipo as $y) {
    $alertaTiempo01Kilometraje = $y["FAM_alerta01_kilometraje"];
    $alertaTiempo01Horometro = $y["FAM_alerta01_horometro"];
    $alertaTiempo02Kilometraje = $y["FAM_alerta02_kilometraje"];
    $alertaTiempo02Horometro = $y["FAM_alerta02_horometro"];
  }
  $limiteTiempoCompraR = ($tipoMedicionActual == "Kilometraje") ?  $alertaTiempo01Kilometraje :  $alertaTiempo01Horometro;
  $limiteTiempoCreacionOt = ($tipoMedicionActual == "Kilometraje") ?  $alertaTiempo02Kilometraje :  $alertaTiempo02Horometro;
  if ($limiteTiempoCreacionOt == null || $limiteTiempoCompraR == null) {
    echo json_encode([0, "La familia del equipo no tiene configurado las alertas del $tipoMedicionActual"]);
    die();
  };
  if ($tiempoRestanteCreacionOt <= $limiteTiempoCompraR  && $tiempoRestanteCreacionOt >= 0) $compraRepuestos=1;
  //echo "TRCOT -> " . $tiempoRestanteCreacionOt, "lCOT -> " . $limiteTiempoCreacionOt;
  if ($existeOtActualizar == "null") {
  if (($tiempoRestanteCreacionOt <= $limiteTiempoCreacionOt && $tiempoRestanteCreacionOt >= 0) && $idOt == null) {
      //creacion de la ot
      $ultimoOt = mysqli_query($conexion, "SELECT ORTR_id FROM ordenes_trabajo ORDER BY ORTR_id DESC LIMIT 1");
      foreach ($ultimoOt as $k) {
        $idUltimoOt = $k["ORTR_id"] + 1;
      }
      $idOt = $idUltimoOt;
      $idOtGeneraOT = $idOt;
  
      /* Registro condicional, si el mantenimiento actual usa una configuracion de otro mantenimiento */
      $idEMantenimientoActual = obtenerIdEquipoMantenimeinto($conexion, $idEMantenimientoActual);
  
      $tipoSistemas = "SELECT TISI_descripcion,CAEQ_id,TISI_id FROM cambio_sis_equipos cs INNER JOIN tipo_sistemas ts ON cs.TISI_id01=ts.TISI_id WHERE EQMA_id01='$idEMantenimientoActual' AND CAEQ_estado=1";
  
      $consulta .= "INSERT INTO ordenes_trabajo (EQCO_id01,	
      ORTR_tipo_evento,		
      ORTR_descripcion,		
      ORTR_medicion,
      ORTR_estado) VALUES ($idEquipoContrato,'PREVENTIVO','OT generada automaticamente','$medicionActualPrincipal ','PENDIENTE');";
      foreach (mysqli_query($conexion, $tipoSistemas) as $x) {
        $consulta .= "INSERT INTO trabajos_realizar (ORTR_id01,TISI_id01,DEOR_estado) 
                    VALUES ('$idUltimoOt','" . $x["TISI_id"] . "',1);";
      }
    }
  }else {
    $idOt = $existeOtActualizar;
    $estadoOt = 0;
  }
}
//echo $idOperador;
$consulta .= "INSERT INTO parametros_diarios 
  (EQCO_id01,PADI_tipo_m_digital,PADI_tipo_m_analogico,PADI_tipo_m_kilometraje,PADI_ultima_medicion,PADI_medicion_cambio,PADI_medicion_digital,PADI_medicion_analogico,PADI_medicion_kilometraje,PADI_horas_trabajo,TRAB_id01,PADI_man_siguiente,ORTR_id01,PADI_estado_ot,PADI_compra_r,PADI_estado_compra_r,PADI_estado_equipo,PADI_turno,PADI_descripcion_estado,PADI_fecha_tareo,PADI_usuario)
  VALUES ('$idEquipoContrato','$medidorDigital','" . (($medidorAnalogico == null) ? "NULL" : $medidorAnalogico) . "', '" . (($medidorKilometraje == null) ? "NULL" : $medidorKilometraje) . "'," . (($medicionUltimoMantenimiento == null) ? "NULL" : $medicionUltimoMantenimiento) . ",'$medicionCambioSiguiente','$medicionActualDigital ','$medicionActualAnalogico','$medicionActualKilometraje','$medicionHoy'," . (($idOperador == null) ? "NULL" : $idOperador) . ",'$cambioSiguiente'," . (($idOt == null) ? "NULL" : $idOt) . ",'$estadoOt','$compraRepuestos','$estadoCompraRepuestos','$estadoEquipo','$turno','" . (($descripcionEstado == null) ? "" : $descripcionEstado) . "','$fechaTareo','$usuario')";

//echo "s. Cambio: ".$medicionCambioSiguiente." -- ".$medicionActual." = ".$medicionCambioSiguiente-$medicionActual;
/*  echo $consulta;
die(); */
echo (mysqli_multi_query($conexion, $consulta)) ? json_encode(["true", $idOtGeneraOT,]) : json_encode(["false", ""]);
