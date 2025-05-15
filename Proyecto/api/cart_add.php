<?php
// /UrbanJ/api/cart_add.php
session_start();
header('Content-Type: application/json');

// 1. Leer el cuerpo
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// Fallback a $_POST si no vino JSON
if (!is_array($data)) {
    $data = $_POST;
}

$id  = isset($data['id'])  ? intval($data['id'])  : 0;
$qty = isset($data['qty']) ? intval($data['qty']) : 1; // por defecto 1

if ($id > 0) {

    // Inicializar carrito si no existe
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Si ya existe el producto, reemplazamos cantidad si viene qty,
    // de lo contrario simplemente sumamos 1
    if ($qty > 0) {
        $_SESSION['cart'][$id] = $qty;
    } else {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    }

    // 3. Total de unidades en carrito
    $totalQty = array_sum($_SESSION['cart']);
    echo json_encode(['success' => true, 'totalQty' => $totalQty]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'ID inválido']);
