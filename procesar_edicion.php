<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header('Location: admin.html');
    exit;
}

require_once 'conexion.php';

$boleta = $_POST['boleta'] ?? '';
$nombre = $_POST['nombre_completo'] ?? '';
$correo = $_POST['correo_institucional'] ?? '';
$grupo = $_POST['grupo_asignado'] ?? '';
$horario = $_POST['horario_examen'] ?? '';
$nueva_password = $_POST['nueva_password'] ?? '';

if (empty($boleta)) {
    header('Location: panel_admin.php');
    exit;
}

try {
    if (!empty($nueva_password)) {
        $password_encriptada = password_hash($nueva_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE alumnos 
                SET nombre_completo = :nombre, 
                    correo_institucional = :correo, 
                    grupo_asignado = :grupo, 
                    horario_examen = :horario, 
                    contrasena = :contrasena 
                WHERE boleta = :boleta";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':grupo' => $grupo,
            ':horario' => $horario,
            ':contrasena' => $password_encriptada,
            ':boleta' => $boleta
        ]);
        
    } else {
        $sql = "UPDATE alumnos 
                SET nombre_completo = :nombre, 
                    correo_institucional = :correo, 
                    grupo_asignado = :grupo, 
                    horario_examen = :horario 
                WHERE boleta = :boleta";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':grupo' => $grupo,
            ':horario' => $horario,
            ':boleta' => $boleta
        ]);
    }

    header('Location: panel_admin.php');
    exit;

} catch (PDOException $e) {
    echo "<h1>Error al actualizar los datos</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<a href='panel_admin.php'>Volver al panel</a>";
}
?>