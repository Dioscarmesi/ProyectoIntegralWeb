<?php
require_once 'includes/conexion.php';
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header('Location: index.php');
    exit();
}

// Acciones de moderación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    if (isset($_POST['aprobar'])) {
        $stmt = $pdo->prepare("UPDATE resenas SET estado = 1 WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['eliminar'])) {
        $stmt = $pdo->prepare("DELETE FROM resenas WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Consultar reseñas
$stmt = $pdo->query("
    SELECT r.id, r.comentario, r.calificacion, r.fecha_resena, r.estado,
           u.usuario, p.nombre AS producto
    FROM resenas r
    JOIN usuarios u ON r.usuario_id = u.id
    JOIN productos p ON r.producto_id = p.id
    ORDER BY r.fecha_resena DESC
");

$resenas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/stylos.php'; ?>

<div class="container">
    <h2>Moderación de Reseñas</h2>
    <table class="tabla-resenas">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Calificación</th>
                <th>Comentario</th>
                <th>Fecha</th>
                <th>Aprobado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resenas as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['usuario']) ?></td>
                    <td><?= htmlspecialchars($r['producto']) ?></td>
                    <td><?= $r['calificacion'] ?>/5</td>
                    <td><?= htmlspecialchars($r['comentario']) ?></td>
                    <td><?= $r['fecha_resena'] ?></td>
                    <td><?= $r['estado'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <?php if (!$r['estado']): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button type="submit" name="aprobar">Aprobar</button>
                            </form>
                        <?php endif; ?>
                        <form method="post" style="display:inline;" onsubmit="return confirm('¿Eliminar esta reseña?');">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <button type="submit" name="eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
