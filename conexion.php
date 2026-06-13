<?php
define('DB_HOST',    'localhost');
define('DB_NAME',    'escom_registro');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

try {

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);

} catch (PDOException $e) {

    http_response_code(500);
    header('Content-Type: application/json');

    echo json_encode([
        'success' => false,
        'mensaje' => 'Error al conectar con la base de datos.',
        'detalle' => $e->getMessage()
    ]);

    exit;
}