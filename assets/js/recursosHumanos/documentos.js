const agregarDocumentoPer = () => {
  event.preventDefault();
  if (!validar_campos("formAddDocumentoPer"))
    return toastPersonalizada(
      "Algunos campos omitidos son obligatorios",
      "warning"
    );
  verLoader();
  let formulario = document.getElementById("formAddDocumentoPer");
  let data = new FormData(formulario);
  fetch("php/recursosHumanos/documentos/agrega.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.text())
    .then((json) => {
      console.log("json", json);
      if (json) {
        toastPersonalizada("Agregado correctamente!", "success", 2000);
        cargarContenido("php/recursosHumanos/documentos/index.php","contenidoGeneral")
      } else {
        toastPersonalizada("Ocurrio un error al agregar!", "error", 2000);
      }
      ocultarLoader();
    });
};
const obtenerListaDocsPer = (idPersona) => {
  let data = new FormData();
  data.append("idPersona", idPersona);
  cargarContenido(
    "php/recursosHumanos/documentos/listaDocumentos.php",
    "llegaListaDocPer",
    {
      method: "POST",
      body: data,
    }
  );
};

const verDocumentoPersonal = (idDocumento) => {
  let data = new FormData();
  data.append("idDocumento", idDocumento);
  cargarContenido(
    "php/recursosHumanos/documentos/verDocumento.php",
    "llegaPdfPersonal",
    {
      method: "POST",
      body: data,
    },
    true
  );
};

const llenarDocumentosAct = (data, isMonitorAlerta) => {
  let [
    idDocumento,
    idPersona,
    idTipoDoc,
    fecha1,
    fecha2,
    numDoc,
    descripcion,
    empresa,
    observacion,
    idPersonaAux,
  ] = data.split("|");
  document.getElementById("idDocAct").value = idDocumento;
  document.getElementById("idPersonaActDoc").value = idPersona;
  document.getElementById("idTipoDocActDOc").value = idTipoDoc;
  document.getElementById("idTipoDocActDOc").click();
  document.getElementById("fInicioDocAct").value = fecha1;
  document.getElementById("fFinDocAct").value = fecha2;
  document.getElementById("numeroDocAct").value = numDoc;
  document.getElementById("descripcionDocAct").value = descripcion;
  document.getElementById("empresaDocAct").value = empresa;
  document.getElementById("observacionesDocAct").value = observacion;
  document.getElementById("idPersonaAuxiliar").value = idPersonaAux;
  document.getElementById("idBtnActDocs").dataset.tabla = isMonitorAlerta;

  $(document).ready(function () {
    $(".select2").select2({
      placeholder: "Seleccione una opcion",
      dropdownParent: $("#modalActDocPersonal"),
      width: "100%",
    });
  });
  $(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
  });
};
const actualizaDocumentoPer = (elemento) => {
  if (validar_campos("formActDocPersonal")) {
    Swal.fire({
      title: "¿Estas seguro de actualizar?",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "si",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        let formulario = document.getElementById("formActDocPersonal");
        let data = new FormData(formulario);
        let monitorAlerta = elemento.dataset.tabla;
        fetch("php/recursosHumanos/documentos/actualiza.php", {
          method: "POST",
          body: data,
        })
          .then((res) => res.json())
          .then((json) => {
            if (json) {
              toastPersonalizada("Actualizado correctamente !", "success");
              $("#modalActDocPersonal").modal("hide");
              let idPersonaAux =
                document.getElementById("idPersonaAuxiliar").value;
                let modalMonitorAlertas = document.getElementById('modalVerTablaMonitor');
                if (modalMonitorAlertas.classList.contains('show')) {
                  cargarContenido("php/recursosHumanos/documentos/tablaMonitor.php","llegaTablaALertaDocs")
                } else{
                  if (monitorAlerta === "true") {
                    cargarContenido("php/recursosHumanos/documentos/tablaMonitor.php","contenidoGeneral")
                  }else {
                    obtenerListaDocsPer(idPersonaAux);
                  }
                }
            } else {
              toastPersonalizada("Ocurrio algun error!", "error");
            }
          });
      }
    });
  }
};
