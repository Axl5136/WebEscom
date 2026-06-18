<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    echo json_encode(['success' => false, 'mensaje' => 'Acceso denegado.']);
    exit;
}

require_once 'conexion.php';

header('Content-Type: application/json');

$boleta              = $_POST['boleta']              ?? '';
$nombre_completo     = $_POST['nombre_completo']     ?? '';
$fecha_nacimiento    = $_POST['fecha_nacimiento']    ?? '';
$genero              = $_POST['genero']              ?? '';
$curp                = $_POST['curp']                ?? '';
$estado_procedencia  = $_POST['estado_procedencia']  ?? '';
$telefono            = $_POST['telefono']            ?? '';
$escuela_procedencia = $_POST['escuela_procedencia'] ?? '';
$nombre_escuela      = $_POST['nombre_escuela']      ?? null;
$promedio            = $_POST['promedio']            ?? '';
$correo_institucional= $_POST['correo_institucional']?? '';
$contrasena          = $_POST['contrasena']          ?? '';
$grupo_asignado      = $_POST['grupo_asignado']      ?? '';
$horario_examen      = $_POST['horario_examen']      ?? '';

$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

try {

    $stmtCount = $pdo->prepare(
        "SELECT COUNT(*) FROM alumnos WHERE grupo_asignado = :grupo AND horario_examen = :horario"
    );
    $stmtCount->execute([':grupo' => $grupo_asignado, ':horario' => $horario_examen]);
    $cantidad = $stmtCount->fetchColumn();

    if ($cantidad >= 30) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'El grupo y horario seleccionados ya no tienen cupo disponible. Por favor selecciona otro.'
        ]);
        exit;
    }

    $sql = "INSERT INTO alumnos (
                boleta,
                nombre_completo,
                fecha_nacimiento,
                genero,
                curp,
                estado_procedencia,
                telefono,
                escuela_procedencia,
                nombre_escuela,
                promedio,
                correo_institucional,
                contrasena,
                grupo_asignado,
                horario_examen
            ) VALUES (
                :boleta,
                :nombre_completo,
                :fecha_nacimiento,
                :genero,
                :curp,
                :estado_procedencia,
                :telefono,
                :escuela_procedencia,
                :nombre_escuela,
                :promedio,
                :correo_institucional,
                :contrasena,
                :grupo_asignado,
                :horario_examen
            )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':boleta'               => $boleta,
        ':nombre_completo'      => $nombre_completo,
        ':fecha_nacimiento'     => $fecha_nacimiento,
        ':genero'               => $genero,
        ':curp'                 => $curp,
        ':estado_procedencia'   => $estado_procedencia,
        ':telefono'             => $telefono,
        ':escuela_procedencia'  => $escuela_procedencia,
        ':nombre_escuela'       => $nombre_escuela,
        ':promedio'             => $promedio,
        ':correo_institucional' => $correo_institucional,
        ':contrasena'           => $contrasena_hash,
        ':grupo_asignado'       => $grupo_asignado,
        ':horario_examen'       => $horario_examen,
    ]);

    echo json_encode([
        'success' => true,
        'datos'   => [
            'boleta'               => $boleta,
            'nombre_completo'      => $nombre_completo,
            'grupo_asignado'       => $grupo_asignado,
            'horario_examen'       => $horario_examen,
        ]
    ]);

} catch (PDOException $e) {

    $mensaje = 'Ocurrió un error al guardar los datos.';

    if ($e->getCode() == 23000) {
        $mensaje = 'La boleta, CURP o correo ingresados ya están registrados en el sistema.';
    }

    echo json_encode([
        'success' => false,
        'mensaje' => $mensaje,
        'detalle' => $e->getMessage()
    ]);

}