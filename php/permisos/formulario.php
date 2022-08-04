<?php
require_once('modales.php');
require_once('../conexion.php');

$resPersonal = mysqli_query($conexion, " SELECT * FROM gyt_personas");
$resMotivos = mysqli_query($conexion, " SELECT * FROM gyt_motivos WHERE mot_estado='ACTIVO'");
?>
<div>
  <h5>AGREGAR PERMISO</h5>
</div>
<div class="container-fluid bg-white my-2 py-3">
  <div class="col-md-4 mx-auto">
    <form id="formPermisosAdd" onsubmit="event.preventDefault();">
      <label class="mb-1">Documento Persona</label>
      <select name="idPersona" data-validate class="form-control select2">
        <option></option>
        <?php foreach ($resPersonal as $x) : ?>
          <option value="<?php echo $x["id_persona"] ?>">
            <?php echo $x["per_nombres"] . " " . $x["per_apellidos"] ." | ".$x["id_persona"] ?>
          </option>
        <?php endforeach; ?>
      </select>
      <label class="mb-1">Motivo de Salida</label>
      <select name="motivo" data-validate class="form-control select2">
        <option></option>
        <?php foreach ($resMotivos as $x) : ?>
          <option value="<?php echo $x["id_motivo"] ?>"><?php echo $x["mot_descripcion"] ?></option>
        <?php endforeach; ?>
      </select>
      <label class="mb-1">Inicio Permiso</label>
      <input type="date" class="form-control form-control-sm mb-2" data-validate name="fInicio">

      <label class="mb-1">Fin Permiso</label>
      <input type="date" class="form-control form-control-sm mb-2" data-validate name="fTermino">
      <label class="mb-1">Total Dias</label>
      <input type="text" class="form-control form-control-sm mb-2" placeholder="1 - Dias" data-validate name="dias">
      <label class="mb-1">Observaciones</label>
      <input type="text" class="form-control form-control-sm mb-2" name="observacion">
      <div class="text-end">
        <button class="btn btn-blue-gyt btn-sm" onclick="agregarPermisos()" type="button">Agregar</button>
      </div>
    </form>
  </div>
</div>
<script>
  $(document).ready(function() {
    $(".select2").select2({
      placeholder: "Seleccione una opcion",
    });
  });
  $(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
  });
</script>