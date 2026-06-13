<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header('Location: admin.html');
    exit;
}

require_once 'conexion.php';

if (!isset($_GET['boleta'])) {
    header('Location: panel_admin.php');
    exit;
}

$boleta = $_GET['boleta'];

$sql = "SELECT * FROM alumnos WHERE boleta = :boleta";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':boleta', $boleta);
$stmt->execute();

$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    header('Location: panel_admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
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

    <h2>Editar Alumno</h2>
    <p class="text-muted">Modifica los datos del alumno y guarda los cambios.</p>

    <form id="formEditarAlumno" method="POST" action="procesar_edicion.php">

        <input type="hidden" name="boleta" value="<?php echo htmlspecialchars($alumno['boleta']); ?>">

        <div class="mb-3">
            <label class="form-label">Boleta</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($alumno['boleta']); ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" name="nombre_completo" value="<?php echo htmlspecialchars($alumno['nombre_completo']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo Institucional</label>
            <input type="email" class="form-control" name="correo_institucional" value="<?php echo htmlspecialchars($alumno['correo_institucional']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Grupo Asignado</label>
            <input type="text" class="form-control" name="grupo_asignado" value="<?php echo htmlspecialchars($alumno['grupo_asignado']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Horario de Examen</label>
            <input type="text" class="form-control" name="horario_examen" value="<?php echo htmlspecialchars($alumno['horario_examen']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" name="nueva_password" placeholder="Dejar en blanco si no se desea cambiar">
            <small class="text-muted">Dejar en blanco si no se desea cambiar la contraseña.</small>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="panel_admin.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
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
