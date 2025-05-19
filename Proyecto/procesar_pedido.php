<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/includes/conexion.php';

$input = json_decode(file_get_contents('php://input'), true);
$usuarioId = $_SESSION['user_id'] ?? null;
$carrito = $_SESSION['cart'] ?? [];
$transactionId = $input['transaction_id'] ?? null;

if (!$usuarioId || empty($carrito) || !$transactionId) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$pdo->beginTransaction();

try {
    // 1. Obtener el último ID de pedidos
    $stmt = $pdo->query("SELECT MAX(id) as max_id FROM pedidos");
    $lastPedidoId = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'] ?? 0;
    $nuevoPedidoId = $lastPedidoId + 1;

    // 2. Obtener productos del carrito
    $ids = array_keys($carrito);
    $in = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT id, precio FROM productos WHERE id IN ($in)");
    $stmt->execute($ids);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Calcular total y preparar detalles
    $total = 0;
    $detalleData = [];
    foreach ($productos as $p) {
        $id = $p['id'];
        $precio = $p['precio'];
        $cantidad = $carrito[$id];
        $total += $precio * $cantidad;
        $detalleData[] = [
            'producto_id' => $id,
            'cantidad' => $cantidad,
            'precio_unitario' => $precio
        ];
    }

    // 4. Insertar pedido principal incluyendo guia_seguimiento
    $stmt = $pdo->prepare("INSERT INTO pedidos 
        (id, usuario_id, total, estado, creado_at, actualizado_at, guia_seguimiento) 
        VALUES (?, ?, ?, 'pagado', NOW(), NOW(), ?)");
    $stmt->execute([
        $nuevoPedidoId,
        $usuarioId,
        $total,
        $transactionId
    ]);

    // 5. Obtener último ID de pedido_detalle
    $stmt = $pdo->query("SELECT MAX(id) as max_id FROM pedido_detalle");
    $lastDetalleId = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'] ?? 0;
    $nuevoDetalleId = $lastDetalleId + 1;

    // 6. Insertar detalles del pedido
    $stmtDetalle = $pdo->prepare("INSERT INTO pedido_detalle 
        (id, pedido_id, producto_id, cantidad, precio_unitario) 
        VALUES (?, ?, ?, ?, ?)");

    foreach ($detalleData as $item) {
        $stmtDetalle->execute([
            $nuevoDetalleId++,
            $nuevoPedidoId,
            $item['producto_id'],
            $item['cantidad'],
            $item['precio_unitario']
        ]);
    }

    $pdo->commit();

    unset($_SESSION['cart']);

    echo json_encode([
        'success' => true, 
        'pedido_id' => $nuevoPedidoId,
        'total' => $total
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al crear pedido: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error al crear el pedido',
        'error' => $e->getMessage()
    ]);
}
