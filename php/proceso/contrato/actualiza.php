<?php 
session_start();

require_once("../../conexion.php");
$idContrato = $_POST["idContrato"];
$descripcion = $_POST["descripcion"];
$proyecto = $_POST["proyecto"];
$cliente = $_POST["cliente"];
$fechaInicio = $_POST["fechaInicio"];
(isset($_POST["fechaFin"])) ? $fechaFin = $_POST["fechaFin"] : $fechaFin= "0000-00-00";
$estado = $_POST["estado"];
$numero = $_POST["numero"];

if ($estado == "CIERRE Y FINALIZACIÓN") {
  $equiposActivos = mysqli_query($conexion,"SELECT EQCO_id FROM equipos_contrato ec INNER JOIN equipos e ON ec.EQU_id01 = e.EQU_id WHERE CONTR_id01= '$idContrato' AND EQCO_estado_contrato = 1 AND EQU_principal=1");
  $numeroequipos = mysqli_num_rows($equiposActivos);
  if ($numeroequipos > 0) {
    $descripcionMensaje = "Hay $numeroequipos ".(($numeroequipos < 2) ? 'equipo habil' : 'equipos habiles')."  en el contrato, todos los equipos de este contrato deben estar finalizados.";
    echo json_encode(["false",$descripcionMensaje]);
    die();
  }
}

$consulta = "UPDATE contratos SET   CONTR_descripcion = '$descripcion',		
                                    CONTR_numero = '$numero',
                                    CLIE_id01 = '$cliente',	
                                    PROY_id01 = '$proyecto',	
                                    CONTR_f_inicio = '$fechaInicio',		
                                    CONTR_f_fin = '$fechaFin',		
                                    CONTR_estado = '$estado' WHERE CONTR_id='$idContrato'";
$resConsulta= mysqli_query($conexion,$consulta);
echo ($resConsulta) ? json_encode(["true",$proyecto]) : json_encode(["false",$proyecto]);
?>