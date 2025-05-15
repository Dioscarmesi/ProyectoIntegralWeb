<?php
require_once 'includes/conexion.php';
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header('Location: index.php');
    exit();
}

// Acciones de administrador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    if (isset($_POST['toggle_admin'])) {
        $stmt = $pdo->prepare("UPDATE usuarios SET admin = NOT admin WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['delete_user']) && (!isset($_SESSION['id']) || $id != $_SESSION['id'])) {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Obtener lista de usuarios
$stmt = $pdo->query("SELECT id, usuario, admin FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/stylos.php'; ?>

<div class="container">
    <h2>Gestión de Usuarios</h2>
    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Administrador</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['usuario']) ?></td>
                    <td><?= $u['admin'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button type="submit" name="toggle_admin">Cambiar rol</button>
                        </form>

                        <?php if (!isset($_SESSION['id']) || $u['id'] != $_SESSION['id']): ?>
                            <form method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que quieres borrar este usuario?');">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit" name="delete_user">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
