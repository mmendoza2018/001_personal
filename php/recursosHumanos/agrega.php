<?php
session_start();
require_once("../conexion.php");
require_once("../general/ultimoId.php");

$idEquipoPrincipal=0;
if(isset($_POST["segundoEquipo"])) {
    $idRelacion=@$_SESSION["idEstructura"];
    if ($idRelacion==NULL) $idRelacion = $_POST["equipoPrincipal"];
    unset($_SESSION["idEstructura"]);
    $idEquipoPrincipal=0;
}else{
    
$ultimoIdArray = mysqli_query($conexion,"SELECT EQU_id FROM equipos ORDER BY EQU_id DESC LIMIT 1");
    if ($ultimoIdArray->num_rows<=0){
        $ultimoId=1;
    }else{
        foreach ($ultimoIdArray as $x) { $ultimoId = $x["EQU_id"]+1; }
    }
   $idRelacion=0;
   $_SESSION["idEstructura"]=$ultimoId;
   $idEquipoPrincipal=1;
}
$usuario = $_SESSION["nombre_trabajador"];
$id = ultimoId($conexion,"equipos","EQU_id");
$codigo = @$_POST["codigo"];
$familia = @$_POST["descripcion"];//error
$placa = @$_POST["placa"];
$marca = @$_POST["marca"];
$modelo = @$_POST["modelo"];
$marcaMotor = @$_POST["marcaMotor"];
$modeloMotor = @$_POST["modeloMotor"];
$fabricacion = @$_POST["fabricacion"];
$fabricacionPluma = @$_POST["fabricacionPluma"]; 
$serieChasis = @$_POST["serieChasis"];//error
$capacidad = @$_POST["capacidad"];
$tMedicion = @$_POST["tMedicion"];
$propietario = @$_POST["propietario"];
$centroCosto = @$_POST["centroCosto"];
(isset($_POST["fechaIngreso"])) ? $fechaIngreso =$_POST["fechaIngreso"] : $fechaIngreso= "0000-00-00";
//$Marcamotor = @$_POST["Marcamotor"];
$numeroMotor = @$_POST["numeroMotor"];

$consulta = "INSERT INTO equipos ( EQU_codigo,		
                                        FAM_id01,	
                                        MAR_id01,	
                                        MOD_id01,
                                        EQU_principal,
                                        EQU_union,		
                                        EQU_modelo_motor,
                                        EQU_marca_motor,
                                        EQU_numero_motor,
                                        EQU_centro_costo,
                                        EQU_a_fabricacion,		
                                        EQU_a_fabricacion_pluma,		
                                        EQU_serie_chasis,	
                                        EQU_placa,		
                                        EQU_capacidad,		
                                        EQU_medicion,		
                                        PROP_id01,		
                                        EQU_f_ingreso)		
                                         VALUES ('$codigo','$familia','$marca'," . (($modelo == null) ? "NULL" : $modelo) . ",'$idEquipoPrincipal','$idRelacion','$modeloMotor','$marcaMotor','$numeroMotor','$centroCosto','$fabricacion','$fabricacionPluma','$serieChasis','$placa','$capacidad','$tMedicion','$propietario','$fechaIngreso');";
/* $consulta.="CALL agregar_historial('equipos','$id','agrego','$usuario');"; */

$consulta.= "UPDATE familias SET FAM_cont_equipos= FAM_cont_equipos+1 WHERE FAM_id='$familia'";
$addEquipo = mysqli_multi_query($conexion,$consulta);
echo ($addEquipo) ? "true" : "false";
$conexion->close();
?>