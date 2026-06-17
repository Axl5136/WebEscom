<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header('Location: admin.html');
    exit;
}

require_once 'conexion.php';

if (!isset($_GET['boleta']) || trim($_GET['boleta']) === '') {
    header('Location: panel_admin.php');
    exit;
}

$boleta = $_GET['boleta'];

$stmt = $pdo->prepare("DELETE FROM alumnos WHERE boleta = :boleta");
$stmt->execute([':boleta' => $boleta]);

header('Location: panel_admin.php');
exit;