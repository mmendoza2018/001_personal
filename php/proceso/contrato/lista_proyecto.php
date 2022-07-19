<?php
require_once("../../conexion.php");
$conProyectos = mysqli_query($conexion, "SELECT * FROM proyectos WHERE PROY_estado=1");
?>
<div><h5> LISTADO PROYECTOS</h5></div>
<div class="container-fluid bg-white my-2 py-3">
<div class="row d-flex justify-content-center">
    <div class="col-sm-10 col-md-10 col-lg-7">
        <button type="button" class="btn btn-sm bg-blue-gyt text-light mb-3" data-bs-toggle="modal" data-bs-target="#modalHistorialEquipo"  onclick="llenaHistorialEC()">Historial de equipos</button>
        <div class="container-fluid ">
            <div class="table-responsive">
                <table id="tabla_lista_proyectos" class="table table-striped">
                    <thead>
                        <tr>
                            <th># Proyecto</th>
                            <th>Descripción</th>
                            <th>F. inicio</th>
                            <th>Contratos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($conProyectos as $x) :
                            $datosProyecto = $x["PROY_id"] . "|" . $x["PROY_descripcion"] . "|" . $x["PROY_estado"] ?>
                            <tr>
                                <td><?php echo $x["PROY_id"] ?></td>
                                <td><?php echo $x["PROY_descripcion"] ?></td>
                                <td><?php echo $x["PROY_f_inicio"] ?></td>
                                <td>
                                    <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#modalAgregaContrato" onclick="llenarDatosDescripcion('<?php echo $datosProyecto ?>','descProyectoAdd','idProyectoAdd')">
                                    <i class="fas fa-plus-circle me-2"></i></a>
                                    <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#modalListadoContrato" onclick="verListaContratos('<?php echo $x['PROY_id'] ?>')"><i class="fas fa-list-ul"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
</div>
<!-- page-content" -->
<script>
    $(document).ready(function() {
        $('#tabla_lista_proyectos').DataTable({
            "info": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });
</script>