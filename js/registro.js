document.getElementById("formularioRegistro").addEventListener("submit", function(event) {
    event.preventDefault();

    let formValido = true;

    function setValidacion(id, esValido) {
        const el = document.getElementById(id);
        if (esValido) {
            el.classList.remove("is-invalid");
            el.classList.add("is-valid");
        } else {
            el.classList.add("is-invalid");
            el.classList.remove("is-valid");
        }
        return esValido;
    }

    const boleta = document.getElementById("boleta").value.trim();
    const anio = parseInt(boleta.substring(0, 4), 10);
    const anioActual = new Date().getFullYear();
    if (!setValidacion("boleta", /^(20\d{2}63\d{4}|P[EP]\d{8})$/.test(boleta) && anio <= anioActual)) formValido = false;

    const nombre = document.getElementById("nombres").value.trim();
    const apellidoP = document.getElementById("apellidoP").value.trim();
    const apellidoM = document.getElementById("apellidoM").value.trim();
    const regexNombre = /^[A-Za-zÁÉÍÓÚÑáéíóúñ]{2,}(?:\s+[A-Za-zÁÉÍÓÚÑáéíóúñ]+)*$/;

    if (!setValidacion("nombres", regexNombre.test(nombre))) formValido = false;
    if (!setValidacion("apellidoP", regexNombre.test(apellidoP))) formValido = false;
    if (!setValidacion("apellidoM", regexNombre.test(apellidoM))) formValido = false;

    const fechaNacimiento = document.getElementById("fechaNacimiento").value;
    let edadValida = false;
    if (fechaNacimiento) {
        const fechaNac = new Date(fechaNacimiento);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaNac.getFullYear();
        const mes = hoy.getMonth() - fechaNac.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) edad--;
        edadValida = edad >= 17;
    }
    if (!setValidacion("fechaNacimiento", edadValida)) formValido = false;

    const telefono = document.getElementById("telefono").value.trim();
    if (!setValidacion("telefono", /^[2-9]\d{9}$/.test(telefono))) formValido = false;

    const curp = document.getElementById("curp").value.trim().toUpperCase();
    let curpValido = /^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-XYZ]{3}[A-Z0-9]{1}\d{1}$/.test(curp);
    
    if (curpValido) {
        const nombresUpper = nombre.toUpperCase();
        const apellidoPUpper = apellidoP.toUpperCase();
        const apellidoMUpper = apellidoM.toUpperCase();
        const genero = document.getElementById("genero").value;
        const primeraVocalInternaAP = obtenerPrimeraVocalInterna(apellidoPUpper);
        const nombreParaCURP = obtenerNombreParaCURP(nombresUpper);

        if (curp[0] !== apellidoPUpper[0]) curpValido = false;
        if (primeraVocalInternaAP && curp[1] !== primeraVocalInternaAP) curpValido = false;
        if (curp[2] !== apellidoMUpper[0]) curpValido = false;
        if (curp[3] !== nombreParaCURP[0]) curpValido = false;

        if (fechaNacimiento) {
            const partes = fechaNacimiento.split("-");
            if (curp.slice(4, 10) !== partes[0].slice(2) + partes[1] + partes[2]) curpValido = false;
        }

        if ((genero === "Hombre" && curp[10] !== "H") || (genero === "Mujer" && curp[10] !== "M")) curpValido = false;
    }
    if (!setValidacion("curp", curpValido)) formValido = false;

    const escuela = document.getElementById("escuelaProcedencia").value;
    const elOtraEscuela = document.getElementById("nombreOtraEscuela");
    const otraEscuelaVal = elOtraEscuela.value.trim();

    if (escuela === "Otro") {
        if (!setValidacion("nombreOtraEscuela", otraEscuelaVal !== "")) formValido = false;
    } else {
        elOtraEscuela.classList.remove("is-invalid", "is-valid");
        elOtraEscuela.value = "";
    }

    const correo = document.getElementById("correo").value.trim().toLowerCase();
    const iniciaNombre = nombre.trim()[0]?.toLowerCase() || "";
    const apPLower = apellidoP.trim().toLowerCase();
    const iniciaMat = apellidoM.trim()[0]?.toLowerCase() || "";
    const regexCorreo = new RegExp(`^${iniciaNombre}${apPLower}${iniciaMat}\\d{4}@alumno\\.ipn\\.mx$`);
    if (!setValidacion("correo", regexCorreo.test(correo))) formValido = false;

    const password = document.getElementById("password").value;
    if (!setValidacion("password", /^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/.test(password))) formValido = false;

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
