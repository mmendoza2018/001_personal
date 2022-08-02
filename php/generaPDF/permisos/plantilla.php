<?php
function plantilla($idPermiso)
{
	require_once("../../conexion.php");
	$resPermisos = mysqli_query($conexion, "SELECT * FROM gyt_permisos pm 
                                  INNER JOIN gyt_motivos mo ON pm.id_motivo = mo.id_motivo 
                                  INNER JOIN gyt_personas ps ON pm.id_persona = ps.id_persona 
                                  INNER JOIN gyt_departamentos d ON d.id_departamento  = ps.id_departamento
																	INNER JOIN gyt_puesto pu ON pu.id_puesto  = ps.id_puesto 
																	WHERE id_permiso='$idPermiso'");
	$arrayResultado = mysqli_fetch_assoc($resPermisos);
	$nombresPersona = 	$arrayResultado['per_nombres'] . ' ' . $arrayResultado['per_apellidos'];
	$header = '<header class="w-100">
	<img src="../images/gyt.png"  width="150px" alt="">
	</header>';
	$plantilla = '<body style="margin-top:0px"> 
	<div class="text-center my-5">
	<h2 >PAPELETA DE SALIDA</h2>
	</div>
	<table class="table tabla_permiso mt-5">
	<tr>
		<td class="fw-bold w-25">DNI</td>
		<td>: '.$arrayResultado["id_persona"].'</td>
		<td class="fw-bold">Puesto</td>
		<td>: '.$arrayResultado["pue_descripcion"].'</td>
	</tr>
	<tr>
		<td class="fw-bold">Nombres y Apellidos</td>
		<td>: '.$nombresPersona.'</td>
		<td class="fw-bold">Departamento</td>
		<td>: '.$arrayResultado["dep_descripcion"].'</td>
	</tr>
	<tr>
		<td class="fw-bold">Fecha registro</td>
		<td>: '.$arrayResultado["perm_fechareg"].'</td>
		<td class="fw-bold"># Permiso</td>
		<td>: '.$arrayResultado["id_permiso"].'</td>
	</tr>
	</table>
	<div class="ms-2 pt-2">
	<b>MOTIVO DE PERMISO</b>
	<p>'.$arrayResultado["mot_descripcion"].'</p>
	<b>OBSERVACIÓN</b>
	<p>'.$arrayResultado["perm_observaciones"].'</p>
	<b>FECHAS</b>
	<div class="mt-2">
	<span >Inicio permiso</span><span>: '.$arrayResultado["perm_inicio"].'</span><br>
	<span>Inicio permiso</span><span>: '.$arrayResultado["perm_fin"].'</span>
	</div>
	</div>
	<table class="mt-5">
	<tr>
		<td class="w-33 text-center">
			____________________________________
			<p class="pt-3">'.$nombresPersona.'</p>
		</td>
		<td class="w-33 text-center">
			____________________________________
			<p class="pt-3">VB. RECURSOS HUMANOS</p>
		</td>
		<td class="w-33 text-center">
			____________________________________
			<p class="pt-3">JEFE INMEDIATO</p>
		</td>
	</tr>
	</table>
	<br><br><br><br><br><br><br><br>
	<div class="text-center my-5">
	<h2 >PAPELETA DE SALIDA</h2>
	</div>
	<table class="table tabla_permiso mt-5">
	<tr>
		<td class="fw-bold w-25">DNI</td>
		<td>: '.$arrayResultado["id_persona"].'</td>
		<td class="fw-bold">Puesto</td>
		<td>: '.$arrayResultado["pue_descripcion"].'</td>
	</tr>
	<tr>
		<td class="fw-bold">Nombres y Apellidos</td>
		<td>: '.$nombresPersona.'</td>
		<td class="fw-bold">Departamento</td>
		<td>: '.$arrayResultado["dep_descripcion"].'</td>
	</tr>
	<tr>
		<td class="fw-bold">Fecha registro</td>
		<td>: '.$arrayResultado["perm_fechareg"].'</td>
		<td class="fw-bold"># Permiso</td>
		<td>: '.$arrayResultado["id_permiso"].'</td>
	</tr>
	</table>
	<div class="ms-2 pt-2">
	<b>MOTIVO DE PERMISO</b>
	<p>'.$arrayResultado["mot_descripcion"].'</p>
	<b>OBSERVACIÓN</b>
	<p>'.$arrayResultado["perm_observaciones"].'</p>
	<b>FECHAS</b>
	<div class="mt-2">
	<span >Inicio permiso</span><span>: '.$arrayResultado["perm_inicio"].'</span><br>
	<span>Inicio permiso</span><span>: '.$arrayResultado["perm_fin"].'</span>
	</div>
	</div>
	<table class="mt-5">
	<tr>
		<td class="w-33 text-center">
			____________________________________
			<p class="pt-3">'.$nombresPersona.'</p>
		</td>
		<td class="w-33 text-center">
			____________________________________
			<p class="pt-3">VB. RECURSOS HUMANOS</p>
		</td>
		<td class="w-33 text-center">
			____________________________________
			<p class="pt-3">JEFE INMEDIATO</p>
		</td>
	</tr>
	</table>
	</body>';
	$footer = '<footer>
		<span class="texto-secundario"> Responsable: ' . $_SESSION["nombre_trabajador"] . '</span>
		</footer>';
	return [$plantilla, $header, $footer, 'documentos'];
}
