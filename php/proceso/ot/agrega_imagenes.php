<?php
session_start();
require_once("../../randon_string.php");
require_once("../../conexion.php");

$idOt = $_POST["idOt"];
$imagen = $_POST["imagen"];
$usuario = $_SESSION["nombre_trabajador"];

$contenidoImg = str_replace('data:image/png;base64,', '', $imagen);

$contenidoImg = str_replace(' ', '+', $contenidoImg);

$contenidoImg = base64_decode($contenidoImg);

$file = rand() . '.jpg';

//echo $success = file_put_contents($file, $data);

    $codigoRandon = generateRandomString();
    $rutaImg = "../../../archivos/imagenOts/";
    $nombreArchivo = "NumOT-".$idOt."-".$codigoRandon;
    $guardaImg =  file_put_contents($rutaImg.$nombreArchivo, $contenidoImg);
    $consulta = "INSERT INTO imagen_ots (ORTR_id01,IMOT_identificador,IMOT_descripcion,IMOT_usuario) 
                 VALUES ('$idOt','$nombreArchivo','-','$usuario')";
    if(!mysqli_query($conexion,$consulta)){
        echo "false";
        die();
}
echo "true";
$conexion->close();
?>