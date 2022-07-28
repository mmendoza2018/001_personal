function agregarPuestoLaboral() {
    event.preventDefault();
    if (validar_campos("formPuestoLaboral")) {
        verLoader()
        $.ajax({
            type: "POST",
            url: "php/mantenimientos/puestoLaboral/agrega.php",
            data: $("#formPuestoLaboral").serialize(),
            success: function (response) {
                validaRespuestasAgregar(response, "php/mantenimientos/puestoLaboral/index.php")
                ocultarLoader();
            }
        });
    } else {
        toastPersonalizada("Datos Incompletos","error")
    }
}

const llenarDatosPuestoLaboral = (dato) => {
    let x = dato.split("|");
    document.getElementById("idPuestoLaboral").value = x[0];
    document.getElementById("descripcionPuestoLaboral").value = x[1];
    document.getElementById("detallePuestoLaboral").value = x[3];
    document.getElementById("estadoPuestoLaboral").value = 'ACTIVO';
}

const actualizaPuestoLaboral = () => {
    if (validar_campos("formPuestoLaboralAct")) {
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
                    url: "php/mantenimientos/puestoLaboral/actualiza.php",
                    data: $("#formPuestoLaboralAct").serialize(),
                    success: function (response) {
                        //console.log(response) /// ver respuesta
                        validaRespuestaActualizar(response, "php/mantenimientos/puestoLaboral/index.php", "modalPuestoLaboral")
                        ocultarLoader();
                    }
                });
            }
        })
    }else{
        toastPersonalizada("Falta Datos","error")
    }
}