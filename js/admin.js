document.getElementById('formAdmin').addEventListener('submit', function(e) {
    e.preventDefault();

    const usuario = document.getElementById('usuario').value.trim();
    const password = document.getElementById('password').value.trim();

    if (usuario === '' || password === '') {
        alert('Por favor, llena todos los campos.');
        return;
    }

    const formData = new FormData();
    formData.append('usuario', usuario);
    formData.append('password', password);

    fetch('login_admin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            window.location.href = 'panel_admin.php';
        } else {
            alert(data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
    });
});