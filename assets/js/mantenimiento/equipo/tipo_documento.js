function agregarTipoDocEquipo() {
    event.preventDefault();
    console.log(event);
    if (validar_campos("formTipoDocEquipo")) {
        verLoader()
        $.ajax({
            type: "POST",
            url: "php/mantenimiento/equipo/tipo_documento/agrega.php",
            data: $("#formTipoDocEquipo").serialize(),
            success: function (response) {
                validaRespuestasAgregar(response, "vistas/formularios/agrega_tipo_doc_equipo.html")
                ocultarLoader();
            }
        });
    } else {
        alertaCamposVacios()
    }
}

const llenarDatosTipoDocEquipo = (dato) => {
    let x = dato.split("|");
    document.getElementById("idTidoAct").value = x[0];
    document.getElementById("descripcionTidoAct").value = x[1];
    document.getElementById("estadoTidoAct").value = x[2];
}

const actualizaTipoDocEquipo = () => {
    if (validar_campos("formTipoDocEquipoAct")) {
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
                    url: "php/mantenimiento/equipo/tipo_documento/actualiza.php",
                    data: $("#formTipoDocEquipoAct").serialize(),
                    success: function (response) {
                        validaRespuestaActualizar(response, "vistas/formularios/agrega_tipo_doc_equipo.html", "modalTipoDocEquipoAct")
                        ocultarLoader();
                    }
                });
            }
        })
    }else{
        alertaCamposVacios()
    }
}