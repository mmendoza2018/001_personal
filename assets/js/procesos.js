const verListaContratos = (idProyecto) => {
  verLoader();
  $.ajax({
    type: "POST",
    url: "php/proceso/contrato/lista_contrato.php",
    data: "idProyecto=" + idProyecto,
    success: function (response) {
      $("#llegaListadoContrato").html(response);
      ocultarLoader();
    },
  });
};
const verListaEquipos = (idContrato) => {
  verLoader();
  $.ajax({
    type: "POST",
    url: "php/proceso/contrato/lista_equipo.php",
    data: "idContrato=" + idContrato,
    success: function (response) {
      $("#llegaListadoEquipos").html(response);
      ocultarLoader();
      $("#tabla_listaEquiposC").DataTable({
        info: false,
        ordering: false,
        pageLength: 50,
        language: {
          url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
      });
    },
  });
};

const verMantenimientosEquipo = (idEquipo, idContratoEquipo) => {
  mostrarPrimerULtimoDIaAnio();
  mostrarPrimerUltimoDiaMes();
  verLoader();
  $.ajax({
    type: "POST",
    url: "php/proceso/index_equipo.php",
    data: "idEquipo=" + idEquipo + "&idContratoEquipo=" + idContratoEquipo,
    success: function (response) {
      $("#contenido").html(response);
      popover();
      ocultarLoader();
    },
  });
};
const llenarDatosDescripcion = (datosProyecto, inputDescripcion, inputId) => {
  let arrayDatosProyecto = datosProyecto.split("|");
  document.getElementById(inputDescripcion).value = arrayDatosProyecto[1];
  document.getElementById(inputId).value = arrayDatosProyecto[0];
};
const llenarDatosEquipoContrato = (datosContrato) => {
  fetch("php/proceso/contrato/equipo_contrato/equipos_habiles.php")
    .then((res) => res.text())
    .then(
      (html) =>
        (document.getElementById("llegaListaEquiposHabiles").innerHTML = html)
    );
  let arrayDatosContrato = datosContrato.split("|");
  document.getElementById("idContratoAddEquipo").value = arrayDatosContrato[0];
  document.getElementById(
    "contratoAddEquipo"
  ).value = `${arrayDatosContrato[1]}-${arrayDatosContrato[2]}`;
};

const llenarDatosTISIEquipoCambio = (datosEquipoCambio) => {
  let [id] = datosEquipoCambio.split("|");
  document.getElementById("idCambioEquipo").value = id;
  // document.getElementById("descFamiliaSisFamilia").textContent = `${arrayDatosFamilia[1]}-${arrayDatosFamilia[2]}`;
};

const llenarDatosParametrosDiarios = (
  elemento,
  registro,
  modoPeticion = null,
  secuencia = null
) => {
  verLoader();
  let modalReferencia = document.getElementById("CajaModalmodalPD");
  let inputsPDEPrimario = document.getElementById("llegaInputsPDEPrimario");
  let inputsPDESecundario = document.getElementById("llegaInputsPDESecundario");
  let idEquipoPrimario = elemento.dataset.idequipri;
  let idEquipoSecundario = elemento.dataset.idequisec;
  let listaDtEquPrimarios = document.querySelectorAll(
    ".btn-sec[data-idequipri]"
  );
  listaDtEquPrimarios.forEach((e) => (e.dataset.idequipri = idEquipoPrimario));
  let listaDtEquSecundarios = document.querySelectorAll(
    ".btn-sec[data-idequisec]"
  );
  listaDtEquSecundarios.forEach(
    (e) => (e.dataset.idequisec = idEquipoSecundario)
  );

  let dataEquipoPrimario = new FormData();
  let dataEquipoSecundario = new FormData();
  dataEquipoPrimario.append("idEquipo", idEquipoPrimario);
  if (registro) {
    dataEquipoSecundario.append("registro", true);
    dataEquipoPrimario.append("registro", true);
  } else {
    if (modoPeticion === "date") {
      dataEquipoPrimario.append(
        "fechaBusquedaChange",
        document.getElementById("fechaBusquedaPD").value
      );
      dataEquipoSecundario.append(
        "fechaBusquedaChange",
        document.getElementById("fechaBusquedaPD").value
      );
    }
    dataEquipoSecundario.append("registro", false);
    dataEquipoPrimario.append("registro", false);
    dataEquipoPrimario.append("modoPeticion", modoPeticion);
    dataEquipoPrimario.append("secuencia", secuencia);
    dataEquipoPrimario.append(
      "fechaActualReferencia",
      document.querySelectorAll("[data-formfecharef]")[0].dataset.formfecharef
    );
    dataEquipoSecundario.append("modoPeticion", modoPeticion);
    dataEquipoSecundario.append("secuencia", secuencia);
    if (idEquipoSecundario !== "")
      dataEquipoSecundario.append(
        "fechaActualReferencia",
        document.querySelectorAll("[data-formfecharef]")[1].dataset.formfecharef
      );
  }

  if (idEquipoSecundario !== "") {
    dataEquipoSecundario.append("idEquipo", idEquipoSecundario);
    modalReferencia.classList.remove("modal-md");
    modalReferencia.classList.add("modal-lg");
    inputsPDEPrimario.classList.remove("item-pd-flex-version");
    inputsPDEPrimario.classList.add("item-pd-flex");
    inputsPDESecundario.classList.remove("d-none");
    renderizaCamposPD(dataEquipoPrimario, "llegaInputsPDEPrimario");
    renderizaCamposPD(dataEquipoSecundario, "llegaInputsPDESecundario");
  } else {
    inputsPDEPrimario.classList.remove("item-pd-flex");
    inputsPDEPrimario.classList.add("item-pd-flex-version");
    modalReferencia.classList.remove("modal-lg");
    modalReferencia.classList.add("modal-md");
    inputsPDESecundario.classList.add("d-none");
    renderizaCamposPD(dataEquipoPrimario, "llegaInputsPDEPrimario");
    inputsPDESecundario.innerHTML = "";
  }
  ocultarLoader();
};

const sumarMedicionActual = (
  elemento,
  idMedicionAnterior,
  idMedicionActual,
  idMedicionGeneral
) => {
  let horasTrabajadas = elemento.value || 0;
  let medicionAnterior = document.getElementById(idMedicionAnterior).value || 0;
  let medicionActual = document.getElementById(idMedicionActual);
  let medicionGeneral = document.getElementById(idMedicionGeneral);
  let valorHorasTrabajadas = elemento.dataset.mecion_trab_val;
  medicionGeneral.value = elemento.dataset.general;

  //console.log(horasTrabajadas, medicionAnterior, medicionActual);
  medicionActual.value =
    parseFloat(horasTrabajadas) + parseFloat(medicionAnterior);
  medicionGeneral.value =
    parseFloat(medicionGeneral.value) +
    parseFloat(horasTrabajadas) -
    valorHorasTrabajadas;
};

const renderizaCamposPD = (data, idLlegada) => {
  fetch("php/proceso/parametros_diarios/tableroIngreso.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.text())
    .then((html) => {
      /*  document.getElementById(idLlegada).innerHTML=; */
      /* console.log("html", html); */
      if (html.length < 100) {
        let arrayResponse = html.split("||");
        toastPersonalizada(arrayResponse[1], "error");
      } else {
        $("#" + idLlegada).html(html);
      }
    });
};

const llenarDatosOTAct = (idOrdenTrabajo) => {
  let data = new FormData();
  data.append("idOrdenTrabajo", idOrdenTrabajo);
  fetch("php/proceso/ot/obtener_ot.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.json())
    .then((arrayOT) => {
      //console.log(arrayOT);
      document.getElementById("idOtAct").value = `${arrayOT[0].idOt}`;
      document.getElementById(
        "codigoEQOTAct"
      ).value = `${arrayOT[0].codigoPlacaEquipo}`;
      document.getElementById("famEQOTAct").value = `${arrayOT[0].familia}`;
      document.getElementById("eventoOTAct").value = `${
        arrayOT[0].tipoEvento || ""
      }`;
      document.getElementById(
        "kilometrajeOTACt"
      ).value = `${arrayOT[0].kilometraje || ""}`;
      document.getElementById(
        "HChasisAnaOTACt"
      ).value = `${arrayOT[0].hChasisAna}`;
      document.getElementById(
        "HChasisDigiOTACt"
      ).value = `${arrayOT[0].hChasisDigi}`;
      document.getElementById("HGruaAnaOTACt").value = `${
        arrayOT[0].hGruaAna || ""
      }`;
      document.getElementById("HGruaDigiOTACt").value = `${
        arrayOT[0].hGruaDigi || ""
      }`;
      document.getElementById("HBrazoHidraulicoOTAct").value = `${
        arrayOT[0].hBrazo || ""
      }`;
      document.getElementById("supervisoresOTAct").value = `${
        arrayOT[0].supervisor || ""
      }`;
      document.getElementById("tecnicoCampoOTAct").value = `${
        arrayOT[0].tecnicoResponsable || ""
      }`;
      document.getElementById("OperadorOTAct").value = `${
        arrayOT[0].operador || ""
      }`;
      document.getElementById("jefeEquiposOTAct").value = `${
        arrayOT[0].jefeEquipos || ""
      }`;
      document.getElementById("centroCostoOTAct").value = `${
        arrayOT[0].centroCosto || ""
      }`;
      document.getElementById(
        "descripcionTIMEOT"
      ).textContent = `${arrayOT[0].tipoMedicion}`;
      document.getElementById(
        "medicionCreacionOtTIMEOT"
      ).value = `${arrayOT[0].medicion}`;
       let listaTipoGruas = document.querySelectorAll("#radioTGACTOT input");
       let TiposGruasDB = arrayOT[0].tipoGrua || "".split("||");
       listaTipoGruas.forEach(e => e.checked=false)
       listaTipoGruas.forEach((e1) => {
         for (let x = 0; x < TiposGruasDB.length; x++) {
          if (e1.value === TiposGruasDB[x]) {
            e1.checked = true;
            break;
          }
         }
       })
      document.getElementById("FIinicioOTAct").value = `${arrayOT[0].fInicio}`;
      document.getElementById("fCierreOTAct").value = `${arrayOT[0].fCierre}`;
      document.getElementById("estadoOTAct").value = `${arrayOT[0].estado}`;
      document.getElementById("placa2OTAct").value = `${arrayOT[0].placa2 || '' }`;
      document.querySelector(
        "[data-idotimg]"
      ).dataset.idotimg = `${arrayOT[0].idOt}`;
      document
        .querySelectorAll("[data-idotpdf]")
        .forEach((e) => (e.dataset.idotpdf = `${arrayOT[0].idOt}`));
    });
  let peticionTRealizar = fetch("php/proceso/ot/tareas_a_realizar.php", {
    method: "POST",
    body: data,
  });
  let peticionImagenes = fetch("php/proceso/ot/ver_imagenes.php", {
    method: "POST",
    body: data,
  });
  let peticionTrealizadas = fetch("php/proceso/ot/trabajos_realizados.php", {
    method: "POST",
    body: data,
  });
  let peticionInsumos = fetch("php/proceso/ot/insumos_usados.php", {
    method: "POST",
    body: data,
  });

  Promise.all(
    [
      peticionTRealizar,
      peticionImagenes,
      peticionTrealizadas,
      peticionInsumos,
    ].map((prom) => prom.then((res) => res.text()))
  ).then((html) => {
    refrescaListaDatosTrabajadores();
    document.getElementById("llegaTareasARelizar").innerHTML = html[0];
    document.getElementById("llegaImagenesOtAct").innerHTML = html[1];
    document.getElementById("llegaCambiosRealizados").innerHTML = html[2];
    document.getElementById("llegaInsumosOt").innerHTML = html[3];
  });
};
/* window.addEventListener("load", () => {
  refrescaListaDatosTrabajadores();
}); */

/* const refrescaListaDatosTrabajadores = () => {
  let peticionTrabajadores = fetch(
    "php/mantenimiento/procesos/trabajador/listaTrabajadores.php"
  );
  let peticionPlanners = fetch(
    "php/mantenimiento/procesos/trabajador/listaPlanners.php"
  );
  let peticionOperadores = fetch(
    "php/mantenimiento/procesos/trabajador/listaOperadores.php"
  );

  Promise.all(
    [peticionTrabajadores, peticionOperadores, peticionPlanners].map((prom) =>
      prom.then((res) => res.text())
    )
  ).then((html) => {
    document
      .querySelectorAll(".LlegaOpcionesTrabajadores")
      .forEach((e) => (e.innerHTML = html[0]));
    document
      .querySelectorAll(".llegaOpcionesOperadores")
      .forEach((e) => (e.innerHTML = html[1]));
    document
      .querySelectorAll(".LlegaOpcionesPlanners")
      .forEach((e) => (e.innerHTML = html[2]));
  });
}; */

const llenarIdOtAddImg = (elemento) => {
  document.getElementById("idOtAddImg").value = elemento.dataset.idotimg;
  //dragAndDRopImg();
  funcionalidadPegarImg();
};

const llenarDatosCRealizadosOt = (datosContrato) => {
  let [id, descripcion, duracion, trabajador] = datosContrato.split("|");
  document.getElementById("idTRealizadosAct").value = id;
  document.getElementById("descTRealizadosAct").value = descripcion;
  document.getElementById("duraTRealizadosAct").value = duracion;
  document.getElementById("trabajadorTRealizadosAct").value = trabajador;
  document.getElementById("estadoTRealizadosAct").value = 1;
};

const llenarInsumosOt = (datosContrato) => {
  let [
    id,
    descripcion,
    codigo,
    marca,
    cantidad,
    observacion,
    moneda,
    precio,
    uMedida,
  ] = datosContrato.split("|");
  document.getElementById("idInsumosOtAct").value = id;
  document.getElementById("descInsumosOtAct").value = descripcion;
  document.getElementById("codigoInsumosOtAct").value = codigo;
  document.getElementById("marcaInsumosOtAct").value = marca;
  document.getElementById("cantiInsumosOtAct").value = cantidad;
  document.getElementById("monedaiInsumosOtAct").value = moneda;
  document.getElementById("uMedidaInsumosOtAct").value = uMedida;
  document.getElementById("precioInsumosOtAct").value = precio;
  /*document.getElementById("totalInsumosOtAct").value = total; */
  document.getElementById("obserInsumosOtAct").value = observacion;
  document.getElementById("estadoInsumosOtAct").value = 1;
};

const obteneridElSeleccionado = (
  idFormulario,
  classInput,
  classAlternativo = ""
) => {
  if (
    document
      .getElementById(idFormulario)
      .querySelector(`.${classInput + classAlternativo}`) === null
  )
    return null;
  //la clase del input formulario debe ser igual al value del list
  let valueSelect = document
    .getElementById(idFormulario)
    .querySelector(`.${classInput + classAlternativo}`).value;
  let options = document.querySelectorAll(
    `#${classInput + classAlternativo} option`
  );
  let idFinal = "";
  options.forEach((e) => {
    if (e.textContent.replace(/\s+/g, "") === valueSelect.replace(/\s+/g, ""))
      idFinal = e.dataset.value;
  });
  return idFinal;
};
const agregaContrato = async () => {
  if (validar_campos("formAgregaContrato")) {
    event.preventDefault();
    let idCliente = await obteneridElSeleccionado(
      "formAgregaContrato",
      "clienteAddContrato"
    );
    if (idCliente === "") return alertaCamposVacios("El cliente No existe");
    verLoader();
    $.ajax({
      url: "php/proceso/contrato/agrega.php",
      type: "POST",
      data: $("#formAgregaContrato").serialize() + "&cliente=" + idCliente,
      success: function (response) {
        validaRespuestasAgregar(
          response,
          "php/proceso/contrato/lista_proyecto.php",
          "formAgregaContrato",
          "modalAgregaContrato"
        );
        ocultarLoader();
      },
    });
  } else {
    alertaCamposVacios();
  }
};

const validarCamposRepetidosClone = (selector) => {
  let listaInputs = document.querySelectorAll(selector);
  let arrayInputs = Array.from(listaInputs);
  let duplicado = [false, ""];
  for (let x = 0; x < arrayInputs.length; x++) {
    let auxiliar = 0;
    let validate = arrayInputs.filter(
      (index) => index.value === arrayInputs[x].value
    );
    if (validate.length > 1) {
      duplicado = [true, arrayInputs[x].value];
      break;
    }
  }
  return duplicado;
};

//quitado por el momento
const agregaTISIEquipoCambio = async () => {
  if (validar_campos("formAddTISIEquipoCAmbio")) {
    event.preventDefault();
    let options = document.getElementById("listaSisFamilias").children;
    let sistemas = document.querySelectorAll(".listaSisFamilias");
    let idsSistemas = [];
    let validaciones = 0;
    sistemas.forEach((e) => {
      for (const x of options) {
        if (x.value.replace(/\s+/g, "") === e.value.replace(/\s+/g, "")) {
          validaciones++;
          idsSistemas.push(x.dataset.value);
        }
      }
    });
    if (validaciones != sistemas.length)
      return alertaCamposVacios("Algun sistema no es valido");
    let [repetido, textRepetido] = await validarCamposRepetidosClone(
      "#inputCloneSistema[data-clone] input[list]"
    );
    console.log(repetido, textRepetido);
    if (repetido)
      return toastPersonalizada(
        `el campo ${textRepetido} fue duplicado`,
        "error"
      );
    verLoader();
    $.ajax({
      url: "php/mantenimiento/equipo/cambio_sis_equipo/agrega.php",
      type: "POST",
      data:
        $("#formAddTISIEquipoCAmbio").serialize() + "&idSistema=" + idsSistemas,
      success: function (response) {
        console.log(response);
        let responseparsing = JSON.parse(response);
        if (responseparsing[0]) {
          alertaPersonalizada("Agregado con exito!", "success");
          limpiarFormulario("formAddTISIEquipoCAmbio");
          $("#modalAgregaTISI").modal("hide");
          let inputsInsertados = document.querySelectorAll(
            "[data-clone]#inputCloneSistema"
          );
          console.log(inputsInsertados);
          let contador = 1;
          if (responseparsing[0]) {
            inputsInsertados.forEach((e) => {
              if (contador == inputsInsertados.length) return;
              e.remove();
              contador++;
            });
          }
        } else {
          if (responseparsing[1].length === 0) {
            alertaPersonalizada("Fallo al agregar!", "error");
          } else {
            let sistemasRepetidos = "";
            responseparsing[1].forEach((element) => {
              sistemasRepetidos += `${element},`;
            });
            let texto = "";
            texto =
              responseparsing[1].length > 1
                ? "ya fueron registrados."
                : "Ya fue registrado.";
            toastPersonalizada(
              `${sistemasRepetidos.slice(0, -1)} ${texto}`,
              "error"
            );
          }
        }
        ocultarLoader();
      },
    });
  } else {
    alertaCamposVacios();
  }
};

const verListaTiempoMantenimiento = (datosEquipo) => {
  let [id, codigo, familia] = datosEquipo.split("|");
  document.getElementById(
    "descSisFamiliaEquipo"
  ).textContent = `${codigo} - ${familia}`;
  document.getElementById("llegaIdEquipoListaMamtenimientos").value = id;
  verLoader();
  $.ajax({
    url: "php/mantenimiento/equipo/equipo_mantenimiento/lista_cambios.php",
    type: "POST",
    data: "idEquipo=" + id,
    success: function (response) {
      $("#llegaListadoSisFamilia").html(response);
      ocultarLoader();
    },
  });
};

const addEquiposMantenimiento = (datos) => {
  let [idEquipo, codigo, familia] = datos.split("|");
  document.getElementById(
    "descequipoAddSistema"
  ).textContent = `${codigo} - ${familia}`;
  verLoader();
  $.ajax({
    url: "php/mantenimiento/equipo/equipo_mantenimiento/lista_add_cambios.php",
    type: "POST",
    data: "idEquipo=" + idEquipo,
    success: function (response) {
      $("#llegaAddListadoCambiosEquipo").html(response);
      ocultarLoader();
    },
  });
};

const verListaTISIMantenimiento = (dataEquipoMantenimiento) => {
  let [id, , habilitarActualizar] = dataEquipoMantenimiento.split("|");
  verLoader();
  $.ajax({
    url: "php/mantenimiento/equipo/cambio_sis_equipo/tabla.php",
    type: "POST",
    data:
      "idEquipoMantenimiento=" +
      id +
      "&habilitarActualizar=" +
      habilitarActualizar,
    success: function (response) {
      $("#llegaListadoTISI").html(response);
      ocultarLoader();
    },
  });
};

const llenarDatosTIMACONF = (data) => {
  let [idEquipoMantenimiento, , idEquipo] = data.split("|");
  verLoader();
  $.ajax({
    url: "php/mantenimiento/equipo/equipo_mantenimiento/dataList_cambios.php",
    type: "POST",
    data: "idEquipo=" + idEquipo,
    success: function (response) {
      $("#llegaDataListTiempoMan").html(response);
      $("#idEquipoManteConfig").val(idEquipoMantenimiento);
      ocultarLoader();
    },
  });
};
const quitarAsignacionTIMACONF = (datos) => {
  let [idEquipoMantenimiento, , idEquipo] = datos.split("|");
  Swal.fire({
    title: "¿Estas seguro de quitar la asignación?",
    text: "una vez actualizado podras configurar nuevamente los sistemas y/o asignar nuevamente",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      let data = new FormData();
      data.append("idEquipoMantenimiento", idEquipoMantenimiento);
      verLoader();
      fetch(
        "php/mantenimiento/equipo/equipo_mantenimiento/quitarAsignacion.php",
        {
          method: "POST",
          body: data,
        }
      )
        .then((res) => res.json())
        .then((json) => {
          if (json) {
            console.log("json", json);
            let data2 = new FormData();
            data2.append("idEquipo", idEquipo);
            fetch(
              "php/mantenimiento/equipo/equipo_mantenimiento/lista_cambios.php",
              {
                method: "POST",
                body: data2,
              }
            )
              .then((res) => res.text())
              .then((html) => {
                $("#llegaListadoSisFamilia").html(html);
              });
          }
          ocultarLoader();
        });
    }
  });
};

const AgregarConfiguracionTIMA = () => {
  let options = document.getElementById("listaTiempoMantenimiento").children;
  let tiempos = document.querySelectorAll(".listaTiempoMantenimiento");
  let idEquipoMantenimiento = document.getElementById(
    "idEquipoManteConfig"
  ).value;
  let idsTiempos = [];
  let validaciones = 0;
  tiempos.forEach((e) => {
    for (const x of options) {
      if (x.value.replace(/\s+/g, "") === e.value.replace(/\s+/g, "")) {
        validaciones++;
        idsTiempos.push(x.dataset.value);
      }
    }
  });
  if (validaciones != tiempos.length)
    return alertaCamposVacios("Algun Tiempo definido no es valido");
  $.ajax({
    url: "php/mantenimiento/equipo/equipo_mantenimiento/config_mantenimiento.php",
    type: "POST",
    data:
      "idEquipoConfiguracion=" +
      tiempos[0].value +
      "&idEquipoMantenimiento=" +
      idEquipoMantenimiento,
    success: function (response) {
      validaRespuestaActualizar(
        response,
        false,
        "modalIngresoConfigMantenimiento"
      );
      document.getElementById("idTimaConfigEQMA").value = "";
      let data = new FormData();
      data.append(
        "idEquipo",
        document.getElementById("llegaIdEquipoListaMamtenimientos").value
      );
      fetch("php/mantenimiento/equipo/equipo_mantenimiento/lista_cambios.php", {
        method: "POST",
        body: data,
      })
        .then((res) => res.text())
        .then((html) => {
          $("#llegaListadoSisFamilia").html(html);
        });
      ocultarLoader();
    },
  });
};

const verListaOTS = (element, configuracionInicial) => {
  $("#modalLsiatOTSPorEquipo").modal("show");
  document.getElementById("cajaFiltrosOt").classList.remove("d-none");
  let primerDiaAnio = document.getElementById("PrimerDiaAnioListOt").value,
    ultimoDiaAnio = document.getElementById("ultimoDiaAnioListOt").value,
    idEquipoContrato = element.dataset.id_eqco;
  idEquipo = element.dataset.idequipo;
  document.querySelector("#buscarListaOt[data-id_eqco]").dataset.id_eqco =
    idEquipoContrato;
  document.querySelector("#buscarListaOt[data-idequipo]").dataset.idequipo =
    idEquipo;
  verLoader();

  if (configuracionInicial) {
    let data = new FormData();
    data.append("idEquipoContrato", idEquipoContrato);
    let peticionContrato = fetch("php/proceso/contrato/dataList.php", {
      method: "POST",
      body: data,
    });
    let data3 = new FormData();
    data3.append("idEquipoContrato", idEquipoContrato);
    let peticionProyecto = fetch(
      "php/mantenimiento/procesos/proyecto/dataList.php",
      {
        method: "POST",
        body: data3,
      }
    );
    Promise.all(
      [peticionContrato, peticionProyecto].map((prom) =>
        prom.then((res) => res.text())
      )
    ).then((html) => {
      [valueContrato, optionContrato] = html[0].split("||");
      document.getElementById("selectProyectoConfOt").innerHTML = html[1];
      document.getElementById("selectContratoConfOt").innerHTML =
        optionContrato;
      document.getElementById("inputContratoConfOt").value = valueContrato;
      let idContrato = obteneridElSeleccionado(
        "formObtenerListaOt",
        "selectContratoConfOt"
      );
      if (idContrato === "") {
        ocultarLoader();
        return alertaCamposVacios("El contrato No existe");
      }
      mostrarTablaListaOt(idEquipo, idContrato, primerDiaAnio, ultimoDiaAnio);
    });
  } else {
    let idContrato = obteneridElSeleccionado(
      "formObtenerListaOt",
      "selectContratoConfOt"
    );
    if (idContrato === "") {
      ocultarLoader();
      return alertaCamposVacios("El contrato No existe");
    }
    mostrarTablaListaOt(idEquipo, idContrato, primerDiaAnio, ultimoDiaAnio);
  }
  ocultarLoader();
};
const mostrarTablaListaOt = (
  idEquipo,
  idContrato,
  primerDiaAnio,
  ultimoDiaAnio
) => {
  let data2 = new FormData();
  data2.append("idEquipo", idEquipo);
  data2.append("idContrato", idContrato);
  data2.append("fInicio", primerDiaAnio);
  data2.append("fFinal", ultimoDiaAnio);
  let peticionOt = fetch("php/proceso/ot/lista_ot.php", {
    method: "POST",
    body: data2,
  });
  peticionOt
    .then((res) => res.text())
    .then((html) => {
      $("#llegaListaOtPorEquipo").html(html);
      $("#tabla_lista_ot_equipo").DataTable({
        info: false,
        language: {
          url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
      });
    });
};


const verListaOtGeneral = (element) => {
  verLoader();
  document.getElementById("cajaFiltrosOt").classList.add("d-none");
  $("#modalLsiatOTSPorEquipo").modal("show");
  let data = new FormData();
  data.append("idEquipo", element.dataset.idequipo);
  data.append("idContrato", false);
  let peticionOt = fetch("php/proceso/ot/lista_ot.php", {
    method: "POST",
    body: data,
  });
  peticionOt
    .then((res) => res.text())
    .then((html) => {
      $("#llegaListaOtPorEquipo").html(html);
      $("#tabla_lista_ot_equipo").DataTable({
        info: false,
        language: {
          url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
      });
      ocultarLoader();
    });
}

const verListaParameDiarios = (element, configuracionInicial) => {
  document.getElementById("cajaFiltrosPDGeneral").classList.remove("d-none");
  let primerDiaMes = document.getElementById("PrimerDiaMesPD").value,
    hoy = document.getElementById("ultimoDiaMesPD").value,
    idEquipoContrato = element.dataset.id_eqco;
  idEquipo = element.dataset.idequipo;
  document.querySelector("#buscarListaPD[data-idequipo]").dataset.idequipo =
    idEquipo;
  document.querySelector("#buscarListaPD[data-id_eqco]").dataset.id_eqco =
    idEquipoContrato;

  verLoader();
  if (configuracionInicial) {
    let data = new FormData();
    data.append("idEquipoContrato", idEquipoContrato);
    let peticionContrato = fetch("php/proceso/contrato/dataList.php", {
      method: "POST",
      body: data,
    });
    let data3 = new FormData();
    data3.append("idEquipoContrato", idEquipoContrato);
    let peticionProyecto = fetch(
      "php/mantenimiento/procesos/proyecto/dataList.php",
      {
        method: "POST",
        body: data3,
      }
    );
    Promise.all(
      [peticionContrato, peticionProyecto].map((prom) =>
        prom.then((res) => res.text())
      )
    ).then((html) => {
      [valueContrato, optionContrato] = html[0].split("||");
      document.getElementById("selectProyectoConfPd").innerHTML = html[1];
      document.getElementById("selectContratoConfPd").innerHTML =
        optionContrato;
      document.getElementById("inputContratoConfPd").value = valueContrato;
      let idContrato = obteneridElSeleccionado(
        "formObtenerListaPd",
        "selectContratoConfPd"
      );
      if (idContrato === "") {
        ocultarLoader();
        return alertaCamposVacios("El contrato No existe");
      }
      mostrarTablaListaPd(idEquipo, idContrato, primerDiaMes, hoy);
    });
  } else {
    let idContrato = obteneridElSeleccionado(
      "formObtenerListaPd",
      "selectContratoConfPd"
    );
    if (idContrato === "") {
      console.log("2");
      ocultarLoader();
      return alertaCamposVacios("El contrato No existe");
    }
    mostrarTablaListaPd(idEquipo, idContrato, primerDiaMes, hoy);
  }
  ocultarLoader();
};
const mostrarTablaListaPd = (idEquipo, idContrato, fInicio, hoy) => {
  let data2 = new FormData();
  data2.append("idEquipo", idEquipo);
  data2.append("idContrato", idContrato);
  data2.append("fInicio", fInicio);
  data2.append("hoy", hoy);
  let peticionPd = fetch("php/proceso/parametros_diarios/lista.php", {
    method: "POST",
    body: data2,
  });
  peticionPd
    .then((res) => res.text())
    .then((html) => {
      $("#llegaListaPDPorEquipo").html(html);
      $("#tabla_lista_pd_equipo").DataTable({
        info: false,
        language: {
          url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
      });
    });
};

const mostrarTablaListaPdGeneral = (element) => {
  verLoader();
  document.getElementById("cajaFiltrosPDGeneral").classList.add("d-none");
  let data = new FormData();
  data.append("idEquipo", element.dataset.idequipo);
  data.append("idContrato", false);
  let peticionPd = fetch("php/proceso/parametros_diarios/lista.php", {
    method: "POST",
    body: data,
  });
  peticionPd
    .then((res) => res.text())
    .then((html) => {
      $("#llegaListaPDPorEquipo").html(html);
      $("#tabla_lista_pd_equipo").DataTable({
        info: false,
        language: {
          url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
      });
      ocultarLoader();
    });
}

const selectAnidadoConfE = (elemento, idDataList, idInputText) => {
  let data = new FormData();
  data.append("idProyecto", elemento.value);
  let peticionContrato = fetch("php/proceso/contrato/dataList.php", {
    method: "POST",
    body: data,
  });
  peticionContrato
    .then((res) => res.text())
    .then((html) => {
      [valueContrato, optionContrato] = html.split("||");
      document.getElementById(idDataList).innerHTML = optionContrato;
      document.getElementById(idInputText).value = valueContrato;
    });
};

const mostrarPrimerULtimoDIaAnio = () => {
  let fechaActual = new Date(),
    anio = fechaActual.getFullYear(),
    mes = fechaActual.getMonth() + 1;

  mes = mes < 10 ? `0${mes}` : `${mes}`;

  let primerDiaAnio = `${anio}-01-01`;
  let ultimoDiaAnio = `${anio}-12-31`;
  document.getElementById("PrimerDiaAnioListOt").value = primerDiaAnio;
  document.getElementById("ultimoDiaAnioListOt").value = ultimoDiaAnio;
};

const mostrarPrimerUltimoDiaMes = () => {
  let fechaActual = new Date(),
    anio = fechaActual.getFullYear(),
    mes1 = fechaActual.getMonth() + 1;
  mes2 = fechaActual.getMonth() + 1;
  dia = fechaActual.getDate();
  mes1 = mes1 < 10 ? `0${mes1}` : `${mes1}`;
  mes2 = mes2 < 10 ? `0${mes2}` : `${mes2}`;
  dia = dia < 10 ? `0${dia}` : `${dia}`;
  let primerDiaMes = `${anio}-${mes1}-01`;
  let hoy = `${anio}-${mes2}-${dia}`;
  document.getElementById("PrimerDiaMesPD").value = primerDiaMes;
  document.getElementById("ultimoDiaMesPD").value = hoy;
};

const seleccionarVarios = (datachecks) => {
  let listaChecks = document.querySelectorAll(datachecks);
  if (event.target.checked) {
    listaChecks.forEach((e) => {
      e.checked = true;
    });
  } else {
    listaChecks.forEach((e) => {
      e.checked = false;
    });
  }
};

const agregaEquipoCont = async () => {
  if (validar_campos("formAddEquipoCon")) {
    event.preventDefault();
    let options = document.getElementById("listaEquipos").children;
    let ListaEquipos = document.querySelectorAll(".listaEquipos");
    let idsEquipos = [];
    let validaciones = 0;
    ListaEquipos.forEach((e) => {
      for (const x of options) {
        if (x.value.replace(/\s+/g, "") === e.value.replace(/\s+/g, "")) {
          validaciones++;
          idsEquipos.push(x.dataset.value);
        }
      }
    });
    if (validaciones != ListaEquipos.length)
      return alertaCamposVacios("Algun Equipo no es valido");
    let [repetido, textRepetido] = await validarCamposRepetidosClone(
      "#inputCloneEQCO[data-clone] input[list]"
    );

    if (repetido)
      return toastPersonalizada(
        `el campo ${textRepetido} fue duplicado`,
        "error"
      );
    verLoader();
    $.ajax({
      url: "php/proceso/contrato/equipo_contrato/agrega.php",
      type: "POST",
      data: $("#formAddEquipoCon").serialize(),
      success: function (response) {
        validaRespuestasAgregar(
          response,
          "php/proceso/contrato/lista_proyecto.php",
          false,
          "modalAgregaEquipoCon"
        );
        if (response === "true") {
          let inputsInsertados = document.querySelectorAll(
            "[data-clone]#inputCloneEQCO"
          );
          let contador = 1;
          inputsInsertados.forEach((e) => {
            if (contador == inputsInsertados.length) return;
            e.remove();
            contador++;
          });
          document.querySelector("[data-clone] input").value = "";
        }
        /*   document.getElementById("formAddEquipoCon").reset(); */
        ocultarLoader();
      },
    });
  } else {
    alertaCamposVacios();
  }
};

const agregaEquipoMantenimientos = () => {
  Swal.fire({
    title: `¿Estas seguro de actualizar?`,
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      verLoader();

      let formChecks = document.getElementById("checkboxsCambios");
      let ids = formChecks.querySelectorAll("[data-idchecked]");
      let chekedsCambios = formChecks.querySelectorAll("[data-checkcambio]");
      let data = new FormData();
      data.append("idEquipo", formChecks.idEquipo.value);
      chekedsCambios.forEach((e) => {
        data.append("checkcambio[]", e.checked);
      });
      ids.forEach((e) => {
        data.append("ids[]", e.dataset.idchecked);
      });
      fetch("php/mantenimiento/equipo/equipo_mantenimiento/agrega.php", {
        method: "POST",
        body: data,
      })
        .then((res) => res.text())
        .then((html) => {
          validaRespuestaActualizar(html, false, "modalAddMantenimiento");
          ocultarLoader();
        });
    }
  });
};

const actualizarMantenimientosEquipo = () => {
  let formulario = document.getElementById("formActCambiosEquipos");
  if (formulario.archivoPdf.files.length <= 0)
    return alertaCamposVacios("Ingrese el un pdf valido");
  verLoader();

  let data = new FormData(formulario);
  fetch("php/mantenimiento/equipo/equipo_mantenimiento/actualiza.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.text())
    .then((html) => {
      validaRespuestasAgregar(
        html,
        false,
        "formActCambiosEquipos",
        "modalActEquMantenimiento"
      );
      ocultarLoader();
    });
};

const actualizarCRealizadosOt = async () => {
  let idtrabajador = await obteneridElSeleccionado(
    "formTrabajoRealizadoAct",
    "listaTrabTRREOt"
  );
  if (idtrabajador === "") return alertaCamposVacios("El trabajador No existe");
  if (validar_campos("formTrabajoRealizadoAct")) {
    Swal.fire({
      title: `¿Estas seguro de actualizar?`,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "si",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        let formulario = document.getElementById("formTrabajoRealizadoAct");
        verLoader();
        let data = new FormData(formulario);
        data.append("idtrabajador", idtrabajador);
        fetch("php/proceso/ot/actualiza_t_realizados.php", {
          method: "POST",
          body: data,
        })
          .then((res) => res.text())
          .then((html) => {
            /* console.log(html); */
            validaRespuestaActualizar(html, false, "modalTrabajoRealizadoAct");
            if (html === "true") {
              let idOt = document.getElementById("idOtAct").value;
              let data = new FormData();
              data.append("idOrdenTrabajo", idOt);
              fetch("php/proceso/ot/trabajos_realizados.php", {
                method: "POST",
                body: data,
              })
                .then((res) => res.text())
                .then(
                  (resText) =>
                    (document.getElementById(
                      "llegaCambiosRealizados"
                    ).innerHTML = resText)
                );
            }
            ocultarLoader();
          });
      }
    });
  } else {
    alertaCamposVacios();
  }
};

const actualizarInsumosOt = () => {
  if (validar_campos("formInsumosOtAct")) {
    Swal.fire({
      title: `¿Estas seguro de actualizar?`,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "si",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        let formulario = document.getElementById("formInsumosOtAct");
        verLoader();
        let data = new FormData(formulario);
        fetch("php/proceso/ot/actualiza_i_usados.php", {
          method: "POST",
          body: data,
        })
          .then((res) => res.text())
          .then((html) => {
            /*  console.log(html); */
            validaRespuestaActualizar(html, false, "modalInsumosOtAct");
            if (html === "true") {
              let idOt = document.getElementById("idOtAct").value;
              let data = new FormData();
              data.append("idOrdenTrabajo", idOt);
              fetch("php/proceso/ot/insumos_usados.php", {
                method: "POST",
                body: data,
              })
                .then((res) => res.text())
                .then(
                  (resText) =>
                    (document.getElementById("llegaInsumosOt").innerHTML =
                      resText)
                );
            }
            ocultarLoader();
          });
      }
    });
  } else {
    alertaCamposVacios();
  }
};

const actualizarOrdenTrabajo = async () => {
  if (validar_campos("formActordenesTrabajo")) {
    let idTecnico = await obteneridElSeleccionado(
      "formActordenesTrabajo",
      "dataListTrabajadoresOt"
    );

    if (
      idTecnico === "" &&
      document.getElementById("tecnicoCampoOTAct").value.length > 0
    )
      return alertaCamposVacios("El tecnico No existe");
    let idSupervisor = await obteneridElSeleccionado(
      "formActordenesTrabajo",
      "dataListSupervisoresOt"
    );
    //console.log(document.getElementById("supervisoresOTAct").value.length,document.getElementById("supervisoresOTAct").value);
    if (
      idSupervisor === "" &&
      document.getElementById("supervisoresOTAct").value.length > 0
    )
      return alertaCamposVacios("El supervisor No existe");

    let idOperador = await obteneridElSeleccionado(
      "formActordenesTrabajo",
      "dataListOperadoresOt"
    );
    if (
      idOperador === "" &&
      document.getElementById("OperadorOTAct").value.length > 0
    )
      return alertaCamposVacios("El Operador No existe");
    let idJefeEquipos = await obteneridElSeleccionado(
      "formActordenesTrabajo",
      "dataLisJefeEquiposOt"
    );

    if (
      idJefeEquipos === "" &&
      document.getElementById("jefeEquiposOTAct").value.length > 0
    )
      return alertaCamposVacios("El Jefe de equipos No existe");

    let options = document.getElementById("listaTrabTRREOt").children;

    let validoCampos1 = validaCamposEspecificos(
      "[data-clone]#CloneCambiosRealizadosOt"
    );
    if (!validoCampos1)
      return alertaCamposVacios(
        "Algunos los campos son requeridos, Trabajos realizados"
      );
    let validoCampos2 = validaCamposEspecificos(
      "[data-clone]#CloneCambiosRepuestosOt"
    );
    if (!validoCampos2)
      return alertaCamposVacios(
        "Algunos los campos son requeridos, Insumos usados"
      );

    let trabajadores = document.querySelectorAll(
      "#trabajadorTRREOTAct.listaTrabTRREOt"
    );
    let idTrabajadores = [];
    trabajadores.forEach((e) => {
      for (const x of options) {
        if (x.value.replace(/\s+/g, "") === e.value.replace(/\s+/g, "")) {
          if (e.value !== "") {
            idTrabajadores.push(x.dataset.value);
          }
        }
      }
    });
    if (idTrabajadores.length < trabajadores.length) {
      let descripcion =
        trabajadores[0].parentElement.parentElement.querySelector(
          ".descripcionTR"
        ).value;
      let duracion =
        trabajadores[0].parentElement.parentElement.querySelector(
          ".duracionTR"
        ).value;
      if (trabajadores.length === 1 && descripcion === "" && duracion === "") {
      } else {
        return alertaCamposVacios("Algun trabajador no es valido");
      }
    } else {
      /* if (trabajadores.length > 1 || ) {
        return alertaCamposVacios("Algun trabajador no es valido");
      }else{ */
    }
    Swal.fire({
      title: `¿Estas seguro de actualizar?`,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "si",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        let formulario = document.getElementById("formActordenesTrabajo");

        let data = new FormData(formulario);
        data.append("supervisor", idSupervisor);
        data.append("tecnico", idTecnico);
        data.append("operador", idOperador);
        data.append("JefeEquipos", idJefeEquipos);
        data.append("idTrabajadores", idTrabajadores);

        fetch("php/proceso/ot/actualiza.php", {
          method: "POST",
          body: data,
        })
          .then((res) => res.text())
          .then((html) => {
            console.log(html);
            /*  ocultarLoader();
            return; */
            let dataUpdate = new FormData();
            dataUpdate.append(
              "idOrdenTrabajo",
              document.getElementById("idOtAct").value
            );
            validaRespuestaActualizar(html, false, false);
            let peticionTrealizadas = fetch(
              "php/proceso/ot/trabajos_realizados.php",
              {
                method: "POST",
                body: dataUpdate,
              }
            );
            let peticionInsumos = fetch("php/proceso/ot/insumos_usados.php", {
              method: "POST",
              body: dataUpdate,
            });

            Promise.all(
              [peticionTrealizadas, peticionInsumos].map((prom) =>
                prom.then((res) => res.text())
              )
            ).then((html) => {
              document.getElementById("llegaCambiosRealizados").innerHTML =
                html[0];
              document.getElementById("llegaInsumosOt").innerHTML = html[1];
            });
            let inputsInsertados1 = document.querySelectorAll(
                "[data-clone]#CloneCambiosRealizadosOt"
              ),
              inputsInsertados2 = document.querySelectorAll(
                "[data-clone]#CloneCambiosRepuestosOt"
              ),
              contador = 1,
              contador2 = 1;
            if (html === "true") {
              $("#contenido").load("php/proceso/lista_equipos_contrato.php");
              inputsInsertados1.forEach((e) => {
                if (contador == inputsInsertados1.length) return;
                e.remove();
                contador++;
              });
              inputsInsertados2.forEach((e) => {
                if (contador2 == inputsInsertados2.length) return;
                e.remove();
                contador2++;
              });
              document
                .querySelectorAll("[data-clone] input, [data-clone] select")
                .forEach((e) => (e.value = ""));
            }
          });
      }
    });
  } else {
    alertaCamposVacios();
  }
};

const validaCamposEspecificos = (selector) => {
  let element = document.querySelectorAll(selector);
  let isValidate = true;
  element.forEach((e) => {
    let algunInputllenado = false;
    let inputsValidate = e.querySelectorAll("[data-val2]");
    inputsValidate.forEach((y) => {
      if (y.value) {
        algunInputllenado = true;
      }
    });
    if (algunInputllenado) {
      inputsValidate.forEach((x) => {
        if (!x.value) {
          isValidate = false;
          e.style.setProperty("border", "1px solid red");
          setTimeout(() => {
            e.style.setProperty("border", "");
          }, 2000);
        }
      });
    }
  });
  return isValidate;
};

const actualizaCambioSisEquipo = (datosEquipoMantenimiento) => {
  let [id, descripcion] = datosEquipoMantenimiento.split("|");
  Swal.fire({
    title: `¿Estas seguro de quitar a ${descripcion}?`,
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      verLoader();
      $.ajax({
        url: "php/mantenimiento/equipo/cambio_sis_equipo/actualiza.php",
        type: "POST",
        data: "idEquipoMantenimiento=" + id,
        success: function (response) {
          console.log(response);
          validaRespuestaActualizar(response, false, "modalListadoTipoSistema");
          ocultarLoader();
        },
      });
    }
  });
};
const creaTextAreaPDiarios = (elemento, idllegada) => {
  let textArea = `<textarea name="descripcionEstado" placeholder="Ingrese un descripción del problema" class="form-control form-control-sm mt-3" data-validate rows="2"></textarea>`;
  let elementoLlegada = document.getElementById(idllegada);
  if (elemento.value === "INOPERATIVO") {
    elementoLlegada.innerHTML = textArea;
  } else {
    elementoLlegada.innerHTML = "";
  }
};
const agregaParametroDiario = async (
  idFormulario,
  actualizarEstado = true,
  existeOtActualizar = null
) => {
  if (validar_campos(idFormulario)) {
    let formulario = document.getElementById(idFormulario);
    let selectCambios = document.querySelector(`#${idFormulario} #idTimaPD`),
      primerCambioSistema = "";
    idPrimerCambioSistema = "";
    if (selectCambios !== null) {
      primerCambioSistema =
        selectCambios.options[selectCambios.selectedIndex].text;
      idPrimerCambioSistema = selectCambios.value;
    }
    let idOperador = await obteneridElSeleccionado(
      idFormulario,
      "listaOperadorPDOt"
    );
    if (idOperador === "") return alertaCamposVacios("El trabajador No existe");
    let medicionUltimoMatenimiento = formulario.querySelector(
      "#configMedicionUltMant"
    );
    if (medicionUltimoMatenimiento !== null) {
      let medicionPrincipal = formulario.querySelector(
        "[data-medicionprincipal=true]"
      );
      if (
        parseFloat(medicionPrincipal.value) <
        parseFloat(medicionUltimoMatenimiento.value)
      )
        return alertaCamposVacios(
          "La medicion actual o del ultimo mantenimiento del equipo nos son los correctos"
        );
    }
    verLoader();
    $.ajax({
      url: "php/proceso/parametros_diarios/agrega.php",
      type: "POST",
      data:
        $("#" + idFormulario).serialize() +
        "&idOperador=" +
        idOperador +
        "&primerCambioSistema=" +
        primerCambioSistema +
        "&idPrimerCambioSistema=" +
        idPrimerCambioSistema +
        "&actualizarEstado=" +
        actualizarEstado +
        "&existeOtActualizar=" +
        existeOtActualizar,
      success: function (response) {
        console.log(response);
         /* ocultarLoader();
          return; */
        if (JSON.parse(response)[0] === 0) {
          ocultarLoader();
          return toastPersonalizada(JSON.parse(response)[1], "error", 4000);
        } else {
          let formulario = document.getElementById(idFormulario);
          let dataEquipo = new FormData();
          dataEquipo.append("idEquipo", formulario.idEquipo.value);
          dataEquipo.append("registro", true);
          toastPersonalizada("Agregado con exito!", "success");
          renderizaCamposPD(
            dataEquipo,
            formulario.parentElement.getAttribute("id")
          );
          $("#contenido").load("php/proceso/lista_equipos_contrato.php");
          if (JSON.parse(response)[1] !== null) {
            var left = screen.width / 2 - (window.innerWidth * 0.75) / 2;
            window.open(
              `php/generaPDF/ot/index.php?id=${JSON.parse(response)[1]}`,
              "Orden de trabajo",
              `width=${window.innerWidth * 0.75},height=${
                window.innerHeight
              },margin=0,padding=5,scrollbars=SI,top=80,left=${left}`
            );
          }
        }
        ocultarLoader();
      },
    });
  } else {
    alertaCamposVacios();
  }
};
const actualizaParametroDiario = (idFormulario, actualizarEstado = false) => {
  if (actualizarEstado === false)
    return toastPersonalizada(
      "No es posible actualizar un parametro diario de un contrato anterior",
      "error"
    );
  Swal.fire({
    title: `¿Estas seguro de actualizar?`,
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      verLoader();
      let data = new FormData();
      data.append(
        "idParametroDiario",
        document.getElementById(idFormulario).idParametroDiario.value
      );
      fetch("php/proceso/parametros_diarios/elimina.php", {
        method: "POST",
        body: data,
      })
        .then((res) => res.json())
        .then((json) => {
          if (json[0]) {
            agregaParametroDiario(idFormulario, actualizarEstado, json[1]);
          } else {
            toastPersonalizada("Ocurrio un Error!", "error");
          }
          ocultarLoader();
        });
    }
  });
};

const limitarLongitudNumero = (elemento) => {
  if (elemento.value > 10) {
    elemento.value = "";
    toastPersonalizada("Limite maximo de 10 Horas", "error", 2000);
  }
};
const crearFilaCollapseBHidraulico = (elemento, datos) => {
  let x = datos.split("|");
  let data = new FormData();
  data.append("idEqPrincipal", x[3]);
  data.append("idContrato", x[4]);
  fetch("php/proceso/equipo_secundario.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.json())
    .then((json) => {
      console.log(json);
      var tempDiv = document.createElement("tr");
      var tdCodigo = document.createElement("td");
      tdCodigo.innerText = json.codigo;
      tempDiv.appendChild(tdCodigo);
      elemento.parentElement.insertAdjacentElement("afterend", tempDiv);
    });
};
const agregaOrdenesTrabajo = async () => {
  if (validar_campos("formAddOrdenesTrabajo")) {
    let idContratoEquipo =
      document.querySelector("[data-eqcoaddot]").dataset.eqcoaddot;
    let idEquipo =
      document.querySelector("[data-idequipoie]").dataset.idequipoie;

    let options = document.getElementById("listaTipoSistemas").children;
    let sistemas = document.querySelectorAll(".listaTipoSistemas");
    let idsSistemas = [];
    let validaciones = 0;
    sistemas.forEach((e) => {
      for (const x of options) {
        if (x.value.replace(/\s+/g, "") === e.value.replace(/\s+/g, "")) {
          validaciones++;
          idsSistemas.push(x.dataset.value);
        }
      }
    });
    if (validaciones != sistemas.length)
      return alertaCamposVacios("Algun sistema no es valido");
    event.preventDefault();
    verLoader();
    $.ajax({
      url: "php/proceso/ot/agrega.php",
      type: "POST",
      data:
        $("#formAddOrdenesTrabajo").serialize() +
        "&idContratoEquipo=" +
        idContratoEquipo +
        "&idEquipo=" +
        idEquipo +
        "&idSistemas=" +
        idsSistemas,
      success: function (response) {
        console.log(response);
        let arrayResponse = JSON.parse(response);
        validaRespuestasAgregar(
          arrayResponse[0],
          false,
          "formAddOrdenesTrabajo",
          "modalAddOt"
        );
        let inputsInsertados = document.querySelectorAll(
            "[data-clone]#inputCloneSistemaAddOt"
          ),
          contador = 1;
        if (arrayResponse[0] === "true") {
          activarAlertaActOt(arrayResponse[1]);
          inputsInsertados.forEach((e) => {
            if (contador == inputsInsertados.length) return;
            e.remove();
            contador++;
          });
        }
        ocultarLoader();
      },
    });
  } else {
    alertaCamposVacios();
  }
};

const activarAlertaActOt = (idOrdenTrabajo) => {
  Swal.fire({
    title: "Agregado con exito!",
    icon: "success",
    showCancelButton: true,
    confirmButtonText: "Editar OT creada",
    cancelButtonText: "Cerrar",
    showLoaderOnConfirm: true,
    preConfirm: (login) => {
      llenarDatosOTAct(idOrdenTrabajo);
      $("#modalActOt").modal("show");
    },
    allowOutsideClick: () => !Swal.isLoading(),
  });
};

const sumarDosCampos = (element) => {
  let nodoPadre = element.parentElement.parentElement,
    cantidad = nodoPadre.querySelector(".cantidad").value || 0,
    precio = nodoPadre.querySelector(".precio").value || 0;
  campoResultado = nodoPadre.querySelector(".resultado");

  let resultado = parseFloat(cantidad) * parseFloat(precio);
  campoResultado.value = resultado;
};

const abreConfirmacionEquipoCont = (
  datosEquipo,
  tipoActualizacion,
  textoAlerta
) => {
  let x = datosEquipo.split("|");
  let botonActEQCO = document.getElementById("dataConfigActEQCO");
  botonActEQCO.dataset.acteqco = datosEquipo;
  botonActEQCO.dataset.acteqtipo = tipoActualizacion;
  $("#modalconfirmacionCierreContrato").modal("show");
  if (tipoActualizacion === "finalizacion") {
    document.getElementById("llegaContenidoConfirmEQCO").innerHTML = `
    <div class="row">
    <h5 class="text-center">Ingrese la fecha de salida del equipo ${x[3]}</h5>
    <input type="date" id="fechaCierreOTInput" class="form-control mt-2">
   <div>`;
  } else {
    document.getElementById("llegaContenidoConfirmEQCO").innerHTML = `
    <div class="row">
    <h5 class="text-center">¿Esta seguro de eliminar a ${x[3]}?</h5>
   <div>`;
  }
};
const actualizaEquipoCont = () => {
  let fechaCierre = null;
  if (document.getElementById("fechaCierreOTInput") != null) {
    if (
      document.getElementById("fechaCierreOTInput").value === "" ||
      document.getElementById("fechaCierreOTInput").value === null
    ) {
      return toastPersonalizada("La fecha de cierre es obligatoria!", "error");
    } else {
      fechaCierre = document.getElementById("fechaCierreOTInput").value;
    }
  }
  let datosEquipo =
    document.getElementById("dataConfigActEQCO").dataset.acteqco;
  let tipoActualizacion =
    document.getElementById("dataConfigActEQCO").dataset.acteqtipo;
  let x = datosEquipo.split("|");
  verLoader();
  $.ajax({
    url: "php/proceso/contrato/equipo_contrato/actualiza.php",
    type: "POST",
    data:
      "idEquipoContrato=" +
      x[0] +
      "&idEquipo=" +
      x[1] +
      "&idContrato=" +
      x[2] +
      "&tipoAccion=" +
      tipoActualizacion +
      "&fechaCierre=" +
      fechaCierre,
    success: function (response) {
      console.log(response);
      validaRespuestaActualizar(
        response,
        "php/proceso/contrato/lista_proyecto.php",
        "modalListadoEquiposCon"
      );
      $("#modalconfirmacionCierreContrato").modal("hide");
      ocultarLoader();
    },
  });
};

const llenarDatosContrato = (datosContrato) => {
  let x = datosContrato.split("|");
  document.getElementById("idConAct").value = x[0];
  document.getElementById("descConAct").value = x[1];
  document.getElementById("numConAct").value = x[2];
  document.getElementById("proyConAct").value = x[4];
  document.getElementById("cliConAct").value = x[3];
  document.getElementById("fechIConAct").value = x[5];
  document.getElementById("fechFConAct").value = x[6];
  document.getElementById("estConAct").value = x[7];
};

const actualizaContratos = () => {
  if (validar_campos("formActContrato")) {
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
          url: "php/proceso/contrato/actualiza.php",
          data: $("#formActContrato").serialize(),
          success: function (response) {
            let responseParsed = JSON.parse(response);
            if (responseParsed[0] === "true") {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Actualizado con exito!",
                showConfirmButton: false,
                timer: 1500,
              });
              $.ajax({
                type: "POST",
                url: "php/proceso/contrato/lista_contrato.php",
                data: "idProyecto=" + JSON.parse(response)[1],
                success: function (respuesta) {
                  $("#llegaListadoContrato").html(respuesta);
                  $("#modalActContrato").modal("hide");
                },
              });
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "Fallo al Actualizar!",
                showConfirmButton: false,
                timer: 1500,
              });
              if (responseParsed[0] !== null) {
                toastPersonalizada(responseParsed[1], "error", 3500);
              }
            }
            ocultarLoader();
          },
        });
      }
    });
  } else {
    alertaCamposVacios();
  }
};

const clonarElemento = (idElemento) => {
  let elemento = document.getElementById(idElemento);
  let clone = elemento.cloneNode(true);
  clone.querySelectorAll("input").forEach((e) => (e.value = ""));
  elemento.parentElement.insertAdjacentElement("beforeend", clone);
};
const QuitarElemento = (elemento) => {
  let cloneElement = elemento.parentElement.parentElement.parentElement;
  let numeroInputs =
    cloneElement.parentElement.querySelectorAll(".row[data-clone]");
  //console.log(cloneElement, numeroInputs);
  if (numeroInputs.length > 1) {
    cloneElement.remove();
  }
};

const vistaEstadisticaEquipo = async (idContratoEquipo, idEquipo = null) => {
  if (idEquipo === null) {
    idEquipo = await obteneridElSeleccionado(
      "formEquipoContrEsta",
      "listaEquiposContrato"
    );
    if (idEquipo === "")
      return alertaCamposVacios("El equipo y contrato no existe");
  }
  $.ajax({
    type: "POST",
    url: "php/proceso/index_equipo.php",
    data: "idEquipo=" + idEquipo + "&idContratoEquipo=" + idContratoEquipo,
    success: function (response) {
      $("#contenido").html(response);
      Tooltips();
      ocultarLoader();
    },
  });
};

const dragAndDRopImg = () => {
  let drag = document.getElementById("cajaDragOts");

  drag.addEventListener("dragover", (e) => {
    e.preventDefault(); //necesario sino no funiona drop
    drag.classList.add("activeDrag");
  });
  drag.addEventListener("dragleave", (e) => {
    drag.classList.remove("activeDrag");
  });
  drag.addEventListener("drop", (e) => {
    drag.classList.remove("activeDrag");
    //drag.style.setProperty("opacity","0");
    e.preventDefault();
    let archivos = e.dataTransfer.files;
    console.log(e.dataTransfer.files);
    sessionStorage.setItem("imgsOt", JSON.stringify(archivos));
    console.log(JSON.parse(sessionStorage.getItem("imgsOt")));
    mostrarImagenes(archivos);
  });
};
const cargarVisualizarImagenesOt = () => {
  let drag = document.getElementById("cajaDragOts");
  let filesImg = document.getElementById("imgsOt").files;
  mostrarImagenes(filesImg);

  //drag.style.setProperty("opacity","0");
};
const mostrarImagenes = (filesImg) => {
  let plantilla = document.getElementById("templateImgOt").content,
    fragmento = document.createDocumentFragment(),
    contenedor = document.getElementById("llegaImagenesOt");
  contenedor.innerHTML = "";
  for (const x of filesImg) {
    let lectorImagen = new FileReader();
    lectorImagen.addEventListener("loadend", () => {
      //console.log("termine de ller",lectorImagen);
      plantilla.querySelector("img").src = lectorImagen.result;
      let clone = document.importNode(plantilla, true);
      fragmento.appendChild(clone);
      contenedor.appendChild(fragmento);
    });
    if (x) {
      lectorImagen.readAsDataURL(x);
    } else {
      html = `<img src="" class="img-fluid" alt="">`;
    }
  }
};

const verPdfOrdenTrabajo = (elemento) => {
  let id = elemento.dataset.idotpdf,
    ruta = "php/generaPDF/ot/index.php";
  var left = screen.width / 2 - (window.innerWidth * 0.75) / 2;
  console.log(window.innerWidth * 0.75);
  window.open(
    `${ruta}?id=${id}`,
    "Orden de trabajo",
    `width=${window.innerWidth * 0.75},height=${
      window.innerHeight
    },margin=0,padding=5,scrollbars=SI,top=80,left=${left}`
  );
};

const verPdfEquiposCambio = (idEquipoMantenimiento) => {
  verLoader();
  $.ajax({
    url: "php/mantenimiento/equipo/equipo_mantenimiento/ver_documento.php",
    type: "POST",
    data: "idEquipoMantenimiento=" + idEquipoMantenimiento,
    success: function (response) {
      document.getElementById("llegaPdfMantenimientoEquipo").innerHTML =
        response;
      ocultarLoader();
    },
  });
};

function cargarControlesReport() {
  verLoader();
  $.ajax({
    url: "php/ordenes/reporte/controles_reporte.php",
    success: function (response) {
      $(`#contenido`).html(response);
      let fechaActual = new Date(),
        anio = fechaActual.getFullYear(),
        mes = fechaActual.getMonth() + 1;
      mes;
      mes = mes < 10 ? `0${mes}` : `${mes}`;
      let primerDiaAnio = `${anio}-${mes}-01`;
      let ultimoDiaAnio = `${anio}-${mes}-31`;
      document.getElementById("PrimerDiaReporte").value = primerDiaMes;
      document.getElementById("ultimoDiaReporte").value = ultimoDiaMes;
      ocultarLoader();
    },
  });
}

const llenaHistorialEC = () => {
  fetch("php/equipo/dataList_equipos.php")
    .then((res) => res.text())
    .then((html) => {
      document.getElementById("llegadaListHistorialEC").innerHTML = html;
    });
  let data = new FormData();
  data.append("codigoEquipo", "data-falsa");
  fetch("php/proceso/contrato/tabla_historial_equipo.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.text())
    .then((html) => {
      $("#llegaTablaHEquipos").html(html);
    });
};
const buscarHistorialEquipo = () => {
  let inputCodigoEquipo = document.getElementById("codigoEquipoHE");
  if (inputCodigoEquipo.value === "")
    return toastPersonalizada("Completa todos los campos", "error");
  let data = new FormData();
  data.append("codigoEquipo", inputCodigoEquipo.value);
  fetch("php/proceso/contrato/tabla_historial_equipo.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.text())
    .then((html) => {
      if (html === "0") {
        toastPersonalizada("El codigo del equipo es incorrecto", "error");
      } else {
        $("#llegaTablaHEquipos").html(html);
      }
    });
};

const agregarImagenesOt = async () => {
  let imagenSesionStorage = sessionStorage.getItem("imagenOtGYT");
  imagen = new FormData();
  if (imagenSesionStorage === null)
    return alertaCamposVacios("No hay una imagen insertada");
  imagen.append("imagen", imagenSesionStorage);
  imagen.append("idOt", document.getElementById("idOtAddImg").value);
  try {
    let peticion = await fetch("php/proceso/ot/agrega_imagenes.php", {
      method: "POST",
      body: imagen,
    });
    if (!peticion.ok)
      throw {
        estado: peticion.status,
      };
    let json = await peticion.text();
    validaRespuestasAgregar(json, false, "formAgregaimgOt", false);

    let data2 = new FormData();
    data2.append("idOrdenTrabajo", document.getElementById("idOtAddImg").value);
    fetch("php/proceso/ot/ver_imagenes.php", {
      method: "POST",
      body: data2,
    })
      .then((res) => res.text())
      .then((html) => {
        document.getElementById("llegaImagenesOtAct").innerHTML = html;
        document.getElementById("llegaImagenesOt").innerHTML = "";
        document.getElementById("llegaImagenesOt").classList.add("d-none");
        sessionStorage.removeItem("imagenOtGYT");
      });
  } catch (error) {
    console.log("error " + error.estado);
  }
};
const eliminarImagenOT = (idImagen) => {
  Swal.fire({
    title: "¿Estas seguro de eliminar esta imagen?",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      let data = new FormData();
      data.append("idImagen", idImagen);
      fetch("php/proceso/ot/quitar_imagen.php", {
        method: "POST",
        body: data,
      })
        .then((res) => res.json())
        .then((json) => {
          if (json) {
            let data2 = new FormData();
            data2.append(
              "idOrdenTrabajo",
              document.getElementById("idOtAddImg").value
            );
            fetch("php/proceso/ot/ver_imagenes.php", {
              method: "POST",
              body: data2,
            })
              .then((res) => res.text())
              .then((html) => {
                document.getElementById("llegaImagenesOtAct").innerHTML = html;
              });
          }
        });
    }
  });
};

const funcionalidadPegarImg = () => {
  document.getElementById("imgsOt").focus();
  document.getElementById("imgsOt").addEventListener("paste", () => {
    // use event.originalEvent.clipboard for newer chrome versions
    var items = (event.clipboardData || event.originalEvent.clipboardData)
      .items;
    console.log(JSON.stringify(items)); // will give you the mime types
    // find pasted image among pasted items
    var blob = null;
    for (var i = 0; i < items.length; i++) {
      if (items[i].type.indexOf("image") === 0) {
        blob = items[i].getAsFile();
      }
    }
    // load image if there is a pasted image
    if (blob !== null) {
      var reader = new FileReader();
      reader.onload = function (event) {
        console.log(event.target.result); // data url!
        document.getElementById("llegaImagenesOt").classList.remove("d-none");
        document.getElementById("llegaImagenesOt").src = event.target.result;
        sessionStorage.setItem("imagenOtGYT", event.target.result);
      };
      reader.readAsDataURL(blob);
    }
  });
};

const verListaRoles = (idTrabajador) => {
  let data = new FormData();
  data.append("idTrabajador", idTrabajador);
  fetch("php/mantenimiento/general/roles/lista.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.text())
    .then((html) => {
      $("#llegaListaRolesTrabajador").html(html);
    });
};

const elimina_rol = (idRol, idTrabajador) => {
  Swal.fire({
    title: "¿Estas seguro de eliminar este rol?",
    text: "el usuario ya no podra acceder a este modulo!",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      verLoader();
      let data = new FormData();
      data.append("idRol", idRol);
      fetch("php/mantenimiento/general/roles/eliminaRol.php", {
        method: "POST",
        body: data,
      })
        .then((res) => res.json())
        .then((json) => {
          if (json === true) {
            alertaPersonalizada("Eliminado con exito!", "success");
            verListaRoles(idTrabajador);
          } else {
            alertaPersonalizada("Fallo al eliminar", "error");
          }
          ocultarLoader();
        });
    } else {
      document.getElementById("rolCheck").checked = true;
    }
  });
};

const agregaRoles = async () => {
  let formulario = document.getElementById("formAgregaRol");
  let idUsuario = await obteneridElSeleccionado(
    "formAgregaRol",
    "listaTrabRoles"
  );
  if (idUsuario === "") return alertaCamposVacios("El trabajador No existe");
  let listaChecks = document.querySelectorAll(".rolCheckAdd");
  console.log(listaChecks);
  let existeCkecks = false;
  listaChecks.forEach((e) => {
    if (e.checked) {
      existeCkecks = true;
    }
  });
  if (!existeCkecks)
    return toastPersonalizada("Debe seleccionar por lo menos un rol", "error");
  let data = new FormData(formulario);
  data.append("idTrabajadorRol", idUsuario);

  fetch("php/mantenimiento/general/roles/agrega.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.json())
    .then((json) => {
      if (json[0]) {
        console.log(json[0]);
        alertaPersonalizada("Agregado con exito!", "success");
        formulario.reset();
      } else {
        if (json[1] === null) {
          alertaPersonalizada("Fallo al agregar!", "error");
        } else {
          let sistemasRepetidos = "";
          json[1].forEach((element) => {
            sistemasRepetidos += `${element},`;
          });
          let texto = "";
          texto =
            json[1].length > 1
              ? "ya fueron registrados."
              : "Ya fue registrado.";
          toastPersonalizada(
            `${sistemasRepetidos.slice(0, -1)} ${texto}`,
            "error"
          );
        }
      }
    });
};

const verHistorialMedidores = (medicionActual, tipoMedicion, idEquipo) => {
  let data = new FormData();
  data.append("idEquipo", idEquipo);
  data.append("medicionActual", medicionActual);
  data.append("tipoMedicion", tipoMedicion);
  fetch("php/proceso/parametros_diarios/historialMedidores.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.text())
    .then((html) => {
      document.getElementById("llegadaTablaHistorialPD").innerHTML = html;
    });
};
const sumaActmedicionActual = (elemento, idInputGeneral) => {
  let medicionAnterior = parseFloat(elemento.dataset.med_actual_val);
  let medicionAnteriorGeneral = parseFloat(
    document.getElementById(idInputGeneral).dataset.med_general_val
  );
  let medicionActual = parseFloat(elemento.value);
  let diferencia = medicionActual - medicionAnterior;
  document.getElementById(idInputGeneral).value =
    medicionAnteriorGeneral + diferencia;
};
/* ayuda */

const verDocumentosAyuda = (elemento) => {
  agregarZindexModales("modalVerListaDocumentos");
  verListaDocumentos(elemento.dataset.idequipoayuda, false);
};
const verInfoAyuda = (elemento) => {
  agregarZindexModales("modalDetalleEquipo");
  verDetalleEquipo(elemento.dataset.idequipoayuda);
};

const agregarZindexModales = (idModal) => {
  let modalAyuda = document.getElementById("modalAyudaSecciones");
  if (modalAyuda.classList.contains("show")) {
    document.getElementById(idModal).classList.add("z-index-9999");
    document.getElementById("modalActOt").classList.add("z-index-10000");
    document
      .getElementById("modalEnviarAdjunto")
      .classList.add("z-index-10000");
  }
  //general
};
const quitarZindexModales = (idModal) => {
  let modalActual = document.getElementById(idModal);
  if (modalActual.classList.contains("z-index-9999")) {
    document.getElementById(idModal).classList.remove("z-index-9999");
  }
  //general
  document
    .getElementById("modalEnviarAdjunto")
    .classList.remove("z-index-10000");
  document.getElementById("modalActOt").classList.remove("z-index-10000");
};

/* fin ayuda */
