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
	$nombresPersona = 	$arrayResultado['per_nombres'] . ' ' . $arrayResultado['per_apellidos'];
	function calculaEdad($fechanacimiento)
	{
		list($ano, $mes, $dia) = explode("-", $fechanacimiento);
		$ano_diferencia  = date("Y") - $ano;
		$mes_diferencia = date("m") - $mes;
		$dia_diferencia   = date("d") - $dia;
		if ($dia_diferencia < 0 || $mes_diferencia < 0)
			$ano_diferencia--;
		return $ano_diferencia;
	}
	$edadActual = calculaEdad($arrayResultado['per_fechanac']);
	$header = '<header class="w-100">
	<img src="../images/gyt.png"  width="150px" alt="">
	</header>';
	$plantilla = '<body style="margin-top:0px"> 
	<div class="w-100 text-center my-4">
	<h2 class="my-0 pb-0">FICHA UNICA DE REGISTRO DE PERSONAL</h2>
	<div class="caja_foto mt-3">
	</div>
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
		<td class="w-30">Tipo Documento</td>
		<td>: ' . $arrayResultado['per_tipodoc'] . '</td>
	</tr>
	<tr>
		<td class="w-30">Numero Documento</td>
		<td>: ' . $arrayResultado['id_persona'] . '</td>
	</tr>
	<tr>	
		<td>Nombres</td>
		<td>: ' . $arrayResultado['per_nombres'] . '</td>
	</tr>
	<tr>	
		<td>Apellidos</td>
		<td>: ' . $arrayResultado['per_apellidos'] . '</td>
	</tr>
	<tr>
		<td>Sexo</td>
		<td>: ' . $arrayResultado['per_sexo'] . '</td>
	</tr>
	<tr>
		<td>Fecha de Nacimiento</td>
		<td>: ' . $arrayResultado['per_fechanac'] . '</td>
	</tr>
	<tr>
		<td>Edad</td>
		<td>: ' . $edadActual . '</td>
	</tr>
	<tr>
		<td>Lugar de Nacimiento</td>
		<td>: ' . $arrayResultado['per_lugarnac'] . '</td>
	</tr>
	<tr>
		<td>Estado Civil</td>
		<td>: ' . $arrayResultado['per_estadociv'] . '</td>
	</tr>
	<tr>
		<td>Hijos</td>
		<td>: ' . $arrayResultado['per_hijos'] . '</td>
	</tr>
	<tr>
		<td>Email</td>
		<td>: ' . $arrayResultado['per_email'] . '</td>
	</tr>
	<tr>
		<td>Nivel de Estudios</td>
		<td>: ' . $arrayResultado['per_estudios'] . '</td>
	</tr>
	<tr>
		<td>Direccion</td>
		<td>: ' . $arrayResultado['per_direccion'] . '</td>
	</tr>
	<tr>
		<td>Departamento</td>
		<td>: ' . $arrayResultado['country_id'] . '</td>
	</tr>
	<tr>
		<td>Provincia</td>
		<td>: ' . $arrayResultado['state_id'] . '</td>
	</tr>
	<tr>
		<td>Distrito</td>
		<td>: ' . $arrayResultado['city_id'] . '</td>
	</tr>
	<tr>
		<td>Teléfono</td>
		<td>: ' . $arrayResultado['per_telefono'] . '</td>
	</tr>
	<tr>
		<td>Tipo de Sangre</td>
		<td>: ' . $arrayResultado['per_sangre'] . '</td>
	</tr>
	</table>
	<table class="table_section">
			<tr style="width:720px;">
				<td>
				DATOS LABORALES
				</td>
			</tr>
	</table>
	<table class="table w-80">
		<tr>
			<td class="w-30">Puesto Laboral </td>
			<td>: ' . $arrayResultado['pue_descripcion'] . '</td>
		</tr>
		<tr>	
			<td>Area de Trabajo</td>
			<td>: ' . $arrayResultado['dep_descripcion'] . '</td>
		</tr>
		<tr>
			<td>Fecha de Ingreso</td>
			<td>: ' . $arrayResultado['per_fechaingreso'] . '</td>
		</tr>
		<tr>
			<td>Sueldo</td>
			<td>: ' . $arrayResultado['per_sueldo'] . '</td>
		</tr>
		<tr>
			<td>Bono</td>
			<td>: ' . $arrayResultado['per_bono'] . '</td>
		</tr>
		<tr>
			<td>Regimen</td>
			<td>: ' . $arrayResultado['per_regimen'] . '</td>
		</tr>
		<tr>
			<td>Regimen de trabajo</td>
			<td>: ' . $arrayResultado['per_regimen_tra'] . '</td>
		</tr>
		<tr>
			<td>Sistema Pension</td>
			<td>: ' . $arrayResultado['per_regimen_tra'] . '</td>
		</tr>
		<tr>
			<td>CUSPP</td>
			<td>: ' . $arrayResultado['per_cuspp'] . '</td>
		</tr>
		<tr>
			<td>AFP</td>
			<td>: ' . $arrayResultado['per_afp'] . '</td>
		</tr>
		<tr>
			<td>Flujo</td>
			<td>: ' . $arrayResultado['per_flujo'] . '</td>
		</tr>

	</table>
	<table class="table_section">
			<tr style="width:720px;">
				<td>
				DATOS DE EMERGENCIA
				</td>
			</tr>
	</table>
	<table class="table w-80">
		<tr>
			<td rowspan="3" class="w-20 fw-bold">EMERGENCIA 1</td>
			<td class="w-12">Nombres </td>
			<td>: ' . $arrayResultado['per_nombre1'] . '</td>
		</tr>
		<tr>
			<td>Parentesco </td>
			<td>: ' . $arrayResultado['per_parentesco1'] . '</td>
		</tr>
		<tr>
			<td>Telefonos </td>
			<td>: ' . $arrayResultado['per_celular1'] . '</td>
		</tr>
	</table>
	<table class="table w-80 mt-3">
		<tr>
			<td rowspan="3" class="w-20 fw-bold">EMERGENCIA 2</td>
			<td class="w-12">Nombres </td>
			<td>: ' . $arrayResultado['per_nombre2'] . '</td>
		</tr>
		<tr>
			<td>Parentesco </td>
			<td>: ' . $arrayResultado['per_parentesco2'] . '</td>
		</tr>
		<tr>
			<td>Telefonos </td>
			<td>: ' . $arrayResultado['per_celular2'] . '</td>
		</tr>
	</table>
	<table class="table w-80 mt-3">
		<tr>
			<td rowspan="3" class="w-20 fw-bold">EMERGENCIA 3</td>
			<td class="w-12">Nombres </td>
			<td>: ' . $arrayResultado['per_nombre3'] . '</td>
		</tr>
		<tr>
			<td>Parentesco </td>
			<td>: ' . $arrayResultado['per_parentesco3'] . '</td>
		</tr>
		<tr>
			<td>Telefonos </td>
			<td>: ' . $arrayResultado['per_celular3'] . '</td>
		</tr>
	</table>
	<table class="table_section">
	<tr style="width:720px;">
		<td>
		DATOS BANCARIOS
		</td>
	</tr>
</table>
<table class="table w-80">
	<tr>
		<td class="w-30">Entidad Financiera</td>
		<td>: ' . $arrayResultado['per_banco'] . '</td>
	</tr>
	<tr>	
		<td>Numero de Cuenta</td>
		<td>: ' . $arrayResultado['per_cuenta'] . '</td>
	</tr>
	<tr>	
		<td>Numero de CCI</td>
		<td>: ' . $arrayResultado['per_cci'] . '</td>
	</tr>
</table>

<div class="w-70 mx-auto mt-3">
<div class="caja_foto mt-5" style="margin-left:50px">
</div>
<div class="w-60 ms-auto">
<div class="text-center">
________________________________________<br>
<p class="my-0">'.$nombresPersona.'</p>
<p class="my-0">'.$arrayResultado['per_tipodoc'] .' : '. $arrayResultado['id_persona'] . '</p>
</div>
</div>
</div>

	</body>';


	$footer = '<footer>
		<span class="texto-secundario"> Responsable: ' . $_SESSION["nombre_trabajador"] . '</span>
		</footer>';
	return [$plantilla, $header, $footer, 'documentos'];
}
