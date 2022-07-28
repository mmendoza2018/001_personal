<?php require_once('modales.php') ?>

<div><h5>PUESTOS LABORALES</h5></div>
<div class="container-fluid bg-white my-2 py-3">
    <div class="row g-5">
        <div class="col-sm-4">
            <form id="formPuestoLaboral" onsubmit="event.preventDefault();">
                <label class="mb-1">Descripción</label>
                <input type="text" class="form-control form-control-sm mb-2" data-validate name="descripcionPuesto">
                 <label class="mb-1">Detalle de Puesto</label>
                <input type="textarea" class="form-control form-control-sm mb-2" data-validate name="detallePuesto">
                <button class="btn btn-blue-gyt btn-sm float-end" onclick="agregarPuestoLaboral()" type="button">Agregar</button>
            </form>
        </div>
        <div class="col-sm-8">
            <div id="contenedorTablaPuestoLaboral"></div>
        </div>
    </div>
</div>

<script>
  cargarContenido('php/mantenimientos/puestoLaboral/tabla.php','contenedorTablaPuestoLaboral');
</script>