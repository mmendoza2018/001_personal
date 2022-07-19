<?php require_once('modales.php') ?>

<div><h5>TIPO DOCUMENTO</h5></div>
<div class="container-fluid bg-white my-2 py-3">
    <div class="row g-5">
        <div class="col-sm-4">
            <form id="formCargoTrabajador">
                <label class="mb-1">Descripción</label>
                <input type="text" class="form-control form-control-sm mb-2" data-validate name="descripcion">
                <button class="btn btn-blue-gyt btn-sm float-end" onclick="agregarCargoTrabajador()" type="button">Agregar</button>
            </form>
        </div>
        <div class="col-sm-8">
            <div id="contenedorTablaTipoDoc"></div>
        </div>
    </div>
</div>

<script>
  cargarContenido('php/mantenimientos/equipo/tipoDocumento/tabla.php','contenedorTablaTipoDoc');
</script>