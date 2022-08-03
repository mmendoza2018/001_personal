/* muestra el loader cuando la pagina esta cargada por completo */
window.addEventListener("load", () => {
  ocultarLoader();
  setTimeout(() => {
    /* $("#modaAlertaDocumentos").modal("show") */
  }, 1000);
});

document.addEventListener("DOMContentLoaded", () => {
  /* $("#contenido").load("vistas/home.php") */
  guardarDireccion();
  //$("#modales").load("componentes/modales.php");
  verLoader();
  ocultarLoader();
  validar_campos();
  CambiarIconoSidebar();
  validaNumerosPositivos();
  routerVistas("#index", "vistas/home.php");
  //DESDE AQUI MOSTRAR MENU

  routerVistas(
    "#sidebarTipoDocumento",
    "php/mantenimientos/tipoDocumento/index.php"
  );

  routerVistas(
    "#sidebarPuestoLaboral",
    "php/mantenimientos/puestoLaboral/index.php"
  );

  routerVistas(
    "#sidebarProyecto",
    "php/mantenimientos/proyecto/index.php"
  );

  routerVistas(
    "#sidebarMotivoPermisos",
    "php/mantenimientos/motivoPermiso/index.php"
  );
  /* fdsf */
  routerVistas(
    "#sidebarNuevoPersonal",
    "php/recursosHumanos/personal/index.php"
  );
  routerVistas(
    "#sidebarGestionPersonal",
    "php/recursosHumanos/personal/tabla.php"
  );
  routerVistas(
    "#sidebarNuevoDocumento",
    "php/recursosHumanos/documentos/index.php"
  );
  routerVistas(
    "#sidebarGestionDocumento",
    "php/recursosHumanos/documentos/tabla.php"
  );
  routerVistas(
    "#sidebarNuevoPermisoAdd",
    "php/permisos/formulario.php"
  );
  routerVistas(
    "#sidebarNuevoPermisoTabla",
    "php/permisos/tabla.php"
  );
  routerVistas(
    "#sidebarMonitorAlertas",
    "php/recursosHumanos/documentos/tablaMonitor.php"
  );

  

  // evitamos que muera la session del usuario
  mantenerSesionActiva();
});

/* funcion para cargar contendio via ajax  */
/* idBoton sidebar, url de la vista a mostrar */
function routerVistas(idBoton, url) {
  document.addEventListener("click", (e) => {
    if (e.target.matches(idBoton)) {
      verLoader();
      $.ajax({
        url: url,
        success: function (response) {
          $(`#contenidoGeneral`).html(response);
          localStorage.setItem("ruta", url);
          url === "php/proceso/lista_equipos_contrato.php"
            ? reducirSidebarlateral()
            : expandirSidebarlateral();
          ocultarLoader();
        },
      });
    }
  });
}

const mantenerSesionActiva = () => {
  // Invocamos cada 5 minutos ;)
  const milisegundos = 300000;
  setInterval(function () {
    fetch("php/refresca_sesion.php");
  }, milisegundos);
};

/* funcion para validar los campos  */
function validar_campos(idForm) {
  let data = document.querySelectorAll(`#${idForm} [data-validate]`);
  let validacion = true;
  if (data.length > 0) {
    for (let i = 0; i < data.length; i++) {
      if (
        data[i].getAttribute("type") === "text" &&
        data[i].value.match(/^[0-9]$/)
      ) {
        validacion = false;
        data[i].style.setProperty("border", "1px solid red");
        setTimeout(() => {
          data[i].style.setProperty("border", "");
        }, 2000);
      }
      if (
        data[i].getAttribute("type") === "number" &&
        !data[i].value.match(/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/)
      ) {
        data[i].style.setProperty("border", "1px solid red");
        validacion = false;
        setTimeout(() => {
          data[i].style.setProperty("border", "");
        }, 2000);
      }
      if (data[i].value === "" || data[i].value === null) {
        data[i].style.setProperty("border", "1px solid red");
        validacion = false;
        setTimeout(() => {
          data[i].style.setProperty("border", "");
        }, 2000);
      }
    }
  }
  return validacion;
}

const toastPersonalizada = (
  mensaje = "Ocurrio algun error",
  tipoAlerta = "success",
  tiempo = "2000"
) => {
  const Toast = Swal.mixin({
    toast: true,
    position: "bottom-end",
    showConfirmButton: false,
    timer: tiempo,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: tipoAlerta,
    title: mensaje,
  });
};

const alertaPersonalizada = (
  mensaje = "Ocurrio algun error",
  tipoAlerta = "success",
  tiempo = "1500"
) => {
  Swal.fire({
    position: "center",
    icon: tipoAlerta,
    title: mensaje,
    showConfirmButton: false,
    timer: tiempo,
  });
};

/* funcion para ocultar el loader de carga */
function ocultarLoader() {
  let loader = document.querySelector(".loader-page");
  loader.style.setProperty("visibility", "hidden");
  loader.style.setProperty("opacity", 0);
}

/* funcion para mostrar otro icono cuando se acorta en sidebar */
function CambiarIconoSidebar() {
  let pWGeneral = document.getElementById("PW_general");
}

/* funcion para mostrar el loader de carga */
function verLoader() {
  let loader = document.querySelector(".loader-page");
  loader.style = "";
}
/* funcion para mostrar el loader de carga */
function guardarDireccion() {
  let rutaActual = localStorage.getItem("ruta");
  if (rutaActual) {
    $("#contenidoGeneral").load(localStorage.getItem("ruta"));
  }
  rutaActual === "php/proceso/lista_equipos_contrato.php"
    ? reducirSidebarlateral()
    : expandirSidebarlateral();
}

const cargarContenido = (ruta, idLlegada, options={}, mostrarRes=false) => {
  fetch(ruta, options)
  .then(res => res.text())
  .then(html => {
    $(`#${idLlegada}`).html(html)
    if(mostrarRes) console.log('html', html)
  })
}

const expandirSidebarlateral = () => {
  $(".page-wrapper").removeClass("pinned");
  $("#sidebar").unbind("hover");
};

const reducirSidebarlateral = () => {
  $(".page-wrapper").addClass("pinned");
  $("#sidebar").hover(
    function () {
      $(".page-wrapper").addClass("sidebar-hovered");
    },
    function () {
      $(".page-wrapper").removeClass("sidebar-hovered");
    }
  );
};

const limpiarFormulario = (idFormulario) => {
  document.getElementById(idFormulario).reset();
};

/* funcion para esperar confirmacion al realizar una accion */
function confirmacion() {
  Swal.fire({
    target: document.getElementById("modal_act_prestamo"),
    title: "¿Estas seguro de actualizar?",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "si",
    cancelButtonText: "Cancelar",
  }).then((result) => result.isConfirmed);
}

//para resetear el formulario
const validaRespuestasAgregar = (
  respuesta,
  ruta = false,
  idFormulario = false,
  idModal = false
) => {
  if (respuesta) {
    Swal.fire({
      position: "center",
      icon: "success",
      title: "Agregado con exito!",
      showConfirmButton: false,
      timer: 1500,
    });

    if (idFormulario !== false) {
      document.getElementById(idFormulario).reset();
    }
    if (idModal !== false) {
      $("#" + idModal).modal("hide");
    }
    if (ruta !== false) {
      $("#contenidoGeneral").load(ruta);
    }
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "Fallo al agregar!",
      showConfirmButton: false,
      timer: 1500,
    });
  }
};

/* funcion para mostrar alertas de acuerdo a la respuesta, recibe dos parametros una la respuesta y la otra la ruta 
para resetear el formulario */
const validaRespuestaActualizar = (respuesta, ruta = false, idmodal) => {
  if (respuesta) {
    Swal.fire({
      position: "center",
      icon: "success",
      title: "Actualizado con exito!",
      showConfirmButton: false,
      timer: 1500,
    });
    if (ruta !== false) $("#contenidoGeneral").load(ruta);
    $("#" + idmodal).modal("hide");
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "Fallo al Actualizar!",
      showConfirmButton: false,
      timer: 1500,
    });
  }
};
const validaNumerosPositivos = () => {
  document.addEventListener("keyup", (e) => {
    if (e.target.matches(`input[type="number"]`)) {
      let valor = e.target.value;
      if (Math.sign(valor) === -1) {
        e.target.style.setProperty("border", "1px solid red");
        setTimeout(() => {
          e.target.value = "";
          e.target.style.setProperty("border", "");
        }, 2000);
      }
    }
  });
};
const generapdf = (id, ruta, descripcionArchivo) => {
  var left = screen.width / 2 - (window.innerWidth * 0.75) / 2;
  window.open(
    `${ruta}?id=${id}`,
    descripcionArchivo,
    `width=${window.innerWidth * 0.75},
    height=${window.innerHeight},
    margin=0,padding=5,scrollbars=SI,top=80,left=${left}`
  );
};
const popover = () => {
  var popover = document.querySelectorAll(".ListaPopover");
  popover.forEach((e) => {
    var popover = new bootstrap.Popover(e, {
      container: "body",
      trigger: "focus",
    });
  });
};
const Tooltips = () => {
  var tootipsAlertEQCO = document.querySelectorAll(".tooltips");
  tootipsAlertEQCO.forEach((e) => {
    var tooltip = new bootstrap.Tooltip(e, {
      Animation: true,
    });
  });
};
