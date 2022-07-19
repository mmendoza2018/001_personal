<?php
require_once("../../../assets/plugins/mpdf/vendor/autoload.php");
require_once("plantilla.php");
$fecha = date("d:m:y");

$idEquipo = @$_POST["idEquipo"];

$mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'stretch']);
$css = file_get_contents("../styleEquipo.css");
$mpdf->SetHTMLHeader('<div style="margin-bottom:20px; color:red;">
    <img src="../images/gyt.png" alt="" width="150px">
    </div>', "O", false);
$mpdf->SetHTMLFooter('<footer>
<p style="text-align: right;"><b>'.$_SESSION["nombre_trabajador"].' </b>, '.$fecha.' </p></footer>', "O");
$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($plantilla);
$mpdf->Output("Ficha Equipo.pdf", "I");
unset($_SESSION["idEquipo"]);
