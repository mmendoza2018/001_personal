<?php
        $conexion = new mysqli("localhost:3310", "root","","gytperu_personal");
        if ($conexion->connect_errno) {
            echo "Fallo al conectar a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
        }
        mysqli_set_charset($conexion, "utf8");
?>