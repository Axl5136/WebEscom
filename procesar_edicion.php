<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header('Location: admin.html');
    exit;
}

require_once 'conexion.php';

$boleta_original = $_POST['boleta_original'] ?? '';
$boleta_nueva    = $_POST['boleta_nueva']    ?? '';
$nombre          = $_POST['nombre_completo']      ?? '';
$correo          = $_POST['correo_institucional']  ?? '';
$telefono        = $_POST['telefono']              ?? '';
$grupo_horario   = $_POST['grupo_horario']         ?? '';
$partes  = explode('|', $grupo_horario);
$grupo   = $partes[0] ?? '';
$horario = $partes[1] ?? '';
$nueva_password = $_POST['nueva_password'] ?? '';

if (empty($boleta_original)) {
    header('Location: panel_admin.php');
    exit;
}

try {
    if (!empty($nueva_password)) {
        $password_encriptada = password_hash($nueva_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE alumnos 
                SET boleta = :boleta_nueva,
                    nombre_completo = :nombre, 
                    correo_institucional = :correo,
                    telefono = :telefono,
                    grupo_asignado = :grupo, 
                    horario_examen = :horario, 
                    contrasena = :contrasena 
                WHERE boleta = :boleta_original";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':boleta_nueva'  => $boleta_nueva,
            ':nombre'        => $nombre,
            ':correo'        => $correo,
            ':telefono'      => $telefono,
            ':grupo'         => $grupo,
            ':horario'       => $horario,
            ':contrasena'    => $password_encriptada,
            ':boleta_original' => $boleta_original
        ]);
        
    } else {
        $sql = "UPDATE alumnos 
                SET boleta = :boleta_nueva,
                    nombre_completo = :nombre, 
                    correo_institucional = :correo,
                    telefono = :telefono,
                    grupo_asignado = :grupo, 
                    horario_examen = :horario 
                WHERE boleta = :boleta_original";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':boleta_nueva'    => $boleta_nueva,
            ':nombre'          => $nombre,
            ':correo'          => $correo,
            ':telefono'        => $telefono,
            ':grupo'           => $grupo,
            ':horario'         => $horario,
            ':boleta_original' => $boleta_original
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