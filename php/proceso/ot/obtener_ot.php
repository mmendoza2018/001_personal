<?php 
require_once("../../conexion.php");
$idOrdenTrabajo = $_POST["idOrdenTrabajo"];
$arregloOrden=[];
$arrayHChasis = ["",""];
$arrayHGrua = ["",""];
$consulta = "SELECT ORTR_id, EQU_codigo,EQU_placa, FAM_descripcion,ORTR_kilometraje,ORTR_h_chasis,ORTR_h_grua,ORTR_h_brazo,ORTR_tipo_grua,ORTR_usuario, EQCO_id01, ORTR_tipo_evento, ORTR_supervisor,EQCO_tipo_medicion, ORTR_descripcion,ORTR_placa2,ORTR_centro_costo, ORTR_medicion, t1.TRAB_nombres as nombre1, t2.TRAB_nombres as nombre2,t3.TRAB_nombres as nombre3,t4.TRAB_nombres as nombre4, DATE(ORTR_f_creacion) as fCreacion, ORTR_f_inicio, DATE(ORTR_f_cierre) as fCierre,DATE(ORTR_f_inicio) as fInicio, ORTR_estado FROM ordenes_trabajo ot INNER JOIN equipos_contrato ec ON ot.EQCO_id01=ec.EQCO_id 
INNER JOIN equipos e ON ec.EQU_id01=e.EQU_id 
INNER JOIN familias f ON e.FAM_id01 = f.FAM_id 
left JOIN trabajadores t1 ON ot.ORTR_tecnico_responsable=t1.TRAB_id 
left JOIN trabajadores t2 ON ot.ORTR_supervisor=t2.TRAB_id 
LEFT JOIN trabajadores t3 ON ot.ORTR_operador=t3.TRAB_id
LEFT JOIN trabajadores t4 ON ot.ORTR_jefe_equipos=t4.TRAB_id
 WHERE ORTR_id='$idOrdenTrabajo'";
 $resOT = mysqli_query($conexion,$consulta);
 //var_dump($resOT->field_count);
 foreach ($resOT as $y) {
    if ($y["ORTR_h_chasis"] != null ) {
       $arrayHChasis = explode("||", $y["ORTR_h_chasis"]);
    }
    if ($y["ORTR_h_grua"] !=null) {
       $arrayHGrua = explode("||",  $y["ORTR_h_grua"]);
    }
    
    $fila =[
    "idOt" => $y["ORTR_id"],	
    "codigoPlacaEquipo" => $y["EQU_codigo"]." - ".$y["EQU_placa"],	
    "familia" => $y["FAM_descripcion"],	
    "idEquipoContrato" =>$y["EQCO_id01"],	
    "tipoEvento" =>$y["ORTR_tipo_evento"],
    "tipoMedicion" =>$y["EQCO_tipo_medicion"],
    "tecnicoResponsable" =>$y["nombre1"],	
    "supervisor" =>$y["nombre2"],	
    "operador" =>$y["nombre3"],	
    "jefeEquipos" =>$y["nombre4"],	
    "ordenDescripcion" =>$y["ORTR_descripcion"],		
    "kilometraje" =>$y["ORTR_kilometraje"],
    "hChasisAna" =>$arrayHChasis[0],		
    "hChasisDigi" =>$arrayHChasis[1],		
    "hGruaAna" =>$arrayHGrua[0],		
    "hGruaDigi" =>$arrayHGrua[1],		
    "hBrazo" =>$y["ORTR_h_brazo"],		
    "tipoGrua" =>$y["ORTR_tipo_grua"],		
    "usuario" =>$y["ORTR_usuario"],		
    "medicion" =>$y["ORTR_medicion"],	
    "fCreacion" =>$y["fCreacion"],		
    "fInicio" =>$y["fInicio"],		
    "fCierre" =>$y["fCierre"],		
    "centroCosto" =>$y["ORTR_centro_costo"],		
    "estado" =>$y["ORTR_estado"],
    "placa2" =>$y["ORTR_placa2"],
   ];
    array_push($arregloOrden,$fila) ;
 }
    echo json_encode($arregloOrden);
?>