<?php
require_once("../conexion.php");
$idEquipo = $_POST["idEquipo"];
    $datosEquipo = [];
    $consulta =  "SELECT * FROM equipos e   INNER JOIN marcas ma ON ma.MAR_id=e.MAR_id01 
    INNER JOIN modelos mo ON mo.MOD_id=e.MOD_id01
    INNER JOIN propietarios p ON p.PROP_id=e.PROP_id01
    INNER JOIN familias fa ON fa.FAM_id=e.FAM_id01  WHERE EQU_id = '$idEquipo'";
    $resConsulta = mysqli_query($conexion, $consulta);
    foreach ($resConsulta as $x) {
        $datosEquipo = [
            $x["EQU_id"],
            $x["EQU_codigo"],
            $x["FAM_id"],
            $x["MAR_id"],
            $x["MOD_descripcion"],
            $x["EQU_placa"],
            $x["EQU_modelo_motor"],
            $x["EQU_numero_motor"],
            $x["EQU_a_fabricacion"],
            $x["EQU_a_fabricacion_pluma"],
            $x["EQU_serie_chasis"],
            $x["EQU_capacidad"],
            $x["PROP_id"],
            $x["EQU_f_ingreso"],
            $x["EQU_f_salida"],
            $x["FAM_descripcion"],
            $x["PROP_descripcion"],
            $x["EQU_centro_costo"],
            $x["EQU_marca_motor"],
            $x["EQU_medicion"],
            $x["MAR_descripcion"]];
    }
    echo json_encode($datosEquipo);