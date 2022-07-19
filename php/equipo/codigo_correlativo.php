<?php 
require_once("../conexion.php");
$familia  = $_POST['familia'];
$verCorrelativo = mysqli_query($conexion,"SELECT FAM_id,FAM_cont_equipos FROM familias WHERE FAM_id='$familia'");
if ($verCorrelativo->num_rows<=0) {
    echo 1;
}else{
    foreach ($verCorrelativo as $x) { echo $x["FAM_cont_equipos"]+1;}
}
?>