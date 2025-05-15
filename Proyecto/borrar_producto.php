<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';

if (!isset($_GET['id'])) {
    echo "Producto no encontrado.";
    exit;
}

$id = intval($_GET['id']);

// Eliminar imágenes asociadas
$imagenes = $pdo->prepare("SELECT imagen_url FROM imagenes_producto WHERE producto_id = ?");
$imagenes->execute([$id]);
foreach ($imagenes->fetchAll(PDO::FETCH_COLUMN) as $img) {
    @unlink(__DIR__ . '/' . $img);
}

// Borrar imágenes de la base de datos
$pdo->prepare("DELETE FROM imagenes_producto WHERE producto_id = ?")->execute([$id]);

// Borrar el producto
$stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
if ($stmt->execute([$id])) {
    header('Location: inventario.php');
    exit;
} else {
    echo "Error al eliminar el producto.";
}
?>
