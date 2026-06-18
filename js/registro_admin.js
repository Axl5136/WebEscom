const formulario = document.getElementById("formularioRegistroAdmin");
const escuelaSelect = document.getElementById("escuelaProcedencia");
const nombreOtraEscuela = document.getElementById("nombreOtraEscuela");

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

function quitarAcentos(texto) {
    return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function actualizarCampoOtraEscuela() {
    const esOtro = escuelaSelect.value === "Otro";
    nombreOtraEscuela.disabled = !esOtro;
    if (!esOtro) {
        nombreOtraEscuela.value = "";
        nombreOtraEscuela.classList.remove("is-invalid", "is-valid");
    }
}

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

escuelaSelect.addEventListener("change", actualizarCampoOtraEscuela);

formulario.addEventListener("submit", function (event) {
    event.preventDefault();

    let formValido = true;

    const boleta = document.getElementById("boleta").value.trim();
    if (!setValidacion("boleta", /^(\d{10}|PE\d{8}|PP\d{8})$/.test(boleta))) formValido = false;

    const nombre = document.getElementById("nombres").value.trim();
    const apellidoP = document.getElementById("apellidoP").value.trim();
    const apellidoM = document.getElementById("apellidoM").value.trim();
    const nombreCompleto = `${nombre} ${apellidoP} ${apellidoM}`.replace(/\s+/g, " ").trim();
    const regexNombre = /^[A-Za-zÁÉÍÓÚÑáéíóúñ]{2,}(?:\s+[A-Za-zÁÉÍÓÚÑáéíóúñ]+)*$/;

    if (!setValidacion("nombres", regexNombre.test(nombre))) formValido = false;
    if (!setValidacion("apellidoP", regexNombre.test(apellidoP))) formValido = false;
    if (!setValidacion("apellidoM", regexNombre.test(apellidoM))) formValido = false;

    const fechaNacimiento = document.getElementById("fechaNacimiento").value;
    let edadValida = false;
    if (fechaNacimiento) {
        const fechaNac = new Date(fechaNacimiento + "T00:00:00");
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaNac.getFullYear();
        const mes = hoy.getMonth() - fechaNac.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) edad--;
        edadValida = edad >= 17;
    }
    if (!setValidacion("fechaNacimiento", edadValida)) formValido = false;

    const genero = document.getElementById("genero").value;
    if (!setValidacion("genero", genero === "Hombre" || genero === "Mujer")) formValido = false;

    const curp = document.getElementById("curp").value.trim().toUpperCase();
    let curpValido = /^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-XYZ]{3}[A-Z0-9]{1}\d{1}$/.test(curp);

    if (curpValido) {
        const nombresUpper = nombre.toUpperCase();
        const apellidoPUpper = apellidoP.toUpperCase();
        const apellidoMUpper = apellidoM.toUpperCase();
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

    const entidad = document.getElementById("entidad").value;
    if (!setValidacion("entidad", entidad !== "")) formValido = false;

    const telefono = document.getElementById("telefono").value.trim();
    if (!setValidacion("telefono", /^[2-9]\d{9}$/.test(telefono))) formValido = false;

    const escuela = escuelaSelect.value;
    if (!setValidacion("escuelaProcedencia", escuela !== "")) formValido = false;

    const otraEscuelaVal = nombreOtraEscuela.value.trim();
    if (escuela === "Otro") {
        if (!setValidacion("nombreOtraEscuela", otraEscuelaVal !== "")) formValido = false;
    } else {
        nombreOtraEscuela.classList.remove("is-invalid", "is-valid");
    }

    const promedioVal = document.getElementById("promedio").value.trim();
    const promedioNum = parseFloat(promedioVal);
    const promedioValido = /^\d+(\.\d+)?$/.test(promedioVal) && promedioNum >= 6.0 && promedioNum <= 10.0;
    if (!setValidacion("promedio", promedioValido)) formValido = false;

    const correo = document.getElementById("correo").value.trim().toLowerCase();
    const iniciaNombre = quitarAcentos(nombre.trim()[0]?.toLowerCase() || "");
    const apPLower = quitarAcentos(apellidoP.trim().toLowerCase());
    const iniciaMat = quitarAcentos(apellidoM.trim()[0]?.toLowerCase() || "");
    const regexCorreo = new RegExp(`^${iniciaNombre}${apPLower}${iniciaMat}\\d{4}@alumno\\.ipn\\.mx$`);
    if (!setValidacion("correo", regexCorreo.test(correo))) formValido = false;

    const password = document.getElementById("password").value;
    if (!setValidacion("password", /^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/.test(password))) formValido = false;

    const grupoHorario = document.getElementById("grupoHorario").value;
    if (!setValidacion("grupoHorario", grupoHorario !== "")) formValido = false;

    if (formValido) {
        const [grupoAsignado, horarioExamen] = grupoHorario.split("|");

        const datos = new FormData();
        datos.append("boleta", boleta);
        datos.append("nombre_completo", nombreCompleto);
        datos.append("fecha_nacimiento", fechaNacimiento);
        datos.append("genero", genero);
        datos.append("curp", curp);
        datos.append("estado_procedencia", entidad);
        datos.append("telefono", telefono);
        datos.append("escuela_procedencia", escuela);
        datos.append("nombre_escuela", otraEscuelaVal);
        datos.append("promedio", promedioVal);
        datos.append("correo_institucional", correo);
        datos.append("contrasena", password);
        datos.append("grupo_asignado", grupoAsignado);
        datos.append("horario_examen", horarioExamen);

        fetch("registrar_alumno_admin_backend.php", {
            method: "POST",
            body: datos
        })
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (data) {
            const resultadoDiv = document.getElementById("resultadoRegistro");
            if (data.success === true) {
                resultadoDiv.classList.remove("d-none", "alert-danger");
                resultadoDiv.classList.add("alert-success");
                resultadoDiv.innerHTML = `Alumno <strong>${nombreCompleto}</strong> registrado correctamente en el grupo ${grupoAsignado} (${horarioExamen}).`;
                resultadoDiv.scrollIntoView({ behavior: "smooth", block: "nearest" });

                setTimeout(function () {
                    window.location.href = "panel_admin.php";
                }, 2000);
            } else {
                resultadoDiv.classList.remove("d-none", "alert-success");
                resultadoDiv.classList.add("alert-danger");
                resultadoDiv.innerHTML = "Error: " + data.mensaje;
                resultadoDiv.scrollIntoView({ behavior: "smooth", block: "nearest" });
            }
        })
        .catch(function (error) {
            alert("No se pudo conectar con el servidor. Verifica que XAMPP esté encendido e inténtalo de nuevo.");
            console.error("Error de red:", error);
        });
    }
});

actualizarCampoOtraEscuela();