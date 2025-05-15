<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/conexion.php';

if (!$is_admin) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: admin_banners.php');
exit;
?>
