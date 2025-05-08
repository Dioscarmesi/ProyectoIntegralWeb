<?php
// RegistrarUsuario.php
session_start();
require __DIR__ . '/includes/conexion.php';

// Inicializo todas las variables usadas en el formulario
$nombre           = '';
$apellido_paterno = '';
$apellido_materno = '';
$usuario          = '';
$correo           = '';
$password         = '';
$fecha_nacimiento = '';
$pais             = '';
$estado           = '';
$ciudad           = '';
$direccion1       = '';
$direccion2       = '';
$codigo_postal    = '';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sobrescribo con lo que venga del POST
    $nombre           = trim($_POST['nombre']          ?? '');
    $apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
    $apellido_materno = trim($_POST['apellido_materno'] ?? '');
    $usuario          = trim($_POST['usuario']         ?? '');
    $correo           = trim($_POST['correo']          ?? '');
    $password         = $_POST['password']             ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento']     ?? '';
    $pais             = trim($_POST['pais']            ?? '');
    $estado           = trim($_POST['estado']          ?? '');
    $ciudad           = trim($_POST['ciudad']          ?? '');
    $direccion1       = trim($_POST['direccion1']      ?? '');
    $direccion2       = trim($_POST['direccion2']      ?? '');
    $codigo_postal    = trim($_POST['codigo_postal']   ?? '');

    // validación mínima
    if (empty($nombre) || empty($apellido_paterno) || empty($usuario) || empty($correo) || empty($password)) {
        $error = 'Rellena los campos obligatorios (*)';
    } else {
        // comprueba duplicados
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE usuario = ? OR correo = ?');
        $stmt->execute([$usuario, $correo]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'El usuario o correo ya existen';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("
                INSERT INTO usuarios 
                  (nombre, apellido_paterno, apellido_materno, usuario, pais,
                   estado, ciudad, direccion1, direccion2, codigo_postal,
                   correo, password, fecha_nacimiento)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");
            $ins->execute([
              $nombre, $apellido_paterno, $apellido_materno, $usuario, $pais,
              $estado, $ciudad, $direccion1, $direccion2, $codigo_postal,
              $correo, $hash, $fecha_nacimiento
            ]);
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registrarse • UrbanJ</title>
  <link rel="stylesheet" href="css/LoginRegister.css">
</head>
<body class="register">

  <div class="login-wrapper">
    <div class="login-promo">
      <h2>Únete Hoy</h2>
      <p>50% OFF para nuevos usuarios</p>
      <span class="coupon-code">NEWUSER</span>
    </div>

    <div class="login-form-container">
      <h2>Crear cuenta</h2>
      <?php if ($error): ?>
        <div class="form-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="RegistrarUsuario.php" method="post" novalidate>
        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" required
               value="<?= htmlspecialchars($nombre) ?>">

        <label for="apellido_paterno">Apellido Paterno *</label>
        <input type="text" id="apellido_paterno" name="apellido_paterno" required
               value="<?= htmlspecialchars($apellido_paterno) ?>">

        <label for="apellido_materno">Apellido Materno</label>
        <input type="text" id="apellido_materno" name="apellido_materno"
               value="<?= htmlspecialchars($apellido_materno) ?>">

        <label for="usuario">Usuario *</label>
        <input type="text" id="usuario" name="usuario" required
               value="<?= htmlspecialchars($usuario) ?>">

        <label for="correo">Correo *</label>
        <input type="email" id="correo" name="correo" required
               value="<?= htmlspecialchars($correo) ?>">

        <label for="password">Contraseña *</label>
        <input type="password" id="password" name="password" required>

        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
               value="<?= htmlspecialchars($fecha_nacimiento) ?>">

        <label for="pais">País</label>
        <input type="text" id="pais" name="pais"
               value="<?= htmlspecialchars($pais) ?>">

        <label for="estado">Estado/Provincia</label>
        <input type="text" id="estado" name="estado"
               value="<?= htmlspecialchars($estado) ?>">

        <label for="ciudad">Ciudad</label>
        <input type="text" id="ciudad" name="ciudad"
               value="<?= htmlspecialchars($ciudad) ?>">

        <label for="direccion1">Dirección 1</label>
        <input type="text" id="direccion1" name="direccion1"
               value="<?= htmlspecialchars($direccion1) ?>">

        <label for="direccion2">Dirección 2</label>
        <input type="text" id="direccion2" name="direccion2"
               value="<?= htmlspecialchars($direccion2) ?>">

        <label for="codigo_postal">Código Postal</label>
        <input type="text" id="codigo_postal" name="codigo_postal"
               value="<?= htmlspecialchars($codigo_postal) ?>">

        <button type="submit">Registrarse</button>
      </form>

      <div class="register-link">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
      </div>
    </div>
  </div>

</body>
</html>
