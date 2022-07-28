<!-- Modal actualiza tipo documentos -->
<div class="modal fade" id="modalTipoDocumento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Tipo Documento</h5>
      </div>
      <div class="modal-body">
        <form id="formTipoDocumentoAct">
          <input type="text" name="id_tipodocumento" hidden id="id_tipodocumento">
          <label> Descripción</label>
          <input type="text" name="tdoc_descripcion" class="form-control form-control-sm mb-2" id="tdoc_descripcion" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="tdoc_estado" id="tdoc_estado" data-validate>
            <option value="ACTIVO">Habilitado</option>
            <option value="INACTIVO">Inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaTipoDocumento()">Actualizar</button>
      </div>
    </div>
  </div>
</div>
<!-- fin modal -->