<?php
session_start();
include "conexion.php";
if ($_POST['user'] !== "" && $_POST['password'] !== "") {
    $user = $_POST['user'];
    $password = $_POST['password'];
    $PasswordSql= "";
    $nombreSql = "";

    $sentencia = $conexion->prepare("SELECT id_persona,per_contrasenia,per_nombres FROM gyt_personas WHERE id_persona = ? ");
    $sentencia->bind_param("s", $user);
    $sentencia->execute();
    $res = $sentencia->get_result();
    $filas = $res->num_rows;
    foreach ($res as $k) {
        $PasswordSql = $k["per_contrasenia"];
        $nombreSql = $k["per_nombres"];
        $idUser = $k["id_persona"];
    }
    if ($filas !== 0) {
        if (password_verify($password, $PasswordSql)) {
            //verificar si tiene rol en el sistema 
            $con_rol="SELECT * FROM roles_persona rp RIGHT JOIN roles r ON rp.ROL_id=r.ROL_id WHERE id_persona='$idUser'";
            $res_con_rol=mysqli_query($conexion,$con_rol);
            if($res_con_rol->num_rows<=0){
                echo  json_encode(0);
                return;
            }
            foreach ($res_con_rol as $x) {
                $descripcionRol = $x["ROL_descripcion"];
            }
            $_SESSION["nombre_trabajador"] = $nombreSql; // session con el nombre
            $_SESSION["rol_trabajador"] = $descripcionRol; // session con el rol
            $_SESSION["primerIngreso"] = true; // session con el rol
            echo json_encode(1);
        } else {
            echo json_encode(3); // la contra esta mal
        }
    } else {
        echo  json_encode(2); //  user esta mal
    }
} else {
    echo json_encode(4); //ausencia de datos
}
