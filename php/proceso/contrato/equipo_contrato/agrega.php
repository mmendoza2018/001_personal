<?php 
session_start();
require_once("../../../conexion.php");
$usuario = @$_SESSION["nombre_trabajador"];
$idContrato = @$_POST["idContrato"];
$equipoCon = @$_POST["equipoCon"];
$tipoMedicion = @$_POST["tipoMedicion"];
$fechaIngreso = @$_POST["fechaIngreso"];
$idBrazoHidraulico = null;
$consulta = "";
$equiposRepetidos = [];
$idEquipos = [];
$contador = 0;

for ($i=0; $i < count($equipoCon) ; $i++) { 
    $traerId= mysqli_query($conexion,"SELECT EQU_id,EQU_codigo FROM equipos WHERE EQU_codigo='".$equipoCon[$i]."'");
    foreach ($traerId as $x) { 
        $brazoHidraulico = mysqli_query($conexion,"SELECT EQU_id FROM equipos WHERE EQU_union = '".$x["EQU_id"]."'");
        if (mysqli_num_rows($brazoHidraulico)>0) {
            foreach ($brazoHidraulico as $y) { $idBrazoHidraulico = $y["EQU_id"]; }
            array_push($idEquipos, [
                "id" => $idBrazoHidraulico,
                "equipoSecundario"=> true,
                "tipoMedicion" =>"Horometro digital",
                "fechaIngreso" => $fechaIngreso[$i]
            ]);
        }
        array_push($idEquipos,  [
            "id" => $x["EQU_id"],
            "equipoSecundario"=> false,
            "tipoMedicion" =>$tipoMedicion[$contador],
            "fechaIngreso" => $fechaIngreso[$i]
        ]);
        $contador++;
    }
}

for ($i=0; $i < count($idEquipos) ; $i++) { 
        $consulta .= "INSERT INTO equipos_contrato (CONTR_id01,EQU_id01,EQCO_tipo_medicion,EQCO_fecha_ingreso_contrato,EQCO_usuario) VALUES ('$idContrato','".$idEquipos[$i]["id"]."','".$idEquipos[$i]["tipoMedicion"]."','".$idEquipos[$i]["fechaIngreso"]."','$usuario');";

}
$consulta = substr($consulta, 0, -1);
$resConsulta = mysqli_multi_query($conexion,$consulta);
echo ($resConsulta) ? "true" : "false";
?>