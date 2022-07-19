<?php
$idEquipo=$_POST["idEquipo"];
include_once("../../conexion.php");
$consulta =  "SELECT * FROM imagen_equipos ie INNER JOIN tipo_img_equipos tie ON ie.TIIM_id01=tie.TIIM_id 
                                                    INNER JOIN equipos e ON ie.EQU_id01=e.EQU_id
                                                    INNER JOIN familias fa ON fa.FAM_id=e.FAM_id01 WHERE (EQU_id01='$idEquipo' OR EQU_union='$idEquipo')  AND IMEQ_estado = 1";
$resConsulta=mysqli_query($conexion,$consulta)
?>
<div class="table-responsive">
    <table id="tabla_lista_imagenes" class="table table-striped">
        <thead >
            <tr>
                <th>codigo Equipo</th>
                <th>Tipo documento</th>
                <th>Descripción</th>
                <th>F. ingreso</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
             if ($resConsulta->num_rows==0) echo '<div class="alert alert-warning" role="alert">
            no hay imagenes para este equipo </div>';
            $cont=0;
            foreach ($resConsulta as $x) : 
            $datos = $x["IMEQ_id"]."|".$x["EQU_codigo"]."|".$x["IMEQ_descripcion"];?> 
                <tr>
                    <td><?php echo $x["EQU_codigo"] ?></td>
                    <td><?php echo $x["TIIM_descripcion"] ?></td>
                    <td><?php echo $x["IMEQ_descripcion"] ?></td>
                    <td><?php echo $x["IMEQ_ingreso"] ?></td>
                    <td class="text-center">
                    <a href="#"  data-bs-toggle="modal" data-bs-target="#modalImgEquipo" onclick="verImgEquipo('<?php echo $x['IMEQ_id'] ?>')"><i class="fas fa-image text-dark"></i></a>
                    <a href="#"  data-bs-toggle="modal" data-bs-target="#modalImgEquipoAct" onclick="llenarDatosImgEquipo('<?php echo $datos ?>')"><i class="fas fa-edit text-dark"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>