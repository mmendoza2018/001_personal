<?php
function plantilla($idPersona)
{
	require_once("../../conexion.php");
$consultaPer = "SELECT * FROM gyt_personas pe 
								INNER JOIN gyt_departamentos d ON d.id_departamento  = pe.id_departamento
								INNER JOIN gyt_puesto pu ON pu.id_puesto  = pe.id_puesto 
								WHERE id_persona='$idPersona'";
	$resListaPersonas = mysqli_query($conexion, $consultaPer);
	$arrayResultado = mysqli_fetch_assoc($resListaPersonas);
	$nombresPersona = 	$arrayResultado['per_nombres'] .' '. $arrayResultado['per_nombres'];
	$consultaDocs = "SELECT * FROM gyt_documentos d
							INNER JOIN gyt_tipodocumento td ON d.id_tipodocumento = td.id_tipodocumento
							INNER JOIN gyt_personas p ON d.id_persona = p.id_persona WHERE d.id_persona=$idPersona";
	function calculaEdad($fechanacimiento){
			list($ano,$mes,$dia) = explode("-",$fechanacimiento);
			$ano_diferencia  = date("Y") - $ano;
			$mes_diferencia = date("m") - $mes;
			$dia_diferencia   = date("d") - $dia;
			if ($dia_diferencia < 0 || $mes_diferencia < 0)
				$ano_diferencia--;
		return $ano_diferencia;
	}
	$edadActual = calculaEdad($arrayResultado['per_fechanac']);
	$resDocsPersona = mysqli_query($conexion, $consultaDocs);
	$header = '<header class="w-100">
	<img src="../images/gyt.png"  width="150px" alt="">
	</header>';
	$plantilla = '<body style="margin-top:0px"> 
	<div class="w-100 text-center my-5">
	<h2 class="my-0 pb-0">DOCUMENTOS DE PERSONAL</h2>
	</div>
	<table class="table_section">
			<tr style="width:720px;">
				<td>
					DATOS PERSONALES
				</td>
			</tr>
	</table>
	<table class="table w-80">
	<tr>
		<td class="w-30">Numero Documento</td>
		<td>: '.$arrayResultado['id_persona'] .'</td>
	</tr>
	<tr>	
		<td>Nombres y Apellidos</td>
		<td>: '.$nombresPersona .'</td>
	</tr>
	<tr>
		<td>Fecha de Nacimiento</td>
		<td>: '.$arrayResultado['per_fechanac'] .'</td>
	</tr>
	<tr>
		<td>Edad</td>
		<td>: '. $edadActual .'</td>
	</tr>
	<tr>
		<td>Telefono</td>
		<td>: '.$arrayResultado['per_telefono'] .'</td>
	</tr>
	</table>
	<table class="table_section">
			<tr style="width:720px;">
				<td>
				DATOS PERSONALES
				</td>
			</tr>
	</table>
	<table class="table w-80">
		<tr>
			<td class="w-30">Fecha de Ingreso</td>
			<td>: '.$arrayResultado['per_fechaingreso'] .'</td>
		</tr>
		<tr>	
			<td>Regimen</td>
			<td>: '.$arrayResultado['per_regimen'] .'</td>
		</tr>
		<tr>
			<td>Puesto Laboral</td>
			<td>: '.$arrayResultado['pue_descripcion'] .'</td>
		</tr>
		<tr>
			<td>Departamento</td>
			<td>: '.$arrayResultado['dep_descripcion'] .'</td>
		</tr>
	</table>
	<table class="table_section">
			<tr style="width:720px;">
				<td>
				DATOS LABORALES
				</td>
			</tr>
	</table>
		<table class="table">
		<thead>
		<tr style="height:20px;">
				<th>ID</th>
				<th>DOCUMENTO</th>
				<th>NUMERO DOCUMENTO</th>
				<th style="width:8.5%">FECHA EMISION</th>
				<th>FECHA VENCIMIENTO</th>
		</tr>
</thead>
<tbody>';
	$cont = 0;
	foreach ($resDocsPersona as $y) {
		$plantilla .= '<tr>
		<td class="fijo">' . $y["id_documento"] . '</td>
		<td class="fijo">' . $y["tdoc_descripcion"] . '</td>
		<td class="fijo">' . $y["doc_numdoc"] . '</td>
		<td class="fijo" style="height:5%">' . $y["doc_fecha1"] . '</td>
		<td class="fijo">' . $y["doc_fecha2"] . '</td>
		</tr>';
	}
	$plantilla .= '</tbody>
</table>';

	$plantilla .= '<br>
	</body>';

	$footer = '<footer>
		<span class="texto-secundario"> Responsable: ' . $_SESSION["nombre_trabajador"] . '</span>
		</footer>';
	return [$plantilla, $header, $footer, 'documentos'];
}
