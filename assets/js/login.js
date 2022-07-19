document.addEventListener("click", (e) => {
    if (event.target.matches(".verContra *")) {
        let ver = document.querySelector("#verContra");
        (ver.getAttribute("type") === "password") ? ver.setAttribute("type", "text"): ver.setAttribute("type", "password");
    }
    if (event.target.matches("#envioLogin")) {
        e.preventDefault();
        const datos = $("#formLogin").serialize(e);
        $.ajax({
            type: "POST",
            url: "php/login.php",
            data: datos,
            success: function (response) {
                console.log('response', response)
                if (response === "1") {
                    $("#formLogin")[0].reset();
                    window.location = "menu.php";
                } else if (response === "2") {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'el usuario no existe',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else if (response === "3") {

                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'la contraseña no coincide',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else if (response === "0") {
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'el trabajador no tiene rol en el sistema !',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    alert("no hay datos")
                }
            }
        });
    }
})
 