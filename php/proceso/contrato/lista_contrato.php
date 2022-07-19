<?php
    $idProyecto=$_POST["idProyecto"];
    include_once("../../conexion.php");
    include_once("../../calculo_tiempo.php");
    $consulta =  "SELECT * FROM contratos co INNER JOIN clientes cl ON co.CLIE_id01=cl.CLIE_id 
                                            INNER JOIN proyectos p ON co.PROY_id01=p.PROY_id  WHERE PROY_id01='$idProyecto'";
    $listadoContratos = mysqli_query($conexion,$consulta);
?>
<div class="container-fluid bg-white my-2 py-3">
<div class="table-responsive">
    <table id="tabla_listaCon" class="table table-striped">
        <thead >
            <tr>
                <th>Contrato</th>
                <th>Cliente</th>
                <th>F. inicio</th>
                <th>F. termino</th>
                <th>Equipos</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $classCircle="";
            foreach ($listadoContratos as $x) : 
             $datosContrato = $x["CONTR_id"]."|".$x["CONTR_descripcion"]."|".$x["CONTR_numero"]."|".$x["CLIE_id01"]."|".$x["PROY_id01"]."|".$x["CONTR_f_inicio"]."|".$x["CONTR_f_fin"]."|".$x["CONTR_estado"];
             /* $classCircle = calculoFechaDocumentos(new DateTime($x["DOEQ_vencimiento"]),$regular,$malo) */?> 
                <tr>
                    <td><?php echo $x["CONTR_descripcion"] ?></td>
                    <td><?php echo $x["CLIE_razon_social"] ?></td>
                    <td><?php echo $x["CONTR_f_inicio"] ?></td>
                    <td><?php echo $x["CONTR_f_fin"] ?></td>
                    <td>
                        <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#modalAgregaEquipoCon" onclick="llenarDatosEquipoContrato('<?php echo $datosContrato ?>')">
                        <i class="fas fa-plus-circle me-2"></i></a>
                        <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#modalListadoEquiposCon" onclick="verListaEquipos('<?php echo $x['CONTR_id'] ?>')">
                        <i class="fas fa-list-ul"></i>
                    </a>
                    </td>
                    <td><?php echo $x["CONTR_estado"] ?></td>
                    <td class="text-center">
                    <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#modalActContrato" onclick="llenarDatosContrato('<?php echo $datosContrato ?>')"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#tabla_listaCon').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>

