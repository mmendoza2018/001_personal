<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '../../../assets/plugins/phpMailer/Exception.php';
require '../../../assets/plugins/phpMailer/PHPMailer.php';
require '../../../assets/plugins/phpMailer/SMTP.php';
require_once("../../../assets/plugins/mpdf/vendor/autoload.php"); 
require("plantilla.php");
error_reporting(0);
$pdfs= ["plantilla"=>[],"codigo"=>[]];
$idsEquipos= $_POST["idsEquipos"];
/* $idsEquipos="1|2"; */
$array = explode("|",$idsEquipos);
	 for ($i=0; $i < count($array); $i++) { 

        $css=file_get_contents("style.css");
        $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'stretch']);
        $mpdf->SetHTMLHeader('<div>
    <img src="../images/gyt.png" alt="" width="150px">
    </div>', "O", true);
        $mpdf->WriteHTML($css ,\Mpdf\HTMLParserMode::HEADER_CSS);
        $plantillaHecha=plantilla($array[$i]);
        $mpdf->WriteHTML($plantillaHecha[0] ,\Mpdf\HTMLParserMode::HTML_BODY);
        array_push($pdfs["plantilla"],$mpdf->Output("","S"));
        array_push($pdfs["codigo"],$plantillaHecha[1]);
	 }

 envio_pdf($pdfs);

function envio_pdf($archivos){


$mail = new PHPMailer(true);

try {
$correo=$_POST["correo"];
$asunto=$_POST["asunto"];
    //Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'sistema.gytperu.com';                    // host de quien va brindar el servicio
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'administradorgyt@sistema.gytperu.com';                     // SMTP username
    $mail->Password   = 'administradorGYT';                               // SMTP password
    $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('administradorgyt@sistema.gytperu.com', 'GYT empresarial');
    $mail->addAddress($correo);     // Add a recipient

    // Attachments
    for ($i=0; $i < count($archivos["plantilla"]); $i++) { 
        $mail->addStringAttachment($archivos["plantilla"][$i],$archivos["codigo"][$i].".pdf");
    }
    
    //envio de archivos pdf en local
   /*  'carpeta_del_fichero_pdf', $name = 'NombreDelFichero',  $encoding = 'base64', $type = 'application/pdf' */
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'GYT Archivos Adjuntos';
    $mail->Body    = $asunto.'</br>';

    $mail->send();
    echo 'true';
} catch (Exception $e) {
    echo "false";
}
}
?>