<?php
session_start();
if ($_SESSION["nombre_trabajador"]) { ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <?php include "componentes/header.html" ?>
        <title>menu principal</title>
    </head>

    <body class="fondo-global">
        <div id="PW_general" class="page-wrapper toggled ice-theme">
            <?php include_once("componentes/sidebar.php") ?>
            <!-- page-content  -->
            <main class="container-fluid page-content fondo-global pt-0">
                <div id="overlay" class="overlay"></div>
                <div class="loader-page"></div>
                <nav class="navbar navbar-light bg-white shadow d-flex justify-content-between d-md-none py-0">
                    <a href="php/cerrar_sesion.php" class="text-danger ms-2"><i class="fas fa-reply-all"></i></a>
                    <a class="navbar-brand mx-auto" href="#">
                        <img src="assets/img/gyt.png" width="100" alt="gyt"></a>
                    <a id="btn-scan-qr" onclick="encenderCamara()" data-bs-toggle="modal" data-bs-target="#modalCamaraQR"  class="btn float-end" href="#">
                        <i class="fas fa-qrcode fa-lg"></i>
                    </a>
                    <a id="toggle-sidebar" class="btn float-end" href="#">
                        <i class="fas fa-bars"></i>
                    </a>
                    <!-- <a id="toggle-sidebar" class="btn" href="#"> <i class="fas fa-undo-alt"></i> </a> -->
                </nav>
                <!-- contenido principal -->
                <div class="container-fluid px-0 pt-2" id="contenidoGeneral"> </div>
            </main>
            <div id="modales"></div>
            <!-- page-content" -->
        </div>
        <?php include "componentes/footer.html" ?>
    </body>

    </html>
<?php } else header("Location: index.html"); ?>