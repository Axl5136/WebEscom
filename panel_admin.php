<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header('Location: admin.html');
    exit;
}

require_once 'conexion.php';

$sql = "SELECT * FROM alumnos";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.html">
            <img src="imgs/logoESCOMBlanco.png" alt="Logo IPN">
            <span class="ms-2 fs-5 fw-bold d-none d-sm-block">Admin</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.html">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="registro.html">Registro</a></li>
                <li class="nav-item"><a class="nav-link active" href="admin.html">Admin</a></li>
                <li class="nav-item"><a class="nav-link" href="cuenta.html">Cuenta</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Panel de Administración</h2>
            <p class="text-muted">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_admin']); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="registro_alumno_admin.php" class="btn btn-success">+ Registrar Alumno</a>
            <a href="logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
        </div>
        </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Boleta</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Grupo</th>
                    <th>Horario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?php echo htmlspecialchars($alumno['boleta']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['correo_institucional']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['grupo_asignado']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['horario_examen']); ?></td>
                    <td>
                        <a href="editar_alumno.php?boleta=<?php echo htmlspecialchars($alumno['boleta']); ?>" class="btn btn-warning btn-sm">Editar</a>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-boleta="<?php echo htmlspecialchars($alumno['boleta']); ?>" data-nombre="<?php echo htmlspecialchars($alumno['nombre_completo']); ?>">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar al alumno <strong id="nombreAlumnoEliminar"></strong> (boleta <strong id="boletaAlumnoEliminar"></strong>)?</p>
                <p class="text-danger mb-0">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">Sí, eliminar</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalEliminar = document.getElementById('modalEliminar');
    modalEliminar.addEventListener('show.bs.modal', function (event) {
        const boton = event.relatedTarget;
        const boleta = boton.getAttribute('data-boleta');
        const nombre = boton.getAttribute('data-nombre');

        document.getElementById('nombreAlumnoEliminar').textContent = nombre;
        document.getElementById('boletaAlumnoEliminar').textContent = boleta;
        document.getElementById('btnConfirmarEliminar').href = 'eliminar_alumno.php?boleta=' + encodeURIComponent(boleta);
    });
</script>

<footer>
<div class="container d-flex justify-content-between align-items-center py-3">
    <img src="imgs/IPN-Logo.png" alt="Logo IPN" style="max-height: 65px;">
    
    <div class="contenedor-central-footer">
        
        <div class="bloque-texto">
            <p class="mb-0">Instituto Politécnico Nacional - ESCOM &copy; 2026</p>
            <small>Desarrollado para la materia Tecnologías para el Desarrollo de Aplicaciones Web</small>
        </div>
        <div class="bloque-redes">
            <small class="fw-bold titulo-redes-small">Redes sociales</small>
            <ul class="list-unstyled mb-0 lista-redes-footer">
                <li><a href="https://www.facebook.com/share/1BSBQNFr3F/" class="footer-link">Facebook:escomipnmx</a></li>
                <li><a href="https://www.instagram.com/escom_ipn_mx" class="footer-link">Instragem:escom_ipn_mx</a></li>
                <li><a href="https://www.tiktok.com/@escom_ipn_mx" class="footer-link">TikTok:escom_ipn_mx</a></li>
                <li><a href="https://x.com/escomunidad/" class="footer-link">Twitter:@escomunidad</a></li>
            </ul>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <img src="imgs/Logo_Equipo.png" alt="Logo del equipo" style="max-height: 65px;">
        <img src="imgs/logoESCOMBlanco.png" alt="Logo ESCOM" style="max-height: 65px;">
    </div>
</div>
</footer>

</body>
</html>