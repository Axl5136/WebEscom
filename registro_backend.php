<?php

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

$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

$grupos   = ['1CM1', '1CM2', '1CM3', '1CM4', '1CM5'];
$horarios = ['10:00 - 12:00', '12:00 - 14:00'];

$grupo_asignado = null;
$horario_examen = null;

foreach ($horarios as $horario) {
    foreach ($grupos as $grupo) {
        $stmtCount = $pdo->prepare(
            "SELECT COUNT(*) FROM alumnos WHERE grupo_asignado = :grupo AND horario_examen = :horario"
        );
        $stmtCount->execute([':grupo' => $grupo, ':horario' => $horario]);
        $cantidad = $stmtCount->fetchColumn();

        if ($cantidad < 30) {
            $grupo_asignado = $grupo;
            $horario_examen = $horario;
            break 2;
        }
    }
}

if ($grupo_asignado === null) {
    echo json_encode(['success' => false, 'mensaje' => 'No hay lugares disponibles en ningún laboratorio ni horario.']);
    exit;
}

$grupo_asignado = $grupos[array_rand($grupos)];
$horario_examen = $horarios[array_rand($horarios)];

try {

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
            'fecha_nacimiento'     => $fecha_nacimiento,
            'genero'               => $genero,
            'curp'                 => $curp,
            'estado_procedencia'   => $estado_procedencia,
            'telefono'             => $telefono,
            'escuela_procedencia'  => $escuela_procedencia,
            'nombre_escuela'       => $nombre_escuela,
            'promedio'             => $promedio,
            'correo_institucional' => $correo_institucional,
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