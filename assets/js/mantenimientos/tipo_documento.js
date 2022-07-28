function agregarTipoDocumento() {
    event.preventDefault();
    if (validar_campos("formTipoDocumento")) {
        verLoader()
        $.ajax({
            type: "POST",
            url: "php/mantenimientos/tipoDocumento/agrega.php",
            data: $("#formTipoDocumento").serialize(),
            success: function (response) {
                validaRespuestasAgregar(response, "php/mantenimientos/tipoDocumento/index.php")
                ocultarLoader();
            }
        });
    } else {
        toastPersonalizada("Datos Incompletos","error")
    }
}

const llenarDatosTipoDocumento = (dato) => {
    let x = dato.split("|");
    document.getElementById("id_tipodocumento").value = x[0];
    document.getElementById("tdoc_descripcion").value = x[1];
    document.getElementById("tdoc_estado").value = 'ACTIVO';

}

const actualizaTipoDocumento = () => {
    if (validar_campos("formTipoDocumentoAct")) {
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
                    url: "php/mantenimientos/tipoDocumento/actualiza.php",
                    data: $("#formTipoDocumentoAct").serialize(),
                    success: function (response) {
                        validaRespuestaActualizar(response, "php/mantenimientos/tipoDocumento/index.php", "modalTipoDocumento")
                        ocultarLoader();
                    }
                });
            }
        })
    }else{
        toastPersonalizada("Error","error")
    }
}