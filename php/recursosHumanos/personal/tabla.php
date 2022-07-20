<?php
require_once "../../conexion.php";
require_once "modales.php";

?>

<div>
  <h5>PERSONAL</h5>
</div>
<div class="container-fluid bg-white my-2 py-3">
  <!-- /.box-header -->
  <div class="table-responsive">
    <table id="tablaListaPersonal" class="table table-striped">
      <thead>
        <tr>
          <th># Documento</th>
          <th>Nombres y Apellidos</th>
          <th>Telefono</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <?php
      $resPersonal = mysqli_query($conexion, " SELECT * FROM gyt_personas");
        foreach ($resPersonal as $x) { ?>
          <tr>
            <td><?php echo $x["id_persona"]; ?></td>
            <td><?php echo $x["per_nombres"]. ' '.$x["per_apellidos"];?> </td>
            <td><?php echo $x["per_telefono"]; ?></td>
            <td>
              <a class="text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#modalActPersonal"
              onclick="llenarDatosPersonalAct('<?php echo $x['id_persona']; ?>')">
                <i class="fa fa-edit"></i>
              </a>
              |
              <a class="text-decoration-none" href="frm-detfamilia.php?id=<?php echo $x["id_persona"]; ?>">
                <i class="fa fa-child"></i>
              </a>
            </td>
          </tr>
      <?php } ?>
    </table>
  </div>
  <!-- /.box-body -->
</div>
<script>
  $(document).ready(function() {
    $('#tablaListaPersonal').DataTable({
      "info": false,
      "ordering":false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });
  });
  setTimeout(() => {
    $('#smartwizardAct').smartWizard({
      lang: { // Language variables for button
        next: 'Siguiente',
        previous: 'Atras'
      },
      toolbar: {
        extraHtml: '<button class="btn btn-blue-gyt" type="submit" onclick="actualizarPersonal()">Actualizar</button>' // Extra html to show on toolbar
      },
    });
  }, 500);
</script>