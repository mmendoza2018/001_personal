const agregarPersonal = () => {
  event.preventDefault();
  if (!validar_campos("formPersonalAdd"))
    return toastPersonalizada("Algunos campos omitidos son obligatorios", 'warning');
  verLoader();
  let formulario = document.getElementById('formPersonalAdd');
  let data = new FormData(formulario);
  fetch("php/recursosHumanos/personal/agrega.php", {
    method: "POST",
    body: data,
  })
    .then((res) => res.json())
    .then((json) => {
      console.log("json", json);  
      if (json[0]) {
        toastPersonalizada('Agregado correctamente!', 'success', 2000)
        formulario.reset();
      }else {
        toastPersonalizada(json[1], 'error',2000)
      } 
      ocultarLoader();
    });
};

const selectChange = (elemento, ruta, idLlegada) => {
  let data = new FormData();
  data.append("idSelect",elemento.value);
  fetch(ruta, {
    method: "POST",
    body: data,
  })
    .then((res) => res.text())
    .then((options) => {
      console.log("html", options);  
      document.getElementById(idLlegada).innerHTML=options;
      ocultarLoader();
    });
} 

const llenarDatosPersonalAct = (idPersona) => {
  verLoader();
  let data = new FormData();
  data.append('idPersona',idPersona)
  fetch('php/recursosHumanos/personal/dataPersona.php', {
    method: "POST",
    body: data,
  })
    .then((res) => res.json())
    .then(json => {
      console.log("json", json);  
      document.getElementById('idPerAct').value=json.idPersona
      document.getElementById('tipdocPerAct').value=json.tipoDoc
      document.getElementById('numdocPerAct').value=json.idPersona
      document.getElementById('nombresPerAct').value=json.nombres
      document.getElementById('apellidosPerAct').value=json.apellidos
      document.getElementById('sexoPerAct').value=json.sexo
      document.getElementById('fecha_nacPerAct').value=json.fNacimiento
      document.getElementById('lugar_nacPerAct').value=json.lNacimiento
      document.getElementById('estado_civPerAct').value=json.estadoCivil
      document.getElementById('hijoPerAct').value=json.hijos
      document.getElementById('emailPerAct').value=json.email
      document.getElementById('estudiosPerAct').value=json.estudios
      document.getElementById('direccionPerAct').value=json.direccion
      //document.getElementById('DepartamentoPerAct').innerHtml=json.departamento
      //document.getElementById('ProvinciaPerAct').innerHtml=json.provinvia
      //document.getElementById('DistritoPerAct').innerHtml=json.distrito
      document.getElementById('telefonoPerAct').value=json.telefono
      document.getElementById('sangrePerAct').value=json.sangre
      document.getElementById('puestoPerAct').value=json.idPuesto
      document.getElementById('departPerAct').value=json.idDepartamento
      document.getElementById('fechaPerAct').value=json.fechaIngreso
      document.getElementById('sueldoPerAct').value=json.sueldo
      document.getElementById('bonoPerAct').value=json.bono
      document.getElementById('regimenPerAct').value=json.regimen
      document.getElementById('regimen_traPerAct').value=json.regimenTransp
      document.getElementById('pensionPerAct').value=json.pension
      document.getElementById('cusppPerAct').value=json.cuspp
      document.getElementById('afpPerAct').value=json.afp
      document.getElementById('flujoPerAct').value=json.flujo
      document.getElementById('nombre1PerAct').value=json.famNombre1
      document.getElementById('parentesco1PerAct').value=json.famParentesco1
      document.getElementById('celular1PerAct').value=json.famCelular1
      document.getElementById('nombre2PerAct').value=json.famNombre2
      document.getElementById('parentesco2PerAct').value=json.famParentesco2
      document.getElementById('celular2PerAct').value=json.famCelular2
      document.getElementById('nombre3PerAct').value=json.famNombre3
      document.getElementById('parentesco3PerAct').value=json.famParentesco3
      document.getElementById('celular3PerAct').value=json.famCelular3
      document.getElementById('bancoPerAct').value=json.banco
      document.getElementById('cuentaPerAct').value=json.cuenta
      document.getElementById('cciPerAct').value=json.cci
      ocultarLoader();
    });
};



   