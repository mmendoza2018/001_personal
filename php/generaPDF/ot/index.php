<?php
require_once("../../../assets/plugins/mpdf/vendor/autoload.php");
require("plantilla.php");
$fecha = date("d:m:y");

$idOt = @$_GET["id"];
$plantilla = plantilla($idOt);
$mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'stretch','setAutoBottomMargin'=>'stretch', 'margin_footer' => 2]);
$css = file_get_contents("../style.css");
$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($plantilla[0]);
$mpdf->SetHTMLFooter($plantilla[1], 'O');
$mpdf->AddPageByArray(array(
    'orientation' => 'L',
));
$mpdf->WriteHTML($plantilla[2]);
$mpdf->Output("Orden de trabajo.pdf", "I");
