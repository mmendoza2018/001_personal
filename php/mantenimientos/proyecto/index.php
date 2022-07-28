<?php require_once('modales.php') ?>

<div><h5>PROYECTOS</h5></div>
<div class="container-fluid bg-white my-2 py-3">
    <div class="row g-5">
        <div class="col-sm-4">
            <form id="formProyecto" onsubmit="event.preventDefault();">
                <label class="mb-1">Descripción</label>
                <input type="text" class="form-control form-control-sm mb-2" data-validate name="descripcionPro">
                <button class="btn btn-blue-gyt btn-sm float-end" onclick="agregarProyecto()" type="button">Agregar</button>
            </form>
        </div>
        <div class="col-sm-8">
            <div id="contenedorTablaPuestoLaboral"></div>
        </div>
    </div>
</div>

<script>
  cargarContenido('php/mantenimientos/proyecto/tabla.php','contenedorTablaPuestoLaboral');
</script>