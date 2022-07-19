<?php 

 function plantilla ($idEquipo) {
	 require("../../conexion.php");
	 
     $consulta="SELECT * FROM equipos e 	INNER JOIN familias fa  ON e.FAM_id01=fa.FAM_id  
     INNER JOIN marcas ma ON e.MAR_id01 = ma.MAR_id 
     INNER JOIN modelos mo ON e.MOD_id01 = mo.MOD_id
     INNER JOIN propietarios p ON e.PROP_id01=p.PROP_id WHERE EQU_id='$idEquipo'";
$resConsulta= mysqli_query($conexion,$consulta);
$hoy = date("d-m-Y");
foreach ($resConsulta as $x) {
$codigo=$x["EQU_codigo"];
$modelo=$x["MOD_descripcion"];
$marca=$x["MAR_descripcion"];
$familiaEquipo=$x["FAM_descripcion"];
$modeloMotor=$x["EQU_modelo_motor"];
$motor=$x["EQU_numero_motor"];
$fabricacion=$x["EQU_a_fabricacion"];
$fabricacionP=$x["EQU_a_fabricacion_pluma"];
$chasis=$x["EQU_serie_chasis"];
$placa=$x["EQU_placa"];
$capacidad=$x["EQU_capacidad"];
$propietario=$x["PROP_descripcion"];
$ingreso=$x["EQU_f_ingreso"];
$salida=$x["EQU_f_ingreso"];
$centroCosto=$x["EQU_centro_costo"];

}
$plantilla = '<body>
<hr>
<table >
<tr style="width:720px;">
<td class="td_subtitulos letra">
Informacion del equipo
</td>
</tr>
</table>
<hr>
<table>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Familia </td>
<td colspan="3"> : '.$familiaEquipo.' </td>
</tr>
<tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Codigo </td>
<td colspan="3"> : '.$codigo.' </td>
</tr>
<tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Placa </td>
<td colspan="3"> : '.$placa.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Marca </td>
<td colspan="4"> : '.$marca.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Modelo </td>
<td> : '.$modelo.'</td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;">Modelo del motor</td>
<td> :'.$modeloMotor.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:140px;"> N° de motor </td>
<td> : '.$motor.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:140px;"> Capacidad </td>
<td> : '.$capacidad.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Centro de costo</td>
<td> : '.$centroCosto.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> N° serie chasis</td>
<td> : '.$chasis.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:110px;"> A. fabricacion </td>
<td colspan="2"> :'.$fabricacionP.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:140px;"> Propietario </td>
<td> :'.$propietario.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> F. ingreso </td>
<td> : '.$ingreso.'</td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:110px;">F. Salida </td>
<td colspan="2"> : '.$salida.' </td>
</tr>
</table>

<hr>
<table cellspacing="0" >
<tr style="width:720px;">
<td class="td_subtitulos">
Imagenes Adjuntas
</td>
</tr>
</table>
<hr style="margin-bottom:10px">
<table style="margin-left:30px">
<tr>';

$conImagenes = "SELECT * FROM imagen_equipos ie INNER JOIN tipo_img_equipos ti ON ie.TIIM_id01=ti.TIIM_id  WHERE EQU_id01='$idEquipo'";
$resImagenes = mysqli_query($conexion,$conImagenes);
foreach ($resImagenes as $x) {
if ($x["TIIM_descripcion"]=="principal") {
$principal=$x["IMEQ_identificador"];
}
if ($x["TIIM_descripcion"]=="segundo") {
$segundo=$x["IMEQ_identificador"];
}
if ($x["TIIM_descripcion"]=="tercero") {
$tercero=$x["IMEQ_identificador"];
}
}
$ruta="../../../archivos/equipos/imagenes/";
$plantilla.='<td rowspan="2">
<img src="'.$ruta.'principal/'.$principal.'" alt="" width="350px" height="310px"></td>
<td> <img src="'.$ruta.'segundo/'.$segundo.'" alt="" width="250px" height="153px"></td> </td>
</tr>
<tr>
<td>
<img src="'.$ruta.'tercero/'.$tercero.'" alt="" width="250px" height="153px">
</td>
</tr>
<br>
</table> <br><br><br><br><br><br><br><br><br><br>';
$consultaEquipoSecudanrio = mysqli_query($conexion, "SELECT * FROM equipos e 	
                 INNER JOIN familias fa  ON e.FAM_id01=fa.FAM_id  
                 INNER JOIN marcas ma ON e.MAR_id01 = ma.MAR_id 
                 INNER JOIN modelos mo ON e.MOD_id01 = mo.MOD_id
                 WHERE EQU_union='$idEquipo'");
foreach ($consultaEquipoSecudanrio as $k) {
$familia2 = $k["FAM_descripcion"]; 
$Codigo2 = $k["EQU_codigo"]; 
$placa2 = $k["EQU_placa"]; 
$marca2 = $k["MAR_descripcion"]; 
$modelo2 = $k["MOD_descripcion"]; 
$modeloMotor2 = $k["EQU_modelo_motor"]; 
$capacidad2 = $k["EQU_capacidad"];
}
if ($consultaEquipoSecudanrio->num_rows>0) {
$plantilla.='<hr >
<table>
<tr style="width:720px;">
<td class="td_subtitulos letra">
Equipo secundario
</td>
</tr>
</table>
<hr>
<table>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Familia </td>
<td colspan="3"> : '.$familia2.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Codigo </td>
<td colspan="3"> : '.$Codigo2.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Placa </td>
<td colspan="3"> : '.$placa2.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Marca </td>
<td colspan="4"> : '.$marca2.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;"> Modelo </td>
<td> : '.$modelo2.'</td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:160px;">Modelo del motor</td>
<td> :'.$modeloMotor2.' </td>
</tr>
<tr>
<td style="width:30px;"></td>
<td class="tabla_datos" style="width:140px;"> Capacidad </td>
<td> : '.$capacidad2.' </td>
</tr>
</table>';

}
$plantilla.='<br>
</body>';
	return [$plantilla,$codigo];
 }
 ?>