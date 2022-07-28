function agregarMotivoSalida() {
    event.preventDefault();
    if (validar_campos("formMotivoSalida")) {
        verLoader()
        $.ajax({
            type: "POST",
            url: "php/mantenimientos/motivoPermiso/agrega.php",
            data: $("#formMotivoSalida").serialize(),
            success: function (response) {
                validaRespuestasAgregar(response, "php/mantenimientos/motivoPermiso/index.php")
                ocultarLoader();
            }
        });
    } else {
        toastPersonalizada("Datos Incompletos","error")
    }
}

const llenarDatosMotivo = (dato) => {
    let x = dato.split("|");
    document.getElementById("id_motivo").value = x[0];
    document.getElementById("mot_descripcion").value = x[1];
    document.getElementById("mot_estado").value = 'ACTIVO';
}

const actualizaMotivo = () => {
    if (validar_campos("formMotivoSalidaAct")) {
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
                    url: "php/mantenimientos/motivoPermiso/actualiza.php",
                    data: $("#formMotivoSalidaAct").serialize(),
                    success: function (response) {
                        validaRespuestaActualizar(response, "php/mantenimientos/motivoPermiso/index.php", "modalMotivoAct")
                        ocultarLoader();
                    }
                });
            }
        })
    }else{
        toastPersonalizada("Falta Datos","error")
    }
}