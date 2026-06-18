<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header('Location: admin.html');
    exit;
}

require_once 'conexion.php';

// Calculamos disponibilidad de cada grupo + horario (igual que en editar_alumno.php)
$grupos = ['1CM1', '1CM2', '1CM3', '1CM4', '1CM5'];
$horarios = ['08:00 - 09:30', '09:45 - 11:15'];

$opcionesDisponibles = [];
foreach ($horarios as $horario) {
    foreach ($grupos as $grupo) {
        $stmtCount = $pdo->prepare(
            "SELECT COUNT(*) FROM alumnos WHERE grupo_asignado = :grupo AND horario_examen = :horario"
        );
        $stmtCount->execute([':grupo' => $grupo, ':horario' => $horario]);
        $cantidad = $stmtCount->fetchColumn();

        if ($cantidad < 30) {
            $opcionesDisponibles[] = [
                'grupo' => $grupo,
                'horario' => $horario,
                'cantidad' => $cantidad
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alumno - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <main class="container my-5">
        <h2 class="text-center mb-4" style="color: var(--ipn-guinda);">Registrar Alumno (Admin)</h2>

        <form id="formularioRegistroAdmin" novalidate>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Datos del Alumno</h5>
                </div>
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Número de boleta</label>
                        <input type="text" class="form-control" id="boleta" name="boleta" placeholder="Ej: 2022630000 o PE12345678" required>
                        <div class="invalid-feedback">Debe ser 10 dígitos, PE + 8 dígitos o PP + 8 dígitos</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" id="nombres" placeholder="Ej: Juan Carlos" required>
                        <div class="invalid-feedback">El o los nombres son incorrectos</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido paterno</label>
                        <input type="text" class="form-control" id="apellidoP" placeholder="Ej: García" required>
                        <div class="invalid-feedback">El apellido paterno es incorrecto</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido materno</label>
                        <input type="text" class="form-control" id="apellidoM" placeholder="Ej: López" required>
                        <div class="invalid-feedback">El apellido materno es incorrecto</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" id="fechaNacimiento" name="fecha_nacimiento" required>
                        <div class="invalid-feedback">Debes tener al menos 17 años</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Género</label>
                        <select class="form-select" id="genero" name="genero" required>
                            <option value="" selected disabled>Selecciona...</option>
                            <option value="Mujer">Mujer</option>
                            <option value="Hombre">Hombre</option>
                        </select>
                        <div class="invalid-feedback">Selecciona un género</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CURP</label>
                        <input type="text" class="form-control" id="curp" name="curp" placeholder="18 caracteres" maxlength="18" required>
                        <div class="invalid-feedback">El CURP es incorrecto</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Entidad federativa de procedencia</label>
                        <select class="form-select" id="entidad" name="estado_procedencia" required>
                            <option value="" selected disabled>Selecciona un estado...</option>
                            <option value="Aguascalientes">Aguascalientes</option>
                            <option value="Baja California">Baja California</option>
                            <option value="Baja California Sur">Baja California Sur</option>
                            <option value="Campeche">Campeche</option>
                            <option value="Chiapas">Chiapas</option>
                            <option value="Chihuahua">Chihuahua</option>
                            <option value="Ciudad de México">Ciudad de México</option>
                            <option value="Coahuila">Coahuila</option>
                            <option value="Colima">Colima</option>
                            <option value="Durango">Durango</option>
                            <option value="Estado de México">Estado de México</option>
                            <option value="Guanajuato">Guanajuato</option>
                            <option value="Guerrero">Guerrero</option>
                            <option value="Hidalgo">Hidalgo</option>
                            <option value="Jalisco">Jalisco</option>
                            <option value="Michoacán">Michoacán</option>
                            <option value="Morelos">Morelos</option>
                            <option value="Nayarit">Nayarit</option>
                            <option value="Nuevo León">Nuevo León</option>
                            <option value="Oaxaca">Oaxaca</option>
                            <option value="Puebla">Puebla</option>
                            <option value="Querétaro">Querétaro</option>
                            <option value="Quintana Roo">Quintana Roo</option>
                            <option value="San Luis Potosí">San Luis Potosí</option>
                            <option value="Sinaloa">Sinaloa</option>
                            <option value="Sonora">Sonora</option>
                            <option value="Tabasco">Tabasco</option>
                            <option value="Tamaulipas">Tamaulipas</option>
                            <option value="Tlaxcala">Tlaxcala</option>
                            <option value="Veracruz">Veracruz</option>
                            <option value="Yucatán">Yucatán</option>
                            <option value="Zacatecas">Zacatecas</option>
                        </select>
                        <div class="invalid-feedback">Selecciona una entidad federativa</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="10 dígitos" required>
                        <div class="invalid-feedback">Deben ser 10 dígitos y no pueden empezar con 0 ni 1</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Escuela de procedencia</label>
                        <select class="form-select" id="escuelaProcedencia" name="escuela_procedencia" required>
                            <option value="" selected disabled>Selecciona tu escuela...</option>
                            <option value="CECyT 1">CECyT 1 "Gonzalo Vázquez Vela"</option>
                            <option value="CECyT 2">CECyT 2 "Miguel Bernard Perales"</option>
                            <option value="CECyT 3">CECyT 3 "Estanislao Ramírez Ruíz"</option>
                            <option value="CECyT 4">CECyT 4 "Lázaro Cárdenas"</option>
                            <option value="CECyT 5">CECyT 5 "Benito Juárez García"</option>
                            <option value="CECyT 6">CECyT 6 "Miguel Othón de Mendizabal"</option>
                            <option value="CECyT 7">CECyT 7 "Cuauhtémoc"</option>
                            <option value="CECyT 8">CECyT 8 "Narciso Bassols García"</option>
                            <option value="CECyT 9">CECyT 9 "Juan de Dios Bátiz Paredes"</option>
                            <option value="CECyT 10">CECyT 10 "Carlos Vallejo Márquez"</option>
                            <option value="CECyT 11">CECyT 11 "Wilfrido Massieu"</option>
                            <option value="CECyT 12">CECyT 12 "José María Morelos"</option>
                            <option value="CECyT 13">CECyT 13 "Ricardo Flores Magón"</option>
                            <option value="CECyT 14">CECyT 14 "Luis Enrique Erro Soler"</option>
                            <option value="CECyT 15">CECyT 15 "Diódoro Antúnez Echegaray"</option>
                            <option value="CECyT 16">CECyT 16 "Unidad Hidalgo"</option>
                            <option value="CECyT 17">CECyT 17 "Unidad Guanajuato"</option>
                            <option value="CECyT 18">CECyT 18 "Unidad Zacatecas"</option>
                            <option value="CECyT 19">CECyT 19 "Leona Vicario Unidad Tecámac"</option>
                            <option value="CECyT 20">CECyT 20 "Natalia Serdán Alatriste"</option>
                            <option value="CET 1">CET 1 "Walter Cross Buchanan"</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <div class="invalid-feedback">Selecciona una escuela de procedencia</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la escuela (Si elegiste 'Otro')</label>
                        <input type="text" class="form-control" id="nombreOtraEscuela" name="nombre_escuela" placeholder="Especificar escuela" disabled>
                        <div class="invalid-feedback">Debes especificar el nombre de la escuela</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Promedio</label>
                        <input type="number" step="0.1" min="6.0" max="10.0" class="form-control" id="promedio" name="promedio" placeholder="Ej: 8.5" required>
                        <div class="invalid-feedback">El promedio debe ser un número entre 6.0 y 10.0</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo electrónico institucional</label>
                        <input type="email" class="form-control" name="correo_institucional" id="correo" placeholder="jgarcial1234@alumno.ipn.mx" required>
                        <div class="invalid-feedback">Debe seguir el formato: inicial + apellido paterno + inicial apellido materno + 4 dígitos @alumno.ipn.mx</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="contrasena" required>
                        <div class="invalid-feedback">Mínimo 6 caracteres, 1 mayúscula, 1 dígito y 1 carácter especial</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Grupo y Horario de Examen (asignación manual)</label>
                        <select class="form-select" id="grupoHorario" name="grupo_horario" required>
                            <option value="" selected disabled>Selecciona grupo y horario...</option>
                            <?php foreach ($opcionesDisponibles as $op): ?>
                                <?php $valor = $op['grupo'] . '|' . $op['horario']; ?>
                                <?php $lugaresRestantes = 30 - $op['cantidad']; ?>
                                <option value="<?php echo htmlspecialchars($valor); ?>">
                                    <?php echo htmlspecialchars($op['grupo'] . ' - ' . $op['horario']); ?>
                                    (<?php echo $lugaresRestantes; ?> lugares disponibles)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Selecciona un grupo y horario con cupo disponible</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="panel_admin.php" class="btn btn-secondary px-4">Cancelar</a>
                <button type="submit" class="btn btn-success px-4">Registrar Alumno</button>
            </div>
        </form>

        <div id="resultadoRegistro" class="d-none alert alert-success mt-4" role="alert"></div>
    </main>

    <footer>
        <div class="container d-flex justify-content-between align-items-center py-3">
            <img src="imgs/IPN-Logo.png" alt="Logo IPN" style="max-height: 65px;">
            <div class="contenedor-central-footer">
                <div class="bloque-texto">
                    <p class="mb-0">Instituto Politécnico Nacional - ESCOM &copy; 2026</p>
                    <small>Desarrollado para la materia Tecnologías para el Desarrollo de Aplicaciones Web</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <img src="imgs/Logo_Equipo.png" alt="Logo del equipo" style="max-height: 65px;">
                <img src="imgs/logoESCOMBlanco.png" alt="Logo ESCOM" style="max-height: 65px;">
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/registro_admin.js"></script>
</body>
</html>