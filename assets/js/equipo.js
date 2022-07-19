const agregarEquipo = (arrayForms,idModal) => {
    if (document.getElementById("checkEquipo").checked) {
        if (!validar_campos(arrayForms[0])) return alertaCamposVacios()
        if (!validar_campos(arrayForms[1])) return alertaCamposVacios()
        Swal.fire({
            title: `Agregaras los equipos ${document.getElementById(arrayForms[0]).codigo.value} y ${document.getElementById(arrayForms[1]).codigo.value}`,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
        arrayForms.forEach(e => {
            let datosFormulario = new FormData(document.getElementById(e));
                envioAgregarEquipo(e, datosFormulario,idModal)
        });
        let linkEquipoSecundario = document.getElementById("linkNuevoEquipo");
        document.getElementById("collapseExample").classList.remove("show")
        linkEquipoSecundario.setAttribute("data-bs-target","")
        linkEquipoSecundario.classList.remove("text-blue-gyt")
        linkEquipoSecundario.classList.add("text-secondary")
        document.getElementById("checkEquipo").checked = false
    }
})
    } else {
        if (!validar_campos(arrayForms[0])) return alertaCamposVacios()
        Swal.fire({
            title: `Agregaras el equipo ${document.getElementById(arrayForms[0]).codigo.value}`,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let datosFormulario = new FormData(document.getElementById(arrayForms[0]));
                envioAgregarEquipo(arrayForms[0], datosFormulario,idModal)
            }
        })
    }

}
const envioAgregarEquipo = async (idFormulario, datosFormulario,idModal) => {
    verLoader();
    try {
        let peticion = await fetch("php/equipo/agrega.php", {
            method: "POST",
            body: datosFormulario,
        });
        let response = await peticion.text();
        if (peticion.ok) {
            console.log('response', response)
            validaRespuestasAgregar(response, "php/equipo/tabla.php",idFormulario,idModal);
            ocultarLoader();
        } else {
            throw "no se pudo concretar la peticion"
        }
    } catch (error) {
        console.log(error);
    }
}
const llenarDatosDocEquipo = (dato) => {
    let x = dato.split("|");
    document.getElementById("idDoEqAct").value = x[0];
    document.getElementById("equipoDoEqAct").value = x[1];
    document.getElementById("descripcionDoEqAct").value = x[2];
    document.getElementById("vencimientoDocEqAct").value = x[3];
    document.getElementById("estadoDoEqAct").value = x[4];
}

function verDetalleEquipo(idEquipo) {
    let llegadaDescripcion = document.getElementById("llegaDatosSegundoEquipo"),
        idTamanioModal = document.getElementById("modalDescSegundoEq");
    $("#modalDetalleEquipo").modal("show")
    let detSuperEstructura;
        document.getElementById("llegaDatosSegundoEquipo").innerHTML=" ";
    let dato = new FormData();  
    dato.append("idEqPrincipal",idEquipo)
        fetch("php/equipo/equipo_secundario.php",{
            method:"POST",
            body:dato,
        })
        .then(response => response.json())
        .then(json => {
            if (json.length<=0) {
                idTamanioModal.classList.remove("modal-lg");
                idTamanioModal.classList.add("modal-md");

                llegadaDescripcion.parentElement.classList.remove("col")
                llegadaDescripcion.innerHTML= "";
            }else{
                idTamanioModal.classList.add("modal-lg");
                idTamanioModal.classList.remove("modal-md");
                llegadaDescripcion.parentElement.classList.add("col")
                let detSuperEstructura = `
                <div class="card shadow">
                    <h6 class="modal-title mx-auto my-2">${json[0]}-${json[1]}</h6><hr class="mt-0">
                    <div class="table-resposive">
                    <table class="table table-borderless table-sm">
                    <tr><td> <span class="fw-bold">Placa / Serie</span></td><td>${json[5]}</td></tr>
                    <tr><td> <span class="fw-bold">Marca</span></td><td>${json[3]}</td></tr>
                    <tr><td> <span class="fw-bold">A. de fabricación</span></td><td >${json[6]}</td></tr>
                    <tr><td> <span class="fw-bold">Capacidad</span></td><td >${json[7]}</td></tr>   
                    <tr><td> <span class="fw-bold">Modelo del motor</span></td><td>${json[4]}</td></tr>
                    <tr><td> <span class="fw-bold">Marca de motor</span></td><td>${json[11]}</td></tr>
                    <tr><td> <span class="fw-bold">N° de motor</span></td><td>${json[13]}</td></tr>
                    <tr><td> <span class="fw-bold">Tipo de medición</span></td><td >${json[12]}</td></tr>
                    </table>
                    </div></div>`;
                document.getElementById("llegaDatosSegundoEquipo").innerHTML= detSuperEstructura; 
            }
        })
        let data = new FormData();
        data.append("idEquipo",idEquipo)
        fetch("php/equipo/listaEquipos.php", {
            method:"POST",
            body:data
        })
        .then(res => res.json())
        .then(json => {
            document.getElementById("codigoEqDet").textContent = `${json[1]}-${json[15]}`;
            document.getElementById("marcaEqDet").textContent = json[20];
            document.getElementById("motorEqDet").textContent = json[7];
            document.getElementById("fabricacionEqDet").textContent = json[8];
            document.getElementById("chasisEqDet").textContent = json[10];
            document.getElementById("ingresoEqDet").textContent = json[13];
            document.getElementById("salidaEqDet").textContent = json[14];
            document.getElementById("centroCostoEqDet").textContent = json[17];
            document.getElementById("capacidadEqDet").textContent = json[11];
            document.getElementById("medicionEqDet").textContent = json[19];
            document.getElementById("placaEqDet").textContent = json[5];
            document.getElementById("modeloEqDet").textContent = json[4];
            document.getElementById("modeloMotorEqDet").textContent = json[6];
            document.getElementById("marcaMotorEqDet").textContent = json[18];
            document.getElementById("idEquipoVerDetayu").dataset.idequipo=idEquipo;
        })
    
      
}

const llenarDatosEquipo = (dato) => {
    let x = dato.split("|");
    let llegadaCamposDTexto = document.getElementById("llegadaDatosActEquipoSec")
    let id = new FormData();  
    id.append("idEqPrincipal",x[0])
        fetch("php/equipo/equipo_secundario.php",{
            method:"POST",
            body:id,
        })
        .then(response => response.json())
        .then(json => {
            let listaInputs = llegadaCamposDTexto.querySelectorAll("input"),
                listaSelects = llegadaCamposDTexto.querySelectorAll("select");
            if (json.length<=0) {
                llegadaCamposDTexto.style.display="none";
                listaInputs.forEach((e) => e.removeAttribute("data-validate"))
                listaSelects.forEach((e) => e.removeAttribute("data-validate"))
                document.getElementById("linkNuevoEquipo2").style.display="block";
                document.getElementById("propietarioEquipo3").value=x[12];
                document.getElementById("equipoPrincipal").value=x[0];
                document.getElementById("collapseExample2").classList.remove("show");
                generaCodigoEquipo("propietarioEquipo3", "codigoEquipo3", "familiaEquipo3")

            }else{
                let detSuperEstructura = ``;
                document.getElementById("collapseExample2").classList.remove("show");
                llegadaCamposDTexto.style.display="block";
                listaInputs.forEach((e) => e.setAttribute("data-validate",""))
                listaSelects.forEach((e) => e.setAttribute("data-validate",""))
                document.getElementById("linkNuevoEquipo2").style.display="none";
                document.getElementById("codigoEqAct2").value = json[0];
                document.getElementById("descFamilia2").value = json[1];
                document.getElementById("marcaEqAct2").value = json[9];
                document.getElementById("marcaMotorEqAct2").value = json[11];
                document.getElementById("modeloMotorAct2").value = json[4];
                document.getElementById("numeroMotorEqAct2").value = json[13];
                document.getElementById("placaEqAct2").value = json[5];
                document.getElementById("capacidadEqAct2").value = json[7];
                document.getElementById("tMedicionEqAct2").value = json[12];
               /*  document.getElementById("propietarioEquipo3").value=x[2]; */
                document.getElementById("fabricacionEqAct2").value = json[6];
            }
        })
    document.getElementById("idEqAct").value = x[0];
    document.getElementById("codigoEqAct").value = x[1];
    document.getElementById("equipoTiEqAct").value = x[2];
    document.getElementById("marcaEqAct").value = x[3];
    document.getElementById("modeloEqAct").value = x[4];
    document.getElementById("placaEqAct").value = x[5];
    document.getElementById("marcaMotorEqAct").value = x[18];
    document.getElementById("modeloMotorEqAct").value = x[6];
    document.getElementById("numeroMotorEqAct").value = x[7];
    document.getElementById("fabricacionEqAct").value = x[8];
    /* document.getElementById("fabricacionPEqAct").value = x[9] */
    document.getElementById("chasisEqAct").value = x[10];
    document.getElementById("capacidadEqAct").value = x[11];
    document.getElementById("tMedicionEqAct").value = x[19];
    document.getElementById("propietarioEqACt").value = x[12];
    document.getElementById("ingresoEqAct").value = x[13];
    document.getElementById("salidaEqAct").value = x[14];
    document.getElementById("descFamilia").value = x[15];
    document.getElementById("descPropietarioAct").value = x[16];
    document.getElementById("centroCostoAct").value = x[17];
    document.getElementById("estadoEqAct").value = 1;
}

const llenarDatosImgEquipo = (dato) => {
    let x = dato.split("|");
    document.getElementById("idImgEqAct").value = x[0];
    document.getElementById("equipoImgEqAct").value = x[1];
    document.getElementById("descripcionImgEqAct").value = x[2];
    document.getElementById("estadoImgEqAct").value = 1;
}

const llenarDatosAlertaEquipo = (regular, mala) => {
    document.getElementById("alertaAmbar").value = regular;
    document.getElementById("alertaRoja").value = mala;
}
const activarLinkEquipo = (elemento) => {
    let linkNuevoEquipo = document.getElementById("linkNuevoEquipo");
    if (linkNuevoEquipo.getAttribute("data-bs-target") === "") {
        linkNuevoEquipo.setAttribute("data-bs-target", "#collapseExample")
        linkNuevoEquipo.classList.remove("text-secondary");
        linkNuevoEquipo.classList.add("text-blue-gyt");
    } else {
        linkNuevoEquipo.setAttribute("data-bs-target", "")
        linkNuevoEquipo.classList.remove("text-blue-gyt");
        linkNuevoEquipo.classList.add("text-secondary");
    }
}
const llenarDatosAddEquipo = (dato) => {
    /* funcionalidad al tooltip */
    var tootipFormEquipo = document.querySelector('.tootipFormEquipo')
    var tooltip = new bootstrap.Tooltip(tootipFormEquipo, {
        Animation: true
    })
    /*  id,codigo,descripcion */
    let x = dato.split("|");
    document.getElementById("correlativoequipo1").value = x[0];
    document.querySelectorAll("[data-id]").forEach(e => {e.value=x[0]});
    document.getElementById("codigoEquipo1").value = "";

    generaCodigoEquipo("propietarioEquipo1", "codigoEquipo1", "familiaEquipo1")
    generaCodigoEquipo("propietarioEquipo2", "codigoEquipo2", "familiaEquipo2")
    
}
const generaCodigoEquipo = (inputPropietario, inputResult, familiaEquipo) => {
    let propietario = document.getElementById(inputPropietario),
        result = document.getElementById(inputResult),
        equipo = document.getElementById(familiaEquipo);
    propietario.addEventListener("change", (e) => {
        document.getElementById("propietarioEquipo2").value=propietario.value;
        peticcionCorrelativo(e, familiaEquipo, result, propietario)
    })
    equipo.addEventListener("change", (e) => {
        /* equipo.dataset.inicial= */
        document.getElementById("propietarioEquipo2").value=propietario.value;
        peticcionCorrelativo(e, familiaEquipo, result, propietario)
    })
}
const peticcionCorrelativo = async (e, equipo, result, propietario) => {
    try {
        let data = new FormData();
        data.append("familia",document.getElementById(equipo).value)
        let correlativo
        let peticion = await fetch("php/equipo/codigo_correlativo.php",{
            method:"POST",
            body:data,
        });
        if (!peticion.ok) throw {
            estado: peticion.status,
            texto: peticion.statusText
        };
        let ultimoID = await peticion.text(); 
        /* if (document.getElementById("checkEquipo").checked && result.value !== " ") {
            ultimoID = Number.parseInt(ultimoID) + 1;
        } */
        let iteracion = 4 - ultimoID.toString().length,
            ceros = "";
        for (let i = 0; i < iteracion; i++) {
            ceros += "0"
        }
        correlativo = `${ceros}${ultimoID}`
        let objCodigo = {
            "propietario": propietario.options[propietario.selectedIndex].text,
            correlativo
        };
        let option = document.getElementById(equipo);
        (propietario.value === "") ? objCodigo.propietario = "": objCodigo.propietario = objCodigo.propietario;
        let codigoFinal = `${objCodigo.propietario}${option.options[option.selectedIndex].dataset.inicial}${objCodigo.correlativo}`
       /*  let codigoFinal2 = `${objCodigo.propietario}${option.options[option.selectedIndex].dataset.inicial}${ceros}${Number.parseFloat(objCodigo.correlativo)+1}` */
        /* document.getElementById("codigoEquipo2").value=codigoFinal2; */
        result.value = codigoFinal;

    } catch (error) {
        console.log(error.estado || error);
    }
}
const actualizaAlertaEquipos = () => {
    if (validar_campos("formAlertaEquiposAct")) {
        Swal.fire({
            title: '¿Estas seguro de actualizar?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                verLoader();
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "php/mantenimiento/equipo/alerta_doc_equipo/actualiza.php",
                    data: $("#formAlertaEquiposAct").serialize(),
                    success: function (response) {
                        validaRespuestaActualizar(response, "php/mantenimiento/equipo/alerta_doc_equipo/vista.php", "modalAlertaDocEquipo");
                        ocultarLoader();
                    }
                });
            }
        })
    } else {
        alertaCamposVacios()
    }
}

const actualizaEquipos = () => {
    if (validar_campos("formEquipoAct")) {
        Swal.fire({
            title: '¿Estas seguro de actualizar?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                verLoader();
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "php/equipo/actualiza.php",
                    data: $("#formEquipoAct").serialize(),
                    success: function (response) {
                        validaRespuestaActualizar(response, "php/equipo/tabla.php", "modalEquipoAct");
                        ocultarLoader();
                    }
                });
            }
        })
    } else {
        alertaCamposVacios()
    }
}

const actualizaMarcas = () => {
    if (validar_campos("formMarcaAct")) {
        Swal.fire({
            title: '¿Estas seguro de actualizar?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                verLoader();
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "php/mantenimiento/equipo/marca/actualiza.php",
                    data: $("#formMarcaAct").serialize(),
                    success: function (response) {
                        validaRespuestaActualizar(response, "vistas/formularios/agrega_marca.html", "modalMarcaAct");
                        ocultarLoader();
                    }
                });
            }
        })
    } else {
        alertaCamposVacios()
    }
}

const agregar_documento_equipos = () => {
    if (validar_campos("formAgregaDocEquipo")) {
        event.preventDefault();
        verLoader();
        $.ajax({
            url: "php/equipo/documento/agrega.php",
            type: "POST",
            data: new FormData($('#formAgregaDocEquipo')[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                validaRespuestasAgregar(response, "vistas/formularios/agrega_doc_equipo.php")
                ocultarLoader();
            }
        });

    } else {
        alertaCamposVacios()
    }
}

const ListaDocumentosPreliminar = (elemento) => {
    if(elemento.value === "") return;
        verLoader();
        $.ajax({
            url: "php/equipo/documento/lista_preliminar.php",
            type: "POST",
            data:"codigoEquipo="+elemento.value,
            success: function (response) {
                if(response==="5") {
                    alertaCamposVacios("El codigo del equipo es incorrecto")
                    document.getElementById("llegaListaEquipos").innerHTML="";
                }else{
                    $("#llegaListaEquipos").html(response);
                }
                ocultarLoader();
            }
        });

}

document.addEventListener('focusout', (e) => {
    if (e.target.matches("#equipoVistaPre")) {
        ListaDocumentosPreliminar(e.target)
    }
  });

const agregar_imagen_equipos = () => {
    if (validar_campos("formAgregaImgEquipo")) {
        event.preventDefault();
        verLoader();
        $.ajax({
            url: "php/equipo/imagen/agrega.php",
            type: "POST",
            data: new FormData($('#formAgregaImgEquipo')[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                validaRespuestasAgregar(response, "vistas/formularios/agrega_img_equipo.php")
                ocultarLoader();
            }
        });

    } else {
        alertaCamposVacios()
    }
}


const actualizaDocEquipos = () => {
    if (validar_campos("formDocEquipoAct")) {
        Swal.fire({
            title: '¿Estas seguro de actualizar?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                event.preventDefault();
                verLoader();
                $.ajax({
                    url: "php/equipo/documento/actualiza.php",
                    type: "POST",
                    data: new FormData($('#formDocEquipoAct')[0]),
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (JSON.parse(response)[0] === "true") {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Actualizado con exito!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $.ajax({
                                type: "POST",
                                url: "php/equipo/documento/lista_documentos.php",
                                data: "idEquipo=" + JSON.parse(response)[1],
                                success: function (respuesta) {
                                    $("#llegaListaDocumentos").html(respuesta)
                                    $("#modalDocEquipoAct").modal("hide");
                                    ocultarLoader();
                                }
                            });

                        } else {
                            ocultarLoader();
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Fallo al Actualizar!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                });
            }
        })
    } else {
        alertaCamposVacios()
    }
}


const actualizaImgEquipos = () => {
    if (validar_campos("formImgEquipoAct")) {
        Swal.fire({
            title: '¿Estas seguro de actualizar?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                event.preventDefault();
                verLoader();
                $.ajax({
                    url: "php/equipo/imagen/actualiza.php",
                    type: "POST",
                    data: new FormData($('#formImgEquipoAct')[0]),
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        ocultarLoader();
                        if (JSON.parse(response)[0] === "true") {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Actualizado con exito!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $.ajax({
                                type: "POST",
                                url: "php/equipo/imagen/lista_imagenes.php",
                                data: "idEquipo=" + JSON.parse(response)[1],
                                success: function (respuesta) {
                                    
                                    $("#llegaListaImagenes").html(respuesta)
                                    $("#modalImgEquipoAct").modal("hide");
                                }
                            });

                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Fallo al Actualizar!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }

                    }
                });
            }
        })
    } else {
        alertaCamposVacios()
    }
}

const verDocEquipo = (idEquipo) => {
    verLoader();
    $.ajax({
        url: "php/equipo/documento/ver_documento.php",
        type: "POST",
        data: "idEquipo=" + idEquipo,
        success: function (response) {
            document.getElementById("llegaDocumentoEquipo").innerHTML = response;
            ocultarLoader()
        }
    });
}
const verListaImagenes = (idEquipo) => {
    $.ajax({
        type: "POST",
        url: "php/equipo/imagen/lista_imagenes.php",
        data: "idEquipo="+idEquipo,
        success: function (response) {
            $("#llegaListaImagenes").html(response)
        }
    });
}

const verListaDocumentos = (idEquipo = false,actualizar = true) => {
    if (idEquipo !== false) {
        $("#modalVerListaDocumentos").modal("show")
    }
    $.ajax({
        type: "POST",
        url: "php/equipo/documento/lista_documentos.php",
        data: "idEquipo="+idEquipo+"&actualizar="+actualizar,
        success: function (response) {
            if (idEquipo !== false) {
                
                $("#llegaListaDocumentos").html(response)
            }else {
                
                $("#llegadaEnviosMasivos").html(response)
            }
        }
    });
}

const modalEnvioAdjunto = () => {
    let listaEquipos = "";
    let cont = 0;
    let inputCheck = document.querySelectorAll("[data-check]");
    inputCheck.forEach(e => {
        if (e.checked) {
            listaEquipos += `${e.parentElement.textContent},`;
            cont++;
        }
    });
    if (cont <= 0) return alertaCamposVacios("marca al menos un equipo");
    document.getElementById("detalleEnvio").textContent = listaEquipos.slice(0, -1);
    document.getElementById("rutaEnvio").dataset.rutaEnvio="php/envio_pdf/ficha_equipos/envio.php"
    $("#modalEnviarAdjunto").modal("show");
}

/* envio de docuemtnos multiples  */
const capturarIddocEquipoMultiple = (elemento) => {
    const d = document;
    let checked = elemento.checked;
    let objetoTemporal = {
        id: elemento.dataset.check,
        codigo: elemento.dataset.codigo,
        documento: elemento.dataset.documento,
    };
    let arrayFinal = [];
    if (localStorage.getItem("dTDocMulti")) {
        if(!checked) return removeRegistroParcial(objetoTemporal.id);
      let dataLocalStorage = JSON.parse(localStorage.getItem("dTDocMulti"));
      dataLocalStorage.push(objetoTemporal);
      localStorage.setItem("dTDocMulti", JSON.stringify(dataLocalStorage));
    } else {
      arrayFinal.push(objetoTemporal);
      localStorage.setItem("dTDocMulti", JSON.stringify(arrayFinal));
    }
  };
  
  const verListaRegistroParcial = () => {
    let dataLocalStorage = localStorage.getItem("dTDocMulti");
    let tBody = document.getElementById('llegadaListaDocumentosMulti');
    if (!dataLocalStorage) return toastPersonalizada('marca al menos un documento','warning');
    let html = "";
    dataLocalStorage = JSON.parse(dataLocalStorage);
    let arregloOrdenado = groupByKey(dataLocalStorage,'codigo');
    for (const x in arregloOrdenado) {
        html += `<div class='col-sm-3 p-2'>
        <div class='shadow bg-secondary-opacity-2 p-1'>
                    <b class='text-center d-block'>${x}</b>
                    <ul>`;
        arregloOrdenado[x].forEach(e => {
                      html += `<li>${e.documento}</li>`
                  });
            html += `</ul> 
            </div> </div>`;
    }
    tBody.innerHTML = html;
    
  };
  
  const removeRegistroParcial = (idCheck) => {
    let newData = [];
    let data = JSON.parse(localStorage.getItem("dTDocMulti"));
    for (let i of data) {
      if (i.id===idCheck) {
        continue;
      } else {
        newData.push(i);
      }
    }
    localStorage.setItem("dTDocMulti", JSON.stringify(newData));
   // verListaRegistroParcial(element.dataset.tbody);
  };
  const removeDataLSDocMulti = () => {
      if (localStorage.getItem('dTDocMulti')) {
          localStorage.removeItem('dTDocMulti');
      }
  };

  
/* fin envio de docuemtnos multiples  */

function groupByKey(array, key) {
    return array
      .reduce((hash, obj) => {
        if(obj[key] === undefined) return hash; 
        return Object.assign(hash, { [obj[key]]:( hash[obj[key]] || [] ).concat(obj)})
      }, {})
 }
 

const modalEnvioDocumentoEquipo = () => {
    let listaDocumentos = "";
    let cont = 0;
    let inputCheck = document.querySelectorAll("[data-check]");
    inputCheck.forEach(e => {
        if (e.checked) {
            listaDocumentos += `${e.parentElement.textContent} => ${e.parentElement.nextElementSibling.textContent }<br>`;
            cont++;
        }
    });
    if (cont <= 0) return alertaCamposVacios("marca al menos un documento");
    document.getElementById("detalleEnvio").innerHTML = listaDocumentos;
    document.getElementById("rutaEnvio").dataset.rutaEnvio="php/envio_pdf/documento_equipos/envio.php"
    $("#modalEnviarAdjunto").modal("show");
}
const modalEnvioDocumentosMulti = () => {
    let dataLocalStorage = localStorage.getItem("dTDocMulti");
    if (!dataLocalStorage) return toastPersonalizada('marca al menos un documento','warning');
    verListaRegistroParcial();
    $("#modalEnviarAdjuntoMulti").modal("show");
}

/* se reutiliza para enviar el documentos y el archivos creado */
const enviarDocEquipo = (ruta) => {

    let idsChecked = "";
    let inputCheck = document.querySelectorAll("[data-check]");
    inputCheck.forEach(e => {
        if (e.checked) {
            idsChecked += `${e.dataset.check}|`;
        }
    });
    let valorCorreo = document.getElementById("correoAsunto").value,
        regexsCorreo = valorCorreo.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
    if (regexsCorreo === null) return alertaCamposVacios("Ingrese un correo valido");
    let idsElementos = idsChecked.slice(0, -1)
    verLoader();
    $.ajax({
        url: document.getElementById("rutaEnvio").dataset.rutaEnvio,
        type: "POST",
        data: "idsEquipos=" + idsElementos +
            "&correo=" + document.getElementById("correoAsunto").value +
            "&asunto=" + document.getElementById("asuntoEnvio").value,
        success: function (response) {

            if (response === "true") {
                alertaPersonalizada('Correo enviado','success',1500)
                inputCheck.forEach(e => {
                    e.checked = false
                });
                limpiarFormulario("formEnvioAdjunto")
                $("#modalEnviarAdjunto").modal("hide");
            } else {
                alertaPersonalizada('Fallo al enviar el correo!','error',1500)
            }
            ocultarLoader()
        }
    })
}
const  enviarDocEquipoMulti = () => {
    let dataLocalStorage = localStorage.getItem("dTDocMulti");
    if (dataLocalStorage ){
        if (dataLocalStorage.length===0) return toastPersonalizada("marca al menos un documento",'warning')
    }else {
        return toastPersonalizada("marca al menos un documento",'warning');
    }
    verLoader()
    dataLocalStorage = JSON.parse(dataLocalStorage);
    let arregloOrdenado = groupByKey(dataLocalStorage,'codigo');

    let valorCorreo = document.getElementById("correodocsMulti").value,
        asuntoCorreo = document.getElementById("asuntodocsMulti").value,
        regexsCorreo = valorCorreo.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/);
    if (regexsCorreo === null) return alertaCamposVacios("Ingrese un correo valido");
    if (asuntoCorreo == "") {
        asuntoCorreo="-";
    }
    let data = new FormData();
    data.append("docsEquipos", JSON.stringify(arregloOrdenado)) 
    data.append("correo", valorCorreo) 
    data.append("asunto", asuntoCorreo) 
    fetch("php/envio_pdf/documento_equipos_multi/envio.php", {
        method:"POST",
        body:data
    })
    .then(res => res.json())
    .then(json =>  {
        if (json) {
            alertaPersonalizada('Correo enviado','success',1500)
            removeDataLSDocMulti();
            verListaDocumentos(false,false);
            $("#modalEnviarAdjuntoMulti").modal("hide");
        }else {
            alertaPersonalizada('Fallo al enviar el correo!','error',1500)
        }
        ocultarLoader();
    })
}

const verImgEquipo = (idEquipo) => {
    verLoader();
    $.ajax({
        url: "php/equipo/imagen/visualizar.php",
        type: "POST",
        data: "idEquipo=" + idEquipo,
        success: function (response) {
            document.getElementById("llegaImgEquipo").innerHTML = response;
            ocultarLoader()
        }
    });
}

const verPdfEquipo = (id) => {
    $.ajax({
        type: "POST",
        url: "php/sesion_pdfs.php",
        data: "idEquipo=" + id,
        success: function (response) {
            window.open("php/generaPDF/ficha_equipo/index.php", "facturacion", "width=650,height=600,margin=0,padding=5,scrollbars=SI,top=80,left=370");
        }
    });
}
const eventoVerPdf = (elemento) => {
    verPdfEquipo(elemento.dataset.idequipo)
}