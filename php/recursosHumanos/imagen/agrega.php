<?php
session_start();
require_once("../../randon_string.php");
require_once("../../conexion.php");
require_once("../../general/ultimoId.php");

$idEquipo = @$_POST["equipo"];
$descripcion = @$_POST["descripcion"];
$tipoDocumento = @$_POST["tipoDocumento"];
$doc = $_FILES["archivo"];
$usuario = $_SESSION["nombre_trabajador"];
$id = ultimoId($conexion,"imagen_equipos","IMEQ_id");
$formato="";
($doc["type"]=="image/png") ? $formato= "png" : $formato = "jpg";
$nombreArchivo = "";
$traerId= mysqli_query($conexion,"SELECT EQU_id,EQU_codigo FROM equipos WHERE EQU_codigo='$idEquipo'");
foreach ($traerId as $x) { $idEquipo=$x["EQU_id"]; }

$clave_aleatoria = generateRandomString();  // OR: generateRandomString(24)
$contenidoArchivo = file_get_contents($doc['tmp_name']); //contenido del archivo
//ruta de almacenamiento
$resTipoImagen = mysqli_query($conexion, "SELECT * FROM tipo_img_equipos WHERE TIIM_id='$tipoDocumento'");
foreach ($resTipoImagen as $x) { $nombreArchivo = $x["TIIM_descripcion"]; }

$rutaDoc = "../../../archivos/equipos/imagenes/" . $nombreArchivo; // ruta del archivo
$nombreIdentificador = $idEquipo . "-" . $clave_aleatoria . ".".$formato;
if (file_exists($rutaDoc)) {
    $guardaDoc =  file_put_contents($rutaDoc . "/" . $nombreIdentificador , $contenidoArchivo);
    if($guardaDoc){
        $consulta="INSERT INTO imagen_equipos   (EQU_id01,	
                                                    TIIM_id01,	
                                                    IMEQ_descripcion,	
                                                    IMEQ_identificador) 
                                            VALUES ('$idEquipo',
                                                    '$tipoDocumento',
                                                    '$descripcion',
                                                    '$nombreIdentificador');";
    $consulta.="CALL agregar_historial('imagen_equipos','$id','agrego','$usuario')";
    $resConAgregaImg = mysqli_multi_query($conexion,$consulta);
    echo ($resConAgregaImg) ? "true" : "false";
    } else{
        echo "false";// ocurrio un error al subir el archivo
    }
} else {
    mkdir("../../../archivos/equipos/imagenes/" . $nombreArchivo, 0777, true);
    $guardaDoc02 = file_put_contents($rutaDoc . "/" . $nombreIdentificador , $contenidoArchivo);
    if($guardaDoc02){
        $consulta="INSERT INTO imagen_equipos   (EQU_id01,	
                                                    TIIM_id01,	
                                                    IMEQ_descripcion,	
                                                    IMEQ_identificador) 
                                            VALUES ('$idEquipo',
                                                    '$tipoDocumento',
                                                    '$descripcion',
                                                    '$nombreIdentificador');";
    $consulta.="CALL agregar_historial('imagen_equipos','$id','agrego','$usuario')";
    $resConAgregaImg = mysqli_multi_query($conexion,$consulta);
    echo ($resConAgregaImg) ? "true" : "false";
    } else{
        echo "false";// ocurrio un error al subir el archivo
    }
}

