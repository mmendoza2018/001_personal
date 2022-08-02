<?php
include_once("../conexion.php");
$resPermisos = mysqli_query($conexion, "SELECT * FROM gyt_permisos pm 
                                  INNER JOIN gyt_personas ps ON pm.id_persona = ps.id_persona");
?>
<div>
  <h5>LISTA PERMISOS</h5>
</div>
<div class="container-fluid bg-white my-2 py-3">
  <div class="table-responsive">
    <table id="tablaPermisos" class="table table-striped">
      <thead>
        <tr>
          <th># Permiso</th>
          <th>Dni</th>
          <th>Nombres y Apellidos</th>
          <th>Motivo</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <?php
      foreach ($resPermisos as $x) {
        $idPermiso =  $x["id_permiso"]; ?>
        <tr>
          <td><?php echo $idPermiso ?></td>
          <td><?php echo $x["id_persona"]; ?></td>
          <td><?php echo $x["per_nombres"];
              echo ' '; ?><?php echo $x["per_apellidos"]; ?></td>
          <td><?php echo $x["perm_observaciones"]; ?></td>
          <td><?php echo $x["perm_inicio"]; ?></td>
          <td class="text-center">
            <a class="link_delete" 
            onclick="generapdf('<?php echo $idPermiso  ?>', 'php/generaPDF/permisos/index.php', 'Permiso')">
              <i class="fas fa-file-pdf"></i>
          </td>
        </tr>
      <?php } ?>
    </table>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#tablaPermisos').DataTable({
      "info": false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });
  });
</script>