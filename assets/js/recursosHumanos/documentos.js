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
      console.log('json', json)
      if (json) {
        toastPersonalizada("Agregado correctamente!", "success", 2000);
        formulario.reset();
      } else {
        toastPersonalizada("Ocurrio un error al agregar!", "error", 2000);
      }
      ocultarLoader();
    });
};