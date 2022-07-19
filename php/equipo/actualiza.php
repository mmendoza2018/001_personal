<?php
session_start();
require_once("../conexion.php");

$usuario = $_SESSION["nombre_trabajador"];
$idAct = @$_POST["idAct"];
$marcaAct = @$_POST["marcaAct"];
$modeloAct = @$_POST["modeloAct"];
$placaAct = @$_POST["placaAct"];
$modeloMotorEqAct = @$_POST["modeloMotorEqAct"];
$numeroMotorEqAct = @$_POST["numeroMotorEqAct"];
$marcaMotorEqAct = @$_POST["marcaMotorEqAct"];
$fabricacionAct = @$_POST["fabricacionAct"];
$fabricacionPAct = @$_POST["fabricacionPAct"];
$chasisAct = @$_POST["chasisAct"];
$capacidadAct = @$_POST["capacidadAct"];
$tMedicionAct = @$_POST["tMedicionAct"];
$propietarioAct = @$_POST["propietarioAct"];
$ingresoAct = @$_POST["ingresoAct"];
$centroCostoAct = @$_POST["centroCostoAct"];
($ingresoAct==" ") ? $ingresoAct="0000-00-00" : $ingresoAct=$ingresoAct;
$salidaAct = @$_POST["salidaAct"];
($salidaAct==" ") ? $salidaAct="0000-00-00" : $salidaAct=$salidaAct;
$estadoAct = @$_POST["estadoAct"];
/* segundo equipo  */
$placaAct2 = @$_POST["placaAct2"];
$marcaAct2 = @$_POST["marcaAct2"];
$marcaMotorEqAct2 = @$_POST["marcaMotorEqAct2"];
$modeloMotorAct2 = @$_POST["modeloMotorAct2"];
$numeroMotorEqAct2 = @$_POST["numeroMotorEqAct2"];
$fabricacionAct2 = @$_POST["fabricacionAct2"];
$capacidadAct2 = @$_POST["capacidadAct2"];
$tMedicionAct2 = @$_POST["tMedicionAct2"];

$updateSegundoEquipo = "UPDATE   equipos SET 
                                MAR_id01='$marcaAct2',		
                                EQU_modelo_motor='$modeloMotorAct2',	
                                EQU_marca_motor='$marcaMotorEqAct2',
                                EQU_numero_motor='$numeroMotorEqAct2',
                                EQU_a_fabricacion='$fabricacionAct2',	
                                EQU_placa='$placaAct2',	
                                EQU_capacidad='$capacidadAct2',
                                EQU_medicion='$tMedicionAct2'
                                 WHERE EQU_union='$idAct';";
$consulta="UPDATE   equipos SET 
                    MAR_id01='$marcaAct',		
                    MOD_id01='$modeloAct',	
                    EQU_modelo_motor='$modeloMotorEqAct',		
                    EQU_marca_motor='$marcaMotorEqAct',
                    EQU_numero_motor='$numeroMotorEqAct',
                    EQU_centro_costo='$centroCostoAct',
                    EQU_a_fabricacion='$fabricacionAct',	
                    EQU_a_fabricacion_pluma='$fabricacionPAct',	
                    EQU_serie_chasis='$chasisAct',	
                    EQU_placa='$placaAct',	
                    EQU_capacidad='$capacidadAct',
                    EQU_medicion='$tMedicionAct',
                    EQU_f_ingreso='$ingresoAct',		
                    EQU_f_salida='$salidaAct',		
                    EQU_estado='$estadoAct' WHERE EQU_id='$idAct';";
                    
 (isset($_POST["marcaAct2"])) ? $consulta.=$updateSegundoEquipo : ""; 
$consulta.="CALL agregar_historial('equipos','$idAct','actualizo','$usuario')";
$updateModelo = mysqli_multi_query($conexion,$consulta);
echo ($updateModelo) ? "true" : "false"; 
$conexion->close();

?>