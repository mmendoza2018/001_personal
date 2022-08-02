<?php 
session_start();
require_once("../conexion.php");
date_default_timezone_set('America/Lima');
$usuario = $_SESSION["nombre_trabajador"];
$idPersona = @$_POST["idPersona"];
$motivo = @$_POST["motivo"];
$fInicio = @$_POST["fInicio"];
$fTermino = @$_POST["fTermino"];
$dias = @$_POST["dias"];
$observacion = @$_POST["observacion"];
$fechaActual =  date('m/d/Y g:ia'); 

$consulta = "INSERT INTO gyt_permisos
                        (
                          id_persona,	
                          id_motivo,
                          perm_observaciones,	
                          perm_inicio,	
                          perm_fin,	
                          perm_total,	
                          perm_loguser,
                          perm_fechareg
                        ) VALUES 
                        (
                          '$idPersona',
                          '$motivo',
                          '$observacion',
                          '$fInicio',
                          '$fTermino',
                          '$dias',
                          '$usuario',
                          '$fechaActual'
                        )";
$addPermiso = mysqli_query($conexion,$consulta);
echo ($addPermiso) ? json_encode(true) : json_encode(false) ; 
$conexion->close();
?>