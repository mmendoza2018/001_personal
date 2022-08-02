<!-- Modal actualiza tipo documentos -->
<div class="modal fade" id="modalMotivoAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Motivo Salida</h5>
      </div>
      <div class="modal-body">
        <form id="formMotivoSalidaAct">
          <input type="text" name="id_motivo" hidden id="id_motivo">
          <label> Descripción</label>
          <input type="text" name="mot_descripcion" class="form-control form-control-sm mb-2" id="mot_descripcion" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="mot_estado" id="mot_estado" data-validate>
            <option value="ACTIVO">Habilitado</option>
            <option value="INACTIVO">Inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaMotivo()">Actualizar</button>
      </div>
    </div>
  </div>
</div>
<!-- fin modal -->