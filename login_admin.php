<?php
session_start();

require_once 'conexion.php';

header('Content-Type: application/json');

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $sql = "SELECT * FROM administradores WHERE usuario = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        if (password_verify($password, $admin['contrasena'])) {

            $_SESSION['id_admin'] = $admin['id_admin']; 
            $_SESSION['usuario_admin'] = $admin['usuario'];

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
        }
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>