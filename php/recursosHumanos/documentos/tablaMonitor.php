<?php
require_once "../../conexion.php";
require_once "modales.php";

$fechaActual = date("Y-m-d");
//sumo 1 día
$fechaEnTreintaDias =  date("Y-m-d", strtotime($fechaActual . "+ 29 days"));
/* $consulta = "SELECT * FROM gyt_documentos d
          INNER JOIN gyt_personas p ON d.id_persona = p.id_persona
          INNER JOIN gyt_tipodocumento td ON d.id_tipodocumento = td.id_tipodocumento
          WHERE doc_fecha2 < '$fechaEnTreintaDias'"; */
          $consulta = "SELECT * FROM gyt_documentos d
          INNER JOIN gyt_personas p ON d.id_persona = p.id_persona
          INNER JOIN gyt_tipodocumento td ON d.id_tipodocumento = td.id_tipodocumento
          WHERE id_documento > 4200";
$resDocumentos = mysqli_query($conexion, $consulta);
?>
<div>
  <h5>DOCUMENTOS DEL PERSONAL</h5>
</div>
<div class="container-fluid bg-white my-2 py-3">
  <div class="table-responsive">
    <table id="tablaMonitorAlertas" class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Doc. Personal</th>
          <th>Nombres y Apellidos</th>
          <th>Documento</th>
          <th>Fecha Vencimiento</th>
          <th>Alerta del Sistema</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($resDocumentos as $x) {
          $idDocumento = $x["id_documento"];
          $classClircle = ($fechaActual >= $x["doc_fecha2"]) ? "text-danger" : "text-warning";
          $data = $x["id_documento"] . "|" . $x["id_persona"] . "|" . $x["id_tipodocumento"] . "|" . $x["doc_fecha1"] . "|" . $x["doc_fecha2"] . "|" . $x["doc_numdoc"] . "|" . $x["doc_descripcion"] . "|" . $x["doc_empresa"] . "|" . $x["doc_observa"] . "|" . $x["id_persona"];
        ?>
          <tr>
            <td><?php echo $idDocumento; ?></td>
            <td><?php echo $x["id_persona"]; ?></td>
            <td><?php echo $x["per_nombres"] . ' ' . $x["per_apellidos"]; ?></td>
            <td><?php echo $x["tdoc_descripcion"]; ?></td>
            <td>
              <i class="fas fa-circle <?php echo $classClircle ?>"></i>
              <?php echo $x["doc_fecha2"]; ?>
            </td>
            <td class="text-center">
              <a class="link_edit text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalActDocPersonal" onclick="llenarDocumentosAct('<?php echo $data ?>',true)" href="#">
                <i class="fa fa-edit"></i>
              </a>
              |
              <a class="link_edit text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalVerDocPersonal" onclick="verDocumentoPersonal('<?php echo $idDocumento ?>')" href="#">
                <i class="fa fa-print"></i>
              </a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#tablaMonitorAlertas').DataTable({
      "info": false,
      "ordering": false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });
  });
</script>