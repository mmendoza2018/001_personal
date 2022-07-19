<?php 
require_once("../../conexion.php");
$idAct = $_POST["idAct"];
$descripcion = $_POST["descripcion"];
$duracion = $_POST["duracion"];
$estado = $_POST["estado"];
$idtrabajador = $_POST["idtrabajador"];

$resTRealizados = mysqli_query($conexion,"UPDATE trabajos_realizados SET   TRRE_descripcion = '$descripcion',
                                                                           TRRE_duracion = '$duracion',
                                                                           TRAB_id01 = '$idtrabajador',
                                                                           TRRE_estado = '$estado' WHERE TRRE_id='$idAct'");
echo ($resTRealizados) ? "true" : "false"; 
$conexion->close();
