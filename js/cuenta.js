document.getElementById('formLogin').addEventListener('submit', function(e) {
    e.preventDefault();

    const correo = document.getElementById('correo').value.trim();
    const password = document.getElementById('password').value.trim();

    if (correo === '' || password === '') {
        alert('Por favor, llena todos los campos.');
        return;
    }

    const formData = new FormData();
    formData.append('correo', correo);
    formData.append('password', password);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            window.location.href = 'perfil_alumno.php';
        } else {
            alert(data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
    });
});