<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/conexion.php';

// Verificación de administrador
if (!$is_admin) {
    header('Location: index.php');
    exit;
}

// Obtener banners
$stmt = $pdo->query("SELECT * FROM banners ORDER BY orden ASC");
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/adminBanners.css">

<main class="admin-banners">
    <h1>Gestión de Banners</h1>

    <a href="crear_banner.php" class="btn btn-primary">➕ Nuevo Banner</a>

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Enlace</th>
                <th>Texto ALT</th>
                <th>Orden</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($banners as $banner): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($banner['imagen_url']) ?>" alt="<?= htmlspecialchars($banner['texto_alt']) ?>" width="100"></td>
                <td><?= htmlspecialchars($banner['enlace']) ?></td>
                <td><?= htmlspecialchars($banner['texto_alt']) ?></td>
                <td><?= $banner['orden'] ?></td>
                <td>
                    <a href="editar_banner.php?id=<?= $banner['id'] ?>">✏️ Editar</a> |
                    <a href="eliminar_banner.php?id=<?= $banner['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este banner?')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

