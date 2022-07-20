<?php
require_once("../conexion.php");
$conFamilia = mysqli_query($conexion, "SELECT * FROM familias WHERE FAM_estado=1");
?>
<div><h5> LISTADO FAMILIAS</h5></div>
<div class="container-fluid bg-white my-2 py-3">
<div class="row d-flex justify-content-center">
    <div class="col-sm-10 col-md-10 col-lg-7">
        <div class="container-fluid ">
            <div class="table-responsive">
                <table id="tabla_lista_familia" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Codigo</th>
                            <th>Descripción</th>
                            <th>Equipos registrados</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($conFamilia as $x) :
                            $datosFamilia = $x["FAM_id"] . "|" . $x["FAM_codigo"] . "|" . $x["FAM_descripcion"] ?>
                            <tr>
                                <td><?php echo $x["FAM_id"] ?></td>
                                <td><?php echo $x["FAM_codigo"] ?></td>
                                <td><?php echo $x["FAM_descripcion"] ?></td>
                                <td class="text-center"><?php echo $x["FAM_cont_equipos"] ?></td>
                                <td><a href="#/gyt/vistas/equipo/plantillas/equipo/agrega" data-bs-toggle="modal" data-bs-target="#modalAgregaEqu" onclick="llenarDatosAddEquipo('<?php echo $datosFamilia ?>')"><span class="badge rounded-pill bg-primary pb-1">Agregar Equipo</span></td>
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
        $('#tabla_lista_familia').DataTable({
            "info": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });
</script>