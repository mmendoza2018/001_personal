<?php
include_once("../../conexion.php");
//include_once("../../calculo_tiempo.php");
$whereConsulta = "";
$idPersona = @$_POST["idPersona"];

$consulta = "SELECT * FROM gyt_documentos d
INNER JOIN gyt_tipodocumento td ON d.id_tipodocumento = td.id_tipodocumento
INNER JOIN gyt_personas p ON d.id_persona = p.id_persona WHERE d.id_persona=$idPersona";

$resListadoDoc = mysqli_query($conexion, $consulta);
?>
<div class="container-fluid bg-white my-2 py-3">
  <div class="table-responsive">
    <table id="tablaListaDoc" class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Proyecto</th>
          <th>Nombres</th>
          <th>FechaInicio</th>
          <th>FechaFin</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <?php
      foreach ($resListadoDoc as $x) { ?>
        <tr>
          <td><?php echo $x["id_documento"]; ?></td>
          <td><?php echo $x["id_persona"]; ?></td>
          <td><?php echo $x["per_nombres"].' '. $x["per_apellidos"] ?></td>
          <td><?php echo $x["tdoc_descripcion"]; ?></td>
          <td><?php echo $x["doc_fecha1"]; ?></td>
          <td><?php echo $x["doc_fecha2"]; ?></td>
          <td class="text-center">
            <a class="link_edit text-decoration-none" href="frm_edocumentos.php?id=<?php echo $x["id_documento"]; ?>">
              <i class="fa fa-edit"></i>
            </a>
            |
            <a class="link_edit text-decoration-none" href="frm-detdocumentos.php?id=<?php echo $x["id_persona"]; ?>">
              <i class="fa fa-print"></i>
            </a>
          </td>
        </tr>
      <?php } ?>
    </table>
  </div>
</div>
<script>
  var table = $('#tablaListaDoc').DataTable({
    "info": false,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    }
  });
</script>