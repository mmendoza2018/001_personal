<!-- Modal actualiza tipo documentos -->
<div class="modal fade" id="modalPuestoLaboral" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Tipo Documento</h5>
      </div>
      <div class="modal-body">
        <form id="formPuestoLaboralAct">
          <input type="text" name="idPuestoLaboral" hidden id="idPuestoLaboral">
          <label> Descripción</label>
          <input type="text" name="descripcionPuestoLaboral" class="form-control form-control-sm mb-2" id="descripcionPuestoLaboral" data-validate>
          <label> Detalle</label>
          <input type="text" name="detallePuestoLaboral" class="form-control form-control-sm mb-2" id="detallePuestoLaboral" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoPuestoLaboral" id="estadoPuestoLaboral" data-validate>
            <option value="ACTIVO">Habilitado</option>
            <option value="INACTIVO">Inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaPuestoLaboral()">Actualizar</button>
      </div>
    </div>
  </div>
</div>
<!-- fin modal -->