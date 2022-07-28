function agregarProyecto() {
    event.preventDefault();
    if (validar_campos("formProyecto")) {
        verLoader()
        $.ajax({
            type: "POST",
            url: "php/mantenimientos/proyecto/agrega.php",
            data: $("#formProyecto").serialize(),
            success: function (response) {
                validaRespuestasAgregar(response, "php/mantenimientos/proyecto/index.php")
                ocultarLoader();
            }
        });
    } else {
        toastPersonalizada("Datos Incompletos","error")
    }
}

const llenarDatosProyectos = (dato) => {
    let x = dato.split("|");
    document.getElementById("id_proyecto").value = x[0];
    document.getElementById("pro_descripcion").value = x[1];
    document.getElementById("pro_estado").value = 'ACTIVO';
}

const actualizaProyecto = () => {
    if (validar_campos("formProyectosAct")) {
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
                    url: "php/mantenimientos/proyecto/actualiza.php",
                    data: $("#formProyectosAct").serialize(),
                    success: function (response) {
                        //console.log(response) /// ver respuesta
                        validaRespuestaActualizar(response, "php/mantenimientos/proyecto/index.php", "modalProyectosAct")
                        ocultarLoader();
                    }
                });
            }
        })
    }else{
        toastPersonalizada("Falta Datos","error")
    }
}