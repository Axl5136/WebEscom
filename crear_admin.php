<?php
require_once 'conexion.php';

$usuario = 'admin_escom';
$contrasena_plana = 'admin123';
$contrasena_encriptada = password_hash($contrasena_plana, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO administradores (usuario, contrasena) VALUES (:usuario, :contrasena)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario' => $usuario,
        ':contrasena' => $contrasena_encriptada
    ]);
    echo "<p>Usuario: <b>$usuario</b></p>";
    echo "<p>Contraseña: <b>$contrasena_plana</b></p>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>