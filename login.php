<?php
session_start();

require_once 'conexion.php';

header('Content-Type: application/json');

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $sql = "SELECT * FROM alumnos WHERE correo_institucional = :correo";
    $stmt = $pdo->prepare($sql); 
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($alumno) {
        if (password_verify($password, $alumno['contrasena'])) {

            $_SESSION['boleta'] = $alumno['boleta'];
            $_SESSION['nombre_completo'] = $alumno['nombre_completo'];
            $_SESSION['grupo_asignado'] = $alumno['grupo_asignado'];
            $_SESSION['horario_examen'] = $alumno['horario_examen'];

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Correo o contraseña incorrectos']);
        }
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Correo o contraseña incorrectos']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Error de servidor.']);
}