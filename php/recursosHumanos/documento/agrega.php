<?php
session_start();
require_once("../../randon_string.php");
require_once("../../conexion.php");
require_once("../../general/ultimoId.php");
$usuario = $_SESSION["nombre_trabajador"];
$idEquipo = @$_POST["equipo"];
$descripcion = @$_POST["descripcion"];
(isset($_POST["fechaVencimiento"])) ? $fechaVencimiento = @$_POST["fechaVencimiento"] : $fechaVencimiento="0000-00-00";
$tipoDocumento = @$_POST["tipoDocumento"];
$doc = $_FILES["archivo"];
$nombreArchivo = "";
$id = ultimoId($conexion,"documento_equipos","DOEQ_id");
$traerId= mysqli_query($conexion,"SELECT EQU_id,EQU_codigo FROM equipos WHERE EQU_codigo='$idEquipo'");
foreach ($traerId as $x) { $idEquipo=$x["EQU_id"]; }

$clave_aleatoria = generateRandomString();  // OR: generateRandomString(24)
$contenidoArchivo = file_get_contents($doc['tmp_name']); //contenido del archivo
//ruta de almacenamiento
$res_con_tido = mysqli_query($conexion, "SELECT * FROM tipo_doc_equipos WHERE TIDO_id='$tipoDocumento'");
foreach ($res_con_tido as $x) { $nombreArchivo = $x["TIDO_descripcion"]; }

$ruta_doc = "../../../archivos/equipos/documentos/" . $nombreArchivo; // ruta del archivo
$nombre_identificador = $idEquipo . "-" . $clave_aleatoria . ".pdf";
if (file_exists($ruta_doc)) {
    $guarda_doc =  file_put_contents($ruta_doc . "/" . $nombre_identificador , $contenidoArchivo);
    if($guarda_doc){
        $consulta="INSERT INTO documento_equipos (EQU_id01,	
                                                            TIDO_id01,	
                                                            DOEQ_descripcion,	
                                                            DOEQ_identificador,	
                                                            DOEQ_vencimiento) 
                                                    VALUES ('$idEquipo',
                                                            '$tipoDocumento',
                                                            '$descripcion',
                                                            '$nombre_identificador',
                                                            '$fechaVencimiento');";
    $consulta.="CALL agregar_historial('documento_equipos','$id','agrego','$usuario')";
    $resConAgregaDoc = mysqli_multi_query($conexion,$consulta);

    echo ($resConAgregaDoc) ? "true" : "false";
    } else{
        echo "false";// ocurrio un error al subir el archivo
    }
} else {
    mkdir("../../../archivos/equipos/documentos/" . $nombreArchivo, 0777, true);
    $guardo_doc02 = file_put_contents($ruta_doc . "/" . $nombre_identificador , $contenidoArchivo);
    if($guardo_doc02){
        $consulta="INSERT INTO documento_equipos (EQU_id01,	
                                                            TIDO_id01,	
                                                            DOEQ_descripcion,	
                                                            DOEQ_identificador,	
                                                            DOEQ_vencimiento) 
                                                    VALUES ('$idEquipo',
                                                            '$tipoDocumento',
                                                            '$descripcion',
                                                            '$nombre_identificador',
                                                            '$fechaVencimiento');";
    $consulta.="CALL agregar_historial('documento_equipos','$id','agrego','$usuario')";
    $resConAgregaDoc = mysqli_multi_query($conexion,$consulta);
    echo ($resConAgregaDoc) ? "true" : "false";
    } else{
        echo "false";// ocurrio un error al subir el archivo
    }

}

