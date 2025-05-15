<?php
require_once 'includes/conexion.php';
session_start();

// Verificar sesión y carrito
if (!isset($_SESSION['id']) || empty($_SESSION['cart'])) {
    header("Location: carrito.php");
    exit();
}

$usuarioId = $_SESSION['id'];
$carrito = $_SESSION['cart'];

// Calcular total
$total = 0;
$productos = [];

$ids = array_keys($carrito);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, precio FROM productos WHERE id IN ($placeholders)");
$stmt->execute($ids);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $producto) {
    $id = $producto['id'];
    $precio = $producto['precio'];
    $cantidad = $carrito[$id];
    $total += $precio * $cantidad;

    $productos[] = [
        'id' => $id,
        'cantidad' => $cantidad,
        'precio' => $precio
    ];
}

// Insertar pedido
$stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
$stmt->execute([$usuarioId, $total]);
$pedidoId = $pdo->lastInsertId();

// Insertar detalle
$stmtDetalle = $pdo->prepare("INSERT INTO pedido_detalle (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
foreach ($productos as $p) {
    $stmtDetalle->execute([$pedidoId, $p['id'], $p['cantidad'], $p['precio']]);
}

// Limpiar carrito
unset($_SESSION['cart']);

// Redirigir a pasarela de pago con total dinámico
header("Location: Pasarela-urbanj.html?total=" . number_format($total, 2, '.', ''));
exit();
