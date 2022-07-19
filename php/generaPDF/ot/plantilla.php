<?php
function plantilla($idOt)
{
	require_once("../../conexion.php");
	$upper="";
	$carrier="";
	$grua = "";
	date_default_timezone_set("America/Lima");
	$consulta = "SELECT ORTR_f_inicio, DATE(ORTR_f_inicio) as fechaInicio,ORTR_kilometraje, FAM_descripcion,ORTR_h_chasis,ORTR_h_brazo,ORTR_h_grua,MOD_descripcion,ORTR_tipo_grua,ORTR_placa2,MAR_descripcion,ORTR_centro_costo,EQU_serie_chasis,EQU_placa,EQU_union,FAM_id,EQU_modelo_motor,EQU_principal,EQU_a_fabricacion,DATE(ORTR_f_creacion) as fechaCreacion,ORTR_tipo_evento,ORTR_medicion,ORTR_usuario,PROY_descripcion,t1.TRAB_nombres as tecnico,t2.TRAB_nombres as supervisor,t3.TRAB_nombres as operador,t4.TRAB_nombres as jefeEquipos,EQU_id,EQCO_tipo_medicion FROM ordenes_trabajo ot 
	LEFT JOIN trabajadores t1 ON ot.ORTR_tecnico_responsable =t1.TRAB_id 
	LEFT JOIN trabajadores t2 ON ot.ORTR_supervisor=t2.TRAB_id 
	LEFT JOIN trabajadores t3 ON ot.ORTR_operador=t3.TRAB_id 
	LEFT JOIN trabajadores t4 ON ot.ORTR_jefe_equipos=t4.TRAB_id 
	INNER JOIN equipos_contrato ec ON ot.EQCO_id01=ec.EQCO_id 
	INNER JOIN contratos c ON c.CONTR_id=ec.CONTR_id01 
	INNER JOIN proyectos p ON p.PROY_id=c.PROY_id01 
	INNER JOIN equipos e ON ec.EQU_id01=e.EQU_id 
	INNER JOIN familias f ON e.FAM_id01=f.FAM_id 
	INNER JOIN modelos mo ON e.MOD_id01=mo.MOD_id 
	INNER JOIN marcas ma ON e.MAR_id01=ma.MAR_id WHERE ORTR_id='$idOt'";
	foreach (mysqli_query($conexion, $consulta) as $k) {
		$familia = $k["FAM_descripcion"];
		$familiaId = $k["FAM_id"];
		$modelo = $k["MOD_descripcion"];
		$marca = $k["MAR_descripcion"];
		$placa = $k["EQU_placa"];
		$OTPlaca2 = $k["ORTR_placa2"];
		$modeloMotor = $k["EQU_modelo_motor"];
		$anio = $k["EQU_a_fabricacion"];
		$serieChasis = $k["EQU_serie_chasis"];
		$fInicio = $k["fechaInicio"];
		if($fInicio != null) {
			//original date is in format YYYY-mm-dd
			$timestamp = strtotime($fInicio); 
			$fInicio = date("d-m-Y", $timestamp );
		}
		$centroCosto = $k["ORTR_centro_costo"];
		$fCreacion = $k["fechaCreacion"];

			$timestamp2 = strtotime($fCreacion); 
			$fCreacion = date("d-m-Y", $timestamp2);

		$tipoEvento = $k["ORTR_tipo_evento"];
		$medicionCreacionOt = $k["ORTR_medicion"];
		$proyecto = $k["PROY_descripcion"];
		$operador = $k["operador"];
		$tecnico = $k["tecnico"];
		$usuario = $k["ORTR_usuario"];
		$supervisor = $k["supervisor"];
		$jefeEquipos = $k["jefeEquipos"];
		$idEquipo = $k["EQU_id"];
		$tipoMedicion = $k["EQCO_tipo_medicion"];
		$equipoPrincipal = $k["EQU_principal"];
		$equipoUnion = $k["EQU_union"];
		$kilometraje = $k["ORTR_kilometraje"];
		$arrayHChasis = ($k["ORTR_h_chasis"]!=null) ? explode("||", $k["ORTR_h_chasis"]) : ["",""];
		$arrayHGrua = ($k["ORTR_h_grua"]!=null) ? explode("||",$k["ORTR_h_grua"]) : ["",""];
		$hBrazo = $k["ORTR_h_brazo"];
		$tipoGrua = explode("||", $k["ORTR_tipo_grua"]);
			for ($i=0; $i < count($tipoGrua); $i++) { 
				if($tipoGrua[$i]==="UPPER"){
					$upper = "X";
				}else if($tipoGrua[$i]==="CARRIER") {
					$carrier = "X";
				}else if($tipoGrua[$i]==="GRUA") {
					$grua = "X";
				}
		}
	}
	$whereConsulta = $equipoPrincipal == 1 ? "EQU_union = $idEquipo" : "EQU_id = $equipoUnion" ;
	$equipoSecundario = mysqli_query($conexion,"SELECT MOD_descripcion FROM equipos e INNER JOIN modelos m ON e.MOD_id01=m.MOD_id WHERE $whereConsulta");
	foreach ($equipoSecundario as $y) {
		$modeloEquipoSecundario1 = " / ".$y["MOD_descripcion"];
		$modeloEquipoSecundario2 = $y["MOD_descripcion"];
	}
	$descripcionModelo = $familiaId == 32 ? $modeloEquipoSecundario2 : $modelo.$modeloEquipoSecundario1;
	$trabajosRealizar = mysqli_query($conexion, "SELECT TISI_descripcion FROM trabajos_realizar tr 
	INNER JOIN ordenes_trabajo ot ON tr.ORTR_id01=ot.ORTR_id
	INNER JOIN tipo_sistemas ts ON ts.TISI_id=tr.TISI_id01 WHERE ORTR_id='$idOt' AND DEOR_estado=1");

	$trabajosRealizados = mysqli_query($conexion, "SELECT * FROM trabajos_realizados tr LEFT JOIN trabajadores t ON tr.TRAB_id01=t.TRAB_id WHERE ORTR_id01='$idOt' AND TRRE_estado=1");
	$insumosUsados = mysqli_query($conexion, "SELECT INOR_descripcion,	
	INOR_codigo	,	
	INOR_marca,
	INOR_cantidad,		
	INOR_umedida,		
	INOR_moneda,		
	INOR_precio,		
	INOR_observacion	 FROM insumos_ordenes WHERE ORTR_id01='$idOt' AND INOR_estado=1");
	$existeInsumos = (mysqli_num_rows($insumosUsados)<=0) ? false : true; 
	$existeTRealizados = (mysqli_num_rows($trabajosRealizados)<=0) ? false : true; 
	$totalDuracion = 0;
	$totalCosto = 0;

	$conSigMantenimiento = "SELECT PADI_man_siguiente FROM parametros_diarios  WHERE ORTR_id01='$idOt ORDER BY PADI_id01 DESC LIMIT 1'";
	$resSigMantenimiento = mysqli_query($conexion,$conSigMantenimiento);
	foreach ($resSigMantenimiento as $x) {
			$manSiguiente = $x["PADI_man_siguiente"];
	}
	/* consulta para determinar si el man. siguiente sera reemplzado por otro (750-250) */
	$sistemaPorEquipo = mysqli_query($conexion,"SELECT EQMA_configuracion FROM equipo_mantenimiento em INNER JOIN tiempo_mantenimiento tm ON em.TIMA_id01=tm.TIMA_id WHERE EQU_id01 = '$idEquipo' AND TIMA_tiempo = '$manSiguiente'");
	foreach ($sistemaPorEquipo as $k) {
		if ($k["EQMA_configuracion"] != "") {
			$manSiguiente = $k["EQMA_configuracion"];
		}
	}
	if($tipoMedicion != "Kilometraje"){
	$tipoMedicion = " HRS";
	$tipoMedicion02 = "MEDICIÓN POR HOROMETRO";
}else {
	$tipoMedicion = " KMS";
	$tipoMedicion02 = "MEDICIÓN POR KILOMETRAJE";
	}

	$plantilla = '<body style="margin-top:0px"> 
	<table style="border:1px solid #D4D3D3" class="codes-table default-table">
	<tr>
	<td rowspan="3" style="width:190px"><img src="../images/gyt.png" width="180px"></td>
	<td style ="padding-left: 7.3rem" colspan="6"><h2>ORDEN DE TRABAJO N° '. $idOt .'</h2></td>
	</tr>
	<tr>
		<td style="border-right:1px solid #D4D3D3" class="fijo">EQUIPO</td>
		<td style="border-right:1px solid #D4D3D3" class="fijo">' . $familia . '</td>
		<td style="border-right:1px solid #D4D3D3" class="fijo">FECHA DE EJECUCIÓN</td>
		<td style="border-right:1px solid #D4D3D3" class="fijo" colspan="3">' . $fInicio . '</td>
	</tr> 
	<tr>	
		<td style="width:120px">MANTENIMIENTO</td>
		<td style="border-right:1px solid #D4D3D3">'.$tipoEvento.'</td>
		<td style="border-right:1px solid #D4D3D3"> FECHA DE REPORTE </td>
		<td style="border-right:1px solid #D4D3D3" colspan="3">'.$fCreacion.' </td>
	</tr>
	
	</table>
	<table style="border:1px solid #D4D3D3" class="codes-table default-table">
	<tr>
		<td style="width:110px; border-right:1px solid #D4D3D3">MARCA</td>
		<td style="border-right:1px solid #D4D3D3; width:250px">' . $marca . '</td>
		<td style="width:150px; border-right:1px solid #D4D3D3">KILOMETRAJE</td>
		<td style="border-right:1px solid #D4D3D3" colspan="3">'.$kilometraje.'</td>
	</tr>;
	<tr>
		<td style="width:120px; border-right:1px solid #D4D3D3">MODELO</td>
		<td style="width:160px; border-right:1px solid #D4D3D3">'.$descripcionModelo.'</td>
		<td style="width:120px; border-right:1px solid #D4D3D3">HOROMETRO CHASIS</td>
		<td colspan="3" style="padding:0"> <table class="subtable">
		<tr>
			<td>ANA</td>
			<td style="width:80px;border-right:1px solid #D4D3D3">'.$arrayHChasis[0].'</td>
			<td>DIGI</td>
			<td style="width:80px;border-right:1px solid #D4D3D3">'.$arrayHChasis[1].'</td>
		</tr>
		</table></td>
	</tr>
	<tr>
		<td style="width:120px; border-right:1px solid #D4D3D3">PLACA / SERIE</td>
		<td style="border-right:1px solid #D4D3D3">
		<table class="subtable">
		<tr>
			<td >'.$placa.'</td>
			<td style="width:60px;border-right:1px solid #D4D3D3;border-left:1px solid #D4D3D3">PLACA II:</td>
			<td>'.$OTPlaca2.'</td>
		</tr>
		</table>
		</td>
		<td style="width:120px;border-right:1px solid #D4D3D3">HOROMETRO GRUA</td>
		<td colspan="3" style="width:120px;border-right:1px solid #D4D3D3" style="padding:0"><table class="subtable">
		<tr>
			<td>ANA</td>	
			<td style="width:80px;border-right:1px solid #D4D3D3">'.$arrayHGrua[0].'</td>
			<td>DIGI</td>
			<td style="width:80px;border-right:1px solid #D4D3D3">'.$arrayHGrua[1].'</td>
		</tr>
		</table></td>
	</tr>
	<tr>
		<td style="width:120px;border-right:1px solid #D4D3D3">SERIE CHASIS</td>
		<td style="border-right:1px solid #D4D3D3">' . $serieChasis . '</td>
		<td style="width:150px; border-right:1px solid #D4D3D3">HOROMETRO DE BH.</td>
		<td style="border-right:1px solid #D4D3D3" colspan="3">'.$hBrazo.'</td>
		
	</tr>
	<tr>
		<td style="width:120px;border-right:1px solid #D4D3D3">AÑO</td>
		<td style="border-right:1px solid #D4D3D3">' . $anio . '</td>
		<td style="border-right:1px solid #D4D3D3"> OPERADOR </td>
		<td style="border-right:1px solid #D4D3D3" colspan="3">' . $operador . '</td>
	</tr>
	<tr>
		<td style="width:120px;border-right:1px solid #D4D3D3">PROYECTO</td>
		<td style="border-right:1px solid #D4D3D3">' . $proyecto . '</td>
		<td style="border-right:1px solid #D4D3D3;padding:0">
		<table class="subtable">
		<tr>
			<td style="border-right:1px solid #D4D3D3">CARRIER</td>
			<td style="width:80px;border-right:1px solid #D4D3D3;text-align:center;">'.$carrier.'</td>
		</tr>
		</table>
		</td>
		<td style="border-right:1px solid #D4D3D3;padding:0" colspan="3"> 
		<table class="subtable">
		<tr>
			<td style="border-right:1px solid #D4D3D3">UPPER</td>	
			<td style="width:80px;border-right:1px solid #D4D3D3;text-align:center;">'.$upper.'</td>
			<td style="border-right:1px solid #D4D3D3">GRUA</td>
			<td style="width:80px;border-right:1px solid #D4D3D3;text-align:center;">'.$grua.'</td>
		</tr>
		</table>
		 </td>
	</tr>
	<tr>
		<td style="width:120px;border-right:1px solid #D4D3D3">C.C</td>
		<td style="border-right:1px solid #D4D3D3">'.$centroCosto.'</td>
		<td style="border-right:1px solid #D4D3D3" colspan="2"> '.$tipoMedicion02.' </td>
		<td style="border-right:1px solid #D4D3D3" colspan="2">'.$medicionCreacionOt.'</td>
	</tr>
	</table>
		<table class="tabla-titulos">
				<tr style="width:720px;">
					<td class="td_subtitulos letra">
					Trabajos por realizar
					</td>
				</tr>
		</table>
	<b>MANTENIMIENTO '.$tipoEvento.' DE '.$manSiguiente .' '.$tipoMedicion.': </b>'; 
	foreach ($trabajosRealizar as $y) {
		$listaCambios.= '<span> ' . $y["TISI_descripcion"] . '</span>,';
	}

	$plantilla .= ' '.substr($listaCambios, 0, -1).'<br>
			<table class="tabla-titulos">
					<tr style="width:720px;">
						<td class="td_subtitulos letra">
						Trabajos realizados
						</td>
					</tr>
			</table>
	';
		if($existeTRealizados){
			$plantilla .= '
			<table class="codes-table default-table">
					<thead>
					<tr style="height:20px;">
							<th width="70%">Descripción</th>
							<th>Trabajador</th>
							<th style="width:10%;">Duración</th>
					</tr>
			</thead>
			<tbody>';
			foreach ($trabajosRealizados as $y) {
				$totalDuracion+=$y["TRRE_duracion"];
				$plantilla .= '<tr>
				<td width="60%">'.$y["TRRE_descripcion"].'</td>
				<td>'.$y["TRAB_nombres"].'</td>
				<td>'.$y["TRRE_duracion"].'</td>
				</tr>';
			}
			$plantilla.=' <tr>
			<td colspan="2"></td>
			<td>'.$totalDuracion.'</td>
		</tr> </tbody>
    </table>';
		}else{
			for ($i=0; $i < 1; $i++) { 
				$plantilla .= '<div style="height:330px;"></div>';
			}

		}
        
		$plantilla.='<br><br>
	<div class="codes">
		<table class="tabla-titulos">
				<tr style="width:720px;">
					<td class="td_subtitulos letra">
					Insumos Usados
					</td>
				</tr>
		</table>	

    <table class="codes-table default-table">
        <thead>
        <tr style="height:20px;">
            <th>Descripción</th>
            <th>Serie</th>
            <th>Marca</th>
            <th style="width:8.5%">Cantidad</th>
            <th style="width:10%">U.M.</th>
            <th>Observación</th>
        </tr>
		</thead>
		<tbody>';
		$cont=0;
		if($existeInsumos){
			foreach ($insumosUsados as $y) {
				$totalCosto+=0;
				$plantilla .= '<tr>
				<td class="fijo">'.$y["INOR_descripcion"].'</td>
				<td class="fijo">'.$y["INOR_codigo"].'</td>
				<td class="fijo">'.$y["INOR_marca"].'</td>
				<td class="fijo" style="height:5%">'.$y["INOR_cantidad"].'</td>
				<td class="fijo">'.$y["INOR_umedida"].'</td>
				<td class="fijo">'.$y["INOR_observacion"].'</td>
				</tr>';
			}
		}else{
			for ($i=0; $i <= 11; $i++) { 
				$plantilla .= '<tr style="height:50px">
				<td class="fijo"></td>
				<td class="fijo"></td>
				<td class="fijo"></td>
				<td class="fijo"></td>
				<td class="fijo"></td>
				<td class="fijo"></td>
				</tr>';
			}
		}
		$plantilla.='</tbody>
		</table>';
	$plantilla .= '<br>
	</body>';

	$plantillaImagenes='<body>
	<table class="tabla-titulos">
			<tr style="width:720px;">
				<td class="td_subtitulos letra">
				IMAGENES DE TRABAJOS REALIZADOS
				</td>
			</tr>
	</table>
	<table class=" default-table" style="table-layout: fixed;">
	';
	
	$conImagenesSec = "SELECT IMOT_identificador FROM imagen_ots io INNER JOIN ordenes_trabajo ot ON io.ORTR_id01=ot.ORTR_id  WHERE ORTR_id01='$idOt' AND IMOT_estado=1";
	$ruta="../../../archivos/imagenOts/";
	$contador=0;
	$auxiliar=2;
	$resImagenes = mysqli_query($conexion,$conImagenesSec);
		$plantillaImagenes.='<tr>';
		foreach ($resImagenes as $x) {
			if($auxiliar==$contador) { $plantillaImagenes.='<tr>'; $auxiliar+=2; }
			$plantillaImagenes.='<td>
			<img src="'.$ruta.'/'.$x["IMOT_identificador"].'" style="margin:10px" width="355px"  height="220px"></td>';
			if($auxiliar==$contador) $plantillaImagenes.='</tr>';
			$contador++;
		}
		$plantillaImagenes.='</tr>';
	$plantillaImagenes.='</table>
	<br>
	</body>';

	$footer = '<footer>
	<table class="codes-table default-table">
        <thead>
        <tr style="height:20px;">
            <th>JEFE DE EQUIPOS</th>
            <th>SUP. DE ÁREA</th>
            <th>TECNICO DE CAMPO</th>
        </tr>
		</thead>
		<tbody>
		<tr>
		<td style=" width:33%;">
		<table class="subtable">
		<tr>
			<td>'.$jefeEquipos.'</td>
		</tr>
		</table>
		</td>
		<td style="width:33%;">
		<table class="subtable">
		<tr>
			<td>'.$supervisor.'</td>
		</tr>

		</table>
		</td>
		<td style="padding:0; width:33%;">
		<table class="subtable">
		<tr>
			<td>'.$tecnico.'</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
			<td style="height:65px;">Firma</td>
			<td style="height:65px;">Firma</td>
			<td style="height:65px;">Firma</td>
		</tr>
		</tbody>
		</table>
		<span class="texto-secundario">'.$usuario.'</span>
		</footer>';
	return [$plantilla, $footer,$plantillaImagenes];
}
