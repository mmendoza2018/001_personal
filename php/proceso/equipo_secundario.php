<?php
require_once("../conexion.php");

$idEqPrincipal = @$_POST["idEqPrincipal"];
$idContrato = @$_POST["idContrato"];

$conEquipoSecundario = mysqli_query($conexion, "SELECT DATEDIFF(NOW(),PADI_fecha_tareo) as diasRetraso, DATE(PADI_fecha_tareo) as fecha,PADI_medicion_digital,PADI_man_siguiente,EQCO_id01,EQU_codigo,EQCO_id,EQU_id,FAM_descripcion,EQU_placa, CONTR_numero,CONTR_id,CONTR_descripcion FROM (SELECT MAX(PADI_id) as maximoId FROM parametros_diarios GROUP BY EQCO_id01) as t1 INNER JOIN parametros_diarios pd ON t1.maximoId=pd.PADI_id RIGHT JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id INNER JOIN equipos e ON e.EQU_id=ec.EQU_id01 INNER JOIN contratos c ON c.CONTR_id=ec.CONTR_id01 INNER JOIN familias f ON f.FAM_id=e.FAM_id01 WHERE EQCO_estado=1 AND CONTR_id='$idContrato' AND EQU_union='$idEqPrincipal'");


foreach ($conEquipoSecundario as $k) {
    $diasRetraso = ($k["diasRetraso"]<=0) ? "" : $k["diasRetraso"];
    $classCircle = ($k["diasRetraso"]<=0)? "text-success" : "text-danger";
    $data = [   
        "idEquipoContrato" => $k["EQCO_id"],
        "codigo" => $k["EQU_codigo"],
        "idEquipo" => $k["EQU_id"],
        "familia" => $k["FAM_descripcion"],
        "placa" => $k["EQU_placa"],
        "numContrato" => $k["CONTR_numero"],
        "contrato" => $k["CONTR_descripcion"],
        "idContrato" => $k["CONTR_id"],
        "ultimoIngreso" => $k["fecha"],
        "diasRetraso" => $diasRetraso,
        "claseCirculo" => $classCircle,
        "parametroDiario" => $k["PADI_medicion_digital"],
        "mantSiguiente" => $k["PADI_man_siguiente"]
    ];
    echo json_encode($data); 
}