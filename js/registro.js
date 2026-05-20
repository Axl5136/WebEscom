document.getElementById("formularioRegistro").addEventListener("submit", function(event) {
    event.preventDefault();

    let formValido = true;

    const boleta = document.getElementById("boleta").value.trim();
    const regexBoleta = /^(20\d{2}63\d{4}|P[EP]\d{8})$/;
    const anio = parseInt(boleta.substring(0, 4), 10);
    const anioActual = new Date().getFullYear();
    if (!regexBoleta.test(boleta) || anio > anioActual) {
        document.getElementById("boleta").classList.add("is-invalid");
        document.getElementById("boleta").classList.remove("is-valid");
        formValido = false;
    } else {
        document.getElementById("boleta").classList.remove("is-invalid");
        document.getElementById("boleta").classList.add("is-valid");
    }

    const nombre = document.getElementById("nombres").value.trim();
    const apellidoP = document.getElementById("apellidoP").value.trim();
    const apellidoM = document.getElementById("apellidoM").value.trim();
    const regexNombre = /^[A-Za-zÁÉÍÓÚÑáéíóúñ]{2,}(?:\s+[A-Za-zÁÉÍÓÚÑáéíóúñ]+)*$/;

    if (!regexNombre.test(nombre)) {
        document.getElementById("nombres").classList.add("is-invalid");
        document.getElementById("nombres").classList.remove("is-valid");
        formValido = false;
    } else {
        document.getElementById("nombres").classList.remove("is-invalid");
        document.getElementById("nombres").classList.add("is-valid");
    }

    if (!regexNombre.test(apellidoP)) {
        document.getElementById("apellidoP").classList.add("is-invalid");
        document.getElementById("apellidoP").classList.remove("is-valid");
        formValido = false;
    } else {
        document.getElementById("apellidoP").classList.remove("is-invalid");
        document.getElementById("apellidoP").classList.add("is-valid");
    }

    if (!regexNombre.test(apellidoM)) {
        document.getElementById("apellidoM").classList.add("is-invalid");
        document.getElementById("apellidoM").classList.remove("is-valid");
        formValido = false;
    } else {
        document.getElementById("apellidoM").classList.remove("is-invalid");
        document.getElementById("apellidoM").classList.add("is-valid");
    }

    const fechaNacimiento = document.getElementById("fechaNacimiento").value;
    if (fechaNacimiento) {
        const fechaNac = new Date(fechaNacimiento);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaNac.getFullYear();
        const mes = hoy.getMonth() - fechaNac.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) edad--;

        if (edad < 17) {
            document.getElementById("fechaNacimiento").classList.add("is-invalid");
            document.getElementById("fechaNacimiento").classList.remove("is-valid");
            formValido = false;
        } else {
            document.getElementById("fechaNacimiento").classList.remove("is-invalid");
            document.getElementById("fechaNacimiento").classList.add("is-valid");
        }
    }

    const telefono = document.getElementById("telefono").value.trim();
    const regexTel = /^[2-9]\d{9}$/;
    if (!regexTel.test(telefono)) {
        document.getElementById("telefono").classList.add("is-invalid");
        document.getElementById("telefono").classList.remove("is-valid");
        formValido = false;
    } else {
        document.getElementById("telefono").classList.remove("is-invalid");
        document.getElementById("telefono").classList.add("is-valid");
    }

    const curp = document.getElementById("curp").value.trim().toUpperCase();
    const nombresUpper = nombre.toUpperCase();
    const apellidoPUpper = apellidoP.toUpperCase();
    const apellidoMUpper = apellidoM.toUpperCase();
    const genero = document.getElementById("genero").value;
    let curpValido = true;

    const regexCURP = /^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-XYZ]{3}[A-Z0-9]{1}\d{1}$/;

    if (!regexCURP.test(curp)) {
        curpValido = false;
    } else {
        const primeraVocalInternaAP = obtenerPrimeraVocalInterna(apellidoPUpper);
        if (curp[0] !== apellidoPUpper[0]) curpValido = false;
        if (primeraVocalInternaAP && curp[1] !== primeraVocalInternaAP) curpValido = false;
        if (curp[2] !== apellidoMUpper[0]) curpValido = false;

        const nombreParaCURP = obtenerNombreParaCURP(nombresUpper);
        if (curp[3] !== nombreParaCURP[0]) curpValido = false;

        if (fechaNacimiento) {
            const partes = fechaNacimiento.split("-");
            const anioC = partes[0].slice(2);
            const mesC  = partes[1];
            const diaC  = partes[2];
            if (curp.slice(4, 10) !== anioC + mesC + diaC) curpValido = false;
        }

        if (genero === "Hombre" && curp[10] !== "H") curpValido = false;
        if (genero === "Mujer"  && curp[10] !== "M") curpValido = false;
    }

    if (!curpValido) {
        document.getElementById("curp").classList.add("is-invalid");
        document.getElementById("curp").classList.remove("is-valid");
        formValido = false;
    } else {
        document.getElementById("curp").classList.remove("is-invalid");
        document.getElementById("curp").classList.add("is-valid");
    }
    const escuela = document.getElementById("escuelaProcedencia").value;
    const otraEscuela = document.getElementById("nombreOtraEscuela").value.trim();

    if (escuela === "Otro") {
        if (otraEscuela === "") {
            document.getElementById("nombreOtraEscuela").classList.add("is-invalid");
            document.getElementById("nombreOtraEscuela").classList.remove("is-valid");
            formValido = false;
        } else {
            document.getElementById("nombreOtraEscuela").classList.remove("is-invalid");
            document.getElementById("nombreOtraEscuela").classList.add("is-valid");
        }
    }
    const correo = document.getElementById("correo").value.trim();
    const regexCorreo = /^[a-zA-Z0-9._%+-]+@alumno\.ipn\.mx$/;
    if (!regexCorreo.test(correo)) {
        document.getElementById("correo").classList.add("is-invalid");
        document.getElementById("correo").classList.remove("is-valid");
        formValido = false;
    } else {
        document.getElementById("correo").classList.remove("is-invalid");
        document.getElementById("correo").classList.add("is-valid");
    }

    const password = document.getElementById("password").value;
    const regexPassword = /^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
    if (!regexPassword.test(password)) {
        document.getElementById("password").classList.add("is-invalid");
        document.getElementById("password").classList.remove("is-valid");
        formValido = false;
    } else {
        document.getElementById("password").classList.remove("is-invalid");
        document.getElementById("password").classList.add("is-valid");
    }
    if (formValido) {
        document.getElementById("pantallaExito").classList.remove("d-none");
        setTimeout(() => {
            document.getElementById("formularioRegistro").reset();
            document.querySelectorAll(".is-valid").forEach(el => el.classList.remove("is-valid"));
            document.getElementById("pantallaExito").classList.add("d-none");
        }, 3000);
    }

});

function obtenerPrimeraVocalInterna(apellido) {
    const vocales = "AEIOU";
    for (let i = 1; i < apellido.length; i++) {
        if (vocales.includes(apellido[i])) return apellido[i];
    }
    return null;
}

function obtenerNombreParaCURP(nombreCompleto) {
    const partes = nombreCompleto.split(" ").filter(p => p.length > 0);
    const compuestos = ["JOSE", "MARIA", "MA.", "MA"];
    if (partes.length > 1 && compuestos.includes(partes[0])) return partes[1];
    return partes[0];
}