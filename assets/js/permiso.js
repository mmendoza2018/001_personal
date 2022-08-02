function agregarPermisos() {
  if (!validar_campos("formPermisosAdd"))
    return toastPersonalizada("Datos Incompletos", "error");
  verLoader();
  let formulario = document.getElementById("formPermisosAdd");
  let data = new FormData(formulario);
  fetch("php/permisos/agrega.php", {
    method:'POST',
    body:data
  })
  .then(res => res.json())
  .then(json => {
    validaRespuestasAgregar(
      json,
      "php/permisos/formulario.php"
    );
    ocultarLoader();
  })
}

const llenarDatosMotivo2 = (dato) => {
  let x = dato.split("|");
  document.getElementById("id_motivo").value = x[0];
  document.getElementById("mot_descripcion").value = x[1];
  document.getElementById("mot_estado").value = "ACTIVO";
};

const actualizaMotivo2 = () => {
  if (validar_campos("formMotivoSalidaAct")) {
    Swal.fire({
      title: "¿Estas seguro de actualizar?",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "si",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        verLoader();
        event.preventDefault();
        $.ajax({
          type: "POST",
          url: "php/mantenimientos/motivoPermiso/actualiza.php",
          data: $("#formMotivoSalidaAct").serialize(),
          success: function (response) {
            validaRespuestaActualizar(
              response,
              "php/mantenimientos/motivoPermiso/index.php",
              "modalMotivoAct"
            );
            ocultarLoader();
          },
        });
      }
    });
  } else {
    toastPersonalizada("Falta Datos", "error");
  }
};
