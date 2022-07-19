<?php
require_once("obtenerMedicionPrincipal.php");
function alertaMantenimiento($conexion, $idFamilia, $idEquipo)
{
    $ultimaMedicionPrincipal = 0;
    $idUltimaOt="";
    $alertaOTPendiente=null;
    $contadorOt = 0;
    $idUltimaOtPD = null;
    $arrayUltimaOt = ["id" => null, "estado" => null];
    $arrayPenultimaOt = ["id" => null, "estado" => null];
    $arregloCompraRepuestos = ["icono" => null, "descripcion" => null, "estado" => null];
    $arregloCreacionOt = ["icono" => null, "descripcion" => null, "estado" => null];

    /* recuperacion de iultimo parametro diario ingresado */
    $ultimoParametro = mysqli_query($conexion, "SELECT PADI_medicion_digital,PADI_medicion_analogico,PADI_medicion_kilometraje,PADI_medicion_cambio,PADI_estado_ot,EQCO_tipo_medicion,ORTR_id01,EQU_id01,PADI_id,
    PADI_estado_compra_r FROM parametros_diarios pd RIGHT JOIN equipos_contrato ON pd.EQCO_id01=EQCO_id WHERE EQU_id01='$idEquipo' ORDER BY PADI_id DESC LIMIT 1");
    foreach ($ultimoParametro as $k) {
        $ultimaMedicionDigital = $k["PADI_medicion_digital"];
        $ultimaMedicionAnalogico = $k["PADI_medicion_analogico"];
        $ultimaMedicionKilometraje = $k["PADI_medicion_kilometraje"];
        $medicionSiguiente = $k["PADI_medicion_cambio"];
        $estadoOt = $k["PADI_estado_ot"];
        $idUltimaOtPD = $k["ORTR_id01"];
        $estadoCompraRepuesto = $k["PADI_estado_compra_r"];
        $tipoMedicion = $k["EQCO_tipo_medicion"];
        $idEquipoPD = $k["EQU_id01"];
        $idPADI = $k["PADI_id"];
        //echo ($medicionSiguiente - $ultimaMedicion);
    }

    $dosUltimasOts = mysqli_query($conexion, "SELECT ORTR_id,ORTR_estado FROM ordenes_trabajo ot INNER JOIN equipos_contrato ec ON ot.EQCO_id01=ec.EQCO_id WHERE ORTR_tipo_evento='PREVENTIVO' AND EQU_id01='$idEquipoPD' ORDER BY ORTR_id DESC LIMIT 2");
    foreach ($dosUltimasOts as $k) {
        if ($contadorOt == 0) {
            $arrayUltimaOt = ["id" => $k["ORTR_id"], "estado" => $k["ORTR_estado"]];
        }else{
            $arrayPenultimaOt = ["id" => $k["ORTR_id"], "estado" => $k["ORTR_estado"]];
        }
        /* var_dump($arrayUltimaOt,$arrayPenultimaOt); */
        $contadorOt ++;
    }
    
    if ($idPADI == null) {
        return [$arregloCompraRepuestos, $arregloCreacionOt,null];
        die();
    }
    //obtenemos las alertas de la familia del equipo
    $alertaCompraRepuestos = mysqli_query($conexion, "SELECT FAM_alerta01_kilometraje,FAM_alerta02_kilometraje,FAM_alerta01_horometro,FAM_alerta02_horometro FROM familias WHERE FAM_id='$idFamilia'");
    foreach ($alertaCompraRepuestos as $x) {
        $alertaTiempo01Kilometraje = $x["FAM_alerta01_kilometraje"];
        $alertaTiempo02Kilometraje = $x["FAM_alerta02_kilometraje"];
        $alertaTiempo01Horometro = $x["FAM_alerta01_horometro"];
        $alertaTiempo02Horometro = $x["FAM_alerta02_horometro"];
    }
    // defeinimos por defecto las alertas se haran por horometro
    $alertaMantenimiento01 = $alertaTiempo01Horometro;
    $alertaMantenimiento02 = $alertaTiempo02Horometro;
    //asignamos la medicion con la que trabajara
    $ultimaMedicionPrincipal = getMedicionPrincipal ($tipoMedicion,$ultimaMedicionDigital,$ultimaMedicionAnalogico,$ultimaMedicionKilometraje);
    if ($tipoMedicion=="Kilometraje") {
        $alertaMantenimiento01 = $alertaTiempo01Kilometraje;
        $alertaMantenimiento02 = $alertaTiempo02Kilometraje;
    }

    if (($medicionSiguiente - $ultimaMedicionPrincipal) <= $alertaMantenimiento01 && $ultimaMedicionPrincipal != 0) {
        $respuesta = semaforoAlertas($medicionSiguiente, $ultimaMedicionPrincipal, $alertaMantenimiento01,$estadoCompraRepuesto);
        $arregloCompraRepuestos = [
            "icono" => "<i class=\"fas fa-shopping-cart fa-lg $respuesta[0]\"></i>",
            "descripcion" => $respuesta[1],
            "estado" => $respuesta[2]
        ];
    }
    if (($medicionSiguiente - $ultimaMedicionPrincipal) <= $alertaMantenimiento02 && $ultimaMedicionPrincipal != 0) {
        $respuesta2 = semaforoAlertas($medicionSiguiente, $ultimaMedicionPrincipal, $alertaMantenimiento02,$estadoOt);
        $idUltimaOt = $arrayUltimaOt["id"];
        $arregloCreacionOt = [
            "icono" => "<a href=\"#\"  data-bs-toggle=\"modal\" data-bs-target=\"#modalActOt\" onclick=\"llenarDatosOTAct('$idUltimaOt')\"><i class=\"far fa-clipboard fa-lg $respuesta2[0]\"></i></a>",
            "descripcion" => $respuesta2[1],
            "estado" => $respuesta2[2]
        ];
       
    }
    if ($idUltimaOtPD == null) { //el ultimo PD no creo una OT
        if ($arrayUltimaOt["estado"] != null) {
            $idUltimaOt = $arrayUltimaOt["id"];
            $alertaOTPendiente = "<a href=\"#\"  data-bs-toggle=\"modal\" data-bs-target=\"#modalActOt\" onclick=\"llenarDatosOTAct('$idUltimaOt')\"><span class=\"badge rounded-pill bg-danger\">OTP</span></a>";
        }
    }else{

        if ($arrayPenultimaOt["estado"] != null) {
            $idPenultimaOt = $arrayPenultimaOt["id"];
            $alertaOTPendiente = "<a href=\"#\"  data-bs-toggle=\"modal\" data-bs-target=\"#modalActOt\" onclick=\"llenarDatosOTAct('$idPenultimaOt')\"><span class=\"badge rounded-pill bg-danger\">OTP</span></a>";
        }
    }
    return [$arregloCompraRepuestos, $arregloCreacionOt,$alertaOTPendiente];
}
function semaforoAlertas($medicionSiguiente, $ultimaMedicion, $alertaMantenimiento01,$estadoAlerta)
{
    $classAlerta = "";
    $restaMedicion = $medicionSiguiente - $ultimaMedicion;
    $malo = $alertaMantenimiento01 * (1 / 3);
    $regular = $alertaMantenimiento01 * (2 / 3);
    if ($estadoAlerta == 0) {
        if ($restaMedicion >= $regular) {
            $classAlerta = "text-success";
        }
        if ($restaMedicion < $regular) {
            $classAlerta = "text-warning";
        }
        if ($restaMedicion < $malo) {
            $classAlerta = "text-danger";
        }
        return [$classAlerta, "Tienes $restaMedicion hrs/klms habiles para realizar la operación","PENDIENTE"];
    }else{
        $classAlerta = "text-success";
        return [$classAlerta, "La operacion fue completada","FINALIZADO"];
    }
}
