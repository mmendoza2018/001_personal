<?php 
require_once("../../conexion.php");
$idAct = @$_POST["idAct"];
$descripcion = @$_POST["descripcion"];
$codigo = @$_POST["codigo"];
$marca = @$_POST["marca"];
$cantidad =@$_POST["cantidad"];
$uMedida =@$_POST["uMedida"];
$moneda =@$_POST["moneda"];
$precio =@$_POST["precio"];
$total =@$_POST["total"];
$observacion = @$_POST["observacion"];
$estado = @$_POST["estado"];

$resTRealizados = mysqli_query($conexion,"UPDATE insumos_ordenes SET    INOR_descripcion = '$descripcion',	
                                                                        INOR_codigo = '$codigo',		
                                                                        INOR_marca = '$marca',		
                                                                        INOR_cantidad = '$cantidad',		
                                                                        INOR_umedida = '$uMedida',		
                                                                        INOR_moneda = '$moneda',		
                                                                        INOR_precio = '$precio',			
                                                                        INOR_observacion = '$observacion',
                                                                        INOR_estado = '$estado'	 WHERE INOR_id='$idAct'");
echo ($resTRealizados) ? "true" : "false"; 
$conexion->close();
?>