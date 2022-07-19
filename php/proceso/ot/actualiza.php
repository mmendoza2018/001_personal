<?php
require_once("../../conexion.php");
session_start();
$usuario = $_SESSION["nombre_trabajador"];

$idOtAct = @$_POST["idOtAct"];
$tecnico = @$_POST["tecnico"];
$supervisor = @($_POST["supervisor"]);
$operador = @$_POST["operador"];
$JefeEquipos = @$_POST["JefeEquipos"];
$fInicio = @$_POST["fInicio"];
$fCierre = @$_POST["fCierre"];
$centroCosto = @$_POST["centroCosto"];
$estado = @$_POST["estado"];
$placa2 = @$_POST["placa2"];
$evento = @$_POST["evento"];

/* $descripcion = @$_POST["descripcion"]; */
$consulta = "";
$kilometraje = @$_POST["kilometraje"];
$hChasisAna = @$_POST["hChasisAna"];
$hChasisDigi = @$_POST["hChasisDigi"];
$horometroChasis = $hChasisAna."||".$hChasisDigi;
$HgruaAna = @$_POST["HgruaAna"];
$HgruaDigi = @$_POST["HgruaDigi"];
$horometroGrua = $HgruaAna."||".$HgruaDigi;
$hBHidraulico = @$_POST["hBHidraulico"];

$upperOt = @$_POST["upperOt"];
$carrierOt = @$_POST["carrierOt"];
$gruaOt = @$_POST["gruaOt"];
$stringTipoGrua = "";
if ($upperOt != null) $stringTipoGrua .= $upperOt."||";
if ($carrierOt != null) $stringTipoGrua .= $carrierOt."||";
if ($gruaOt != null) $stringTipoGrua .= $gruaOt."||";
$stringTipoGrua = substr($stringTipoGrua, 0, -2);  
//datos tareas realzadas
$descripcionTRealizado = @$_POST["descripcionTRealizado"];
$duracionTRealizado = @$_POST["duracionTRealizado"];
$idTrabajadoresV1 = @$_POST["idTrabajadores"];
$idTrabajadores = explode(",",$idTrabajadoresV1);
//datos insumos
$descripcionAutoparte = @$_POST["descripcionAutoparte"];
$serieAutoparte = @$_POST["serieAutoparte"];
$marcaAutoparte = @$_POST["marcaAutoparte"];
$cantidadAutoparte = @$_POST["cantidadAutoparte"];
$uMedidaAutoparte = @$_POST["uMedidaAutoparte"];
$observacionAutoparte = @$_POST["observacionAutoparte"];
$monedaAutoparte = @$_POST["monedaAutoparte"];
$precioAutoparte = @$_POST["precioAutoparte"];
/*$totalAutoparte = @$_POST["totalAutoparte"]; */
if ($descripcionTRealizado[0] !== "" || $duracionTRealizado[0] !== "") {
    for ($i = 0; $i < count($descripcionTRealizado); $i++) {
        $consulta .= "INSERT INTO trabajos_realizados (ORTR_id01, TRRE_descripcion, TRRE_duracion,TRAB_id01) 
                     VALUES ('$idOtAct','$descripcionTRealizado[$i]','$duracionTRealizado[$i]','$idTrabajadores[$i]');";
    }
}
if ($descripcionAutoparte[0] !== "" && $serieAutoparte[0] !== "" && $marcaAutoparte[0] !== "") {
    for ($i = 0; $i < count($descripcionAutoparte); $i++) {
        $consulta .= "INSERT INTO insumos_ordenes (ORTR_id01,INOR_descripcion,INOR_codigo,INOR_marca,INOR_cantidad,INOR_umedida,INOR_moneda,INOR_precio,INOR_observacion) 
                     VALUES ('$idOtAct','$descripcionAutoparte[$i]','$serieAutoparte[$i]','$marcaAutoparte[$i]','$cantidadAutoparte[$i]','$uMedidaAutoparte[$i]','$monedaAutoparte[$i]','$precioAutoparte[$i]','$observacionAutoparte[$i]');";
    }
}
$consulta .= "UPDATE ordenes_trabajo SET 
                                        ORTR_tipo_evento = '$evento',	
                                        ORTR_tecnico_responsable  = " . (($tecnico == "") ? "NULL" : $tecnico) . ",
                                        ORTR_supervisor = " . (($supervisor == "") ? "NULL" : $supervisor) . ",		
                                        ORTR_operador = " . (($operador == "") ? "NULL" : $operador) . ",
                                        ORTR_jefe_equipos = " . (($JefeEquipos == "") ? "NULL" : $JefeEquipos) . ",
                                        ORTR_kilometraje = '$kilometraje',		
                                        ORTR_h_chasis = '$horometroChasis',		
                                        ORTR_h_grua = '$horometroGrua',		
                                        ORTR_h_brazo = '$hBHidraulico',
                                        ORTR_tipo_grua = '$stringTipoGrua',
                                        ORTR_usuario = '$usuario',
                                        ORTR_f_inicio = '$fInicio',		
                                        ORTR_f_cierre = '$fCierre',		
                                        ORTR_centro_costo = '$centroCosto',		
                                        ORTR_placa2 = '$placa2',		
                                        ORTR_estado = '$estado'  WHERE ORTR_id='$idOtAct';";
$obtenerEstadoOt = mysqli_query($conexion, "SELECT ORTR_estado FROM ordenes_trabajo WHERE ORTR_id='$idOtAct'");
foreach ($obtenerEstadoOt as $k) {
    $estadoOtDB = $k["ORTR_estado"];
}
/* revisar cuando la ot no esta asociada a un parametro diario */
if ($estadoOtDB != $estado && $estado == "FINALIZADO") {
    $ultimoParametro = "SELECT PADI_id FROM parametros_diarios pd 
    INNER JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id
    LEFT JOIN ordenes_trabajo ot ON pd.ORTR_id01=ot.ORTR_id
    WHERE ORTR_id='$idOtAct' ORDER BY PADI_id DESC LIMIT 1";
    $resUltimoParametro = mysqli_query($conexion, $ultimoParametro);
    if (mysqli_num_rows($resUltimoParametro) > 0) {
        foreach ($resUltimoParametro as $x) { $idUltimoParametroDiario = $x["PADI_id"]; }
        $consulta .= "UPDATE parametros_diarios SET PADI_estado_ot ='1' WHERE PADI_id='$idUltimoParametroDiario'";
    }
}
$resUpdate = mysqli_multi_query($conexion, $consulta);

echo ($resUpdate) ? "true" : "false";

$conexion->close();
