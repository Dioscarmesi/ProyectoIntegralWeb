<?php
// login.php
session_start();
require __DIR__ . '/includes/conexion.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['usuario']  ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = 'Rellena todos los campos';
    } else {
        // Ahora seleccionamos también el campo `admin`
        $stmt = $pdo->prepare('SELECT id, password, admin FROM usuarios WHERE usuario = ?');
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['usuario'] = $usuario;
            // Guardamos el rol admin (0 o 1)
            $_SESSION['admin']   = (int)$user['admin'];

            // Redirigimos al index
            header('Location: index.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Iniciar sesión • UrbanJ</title>
  <link rel="stylesheet" href="css/LoginRegister.css">
</head>
<body class="login">

  <div class="login-wrapper">
    <div class="login-promo">
      <h2>50% OFF</h2>
      <p>for new users</p>
      <span class="coupon-code">NEWUSER</span>
    </div>

    <div class="login-form-container">
      <h2>Iniciar sesión</h2>
      <?php if ($error): ?>
        <div class="form-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="login.php" method="post" novalidate>
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" placeholder="Tu usuario"
               value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu contraseña" required>

        <button type="submit">Entrar</button>
      </form>

      <div class="register-link">
        ¿No tienes cuenta? <a href="RegistrarUsuario.php">Regístrate</a>
      </div>
    </div>
  </div>

</body>
</html>
