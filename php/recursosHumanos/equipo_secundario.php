<?php
require_once("../conexion.php");
$idEqPrincipal = $_POST["idEqPrincipal"];
function returnEquipoSecundario($conexion, $idEqPrincipal)
{
    $datosEquipo = [];

    $consulta = "SELECT * FROM equipos e INNER JOIN familias f ON f.FAM_id=e.FAM_id01
                                     LEFT JOIN modelos mo ON mo.MOD_id=e.MOD_id01
                                     INNER JOIN marcas ma ON ma.MAR_id=e.MAR_id01 WHERE EQU_union = '$idEqPrincipal'";
    $resConsulta = mysqli_query($conexion, $consulta);
    foreach ($resConsulta as $x) {
        $datosEquipo = [
            $x["EQU_codigo"],
            $x["FAM_descripcion"],
            $x["MOD_descripcion"],
            $x["MAR_descripcion"], 
            $x["EQU_modelo_motor"], 
            $x["EQU_placa"], 
            $x["EQU_a_fabricacion"], 
            $x["EQU_capacidad"], 
            $x["FAM_id"], 
            $x["MAR_id"], 
            $x["MOD_id"], 
            $x["EQU_marca_motor"],
            $x["EQU_medicion"], 
            $x["EQU_numero_motor"]];
    }
    echo json_encode($datosEquipo);
}
returnEquipoSecundario($conexion,$idEqPrincipal);