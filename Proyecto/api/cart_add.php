<?php
// /xampp/htdocs/UrbanJ/api/cart_add.php

session_start();
header('Content-Type: application/json');

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success'=>false,'msg'=>'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['id']) || !ctype_digit((string)$input['id'])) {
    http_response_code(400);
    echo json_encode(['success'=>false,'msg'=>'ID inválido']);
    exit;
}

$id  = (int)$input['id'];
$qty = isset($input['qty']) && ctype_digit((string)$input['qty']) ? (int)$input['qty'] : 1;

// Inicializa carrito
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Añade o incrementa
if (!isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = 0;
}
$_SESSION['cart'][$id] += $qty;

// Cuenta totales
$totalQty = array_sum($_SESSION['cart']);

echo json_encode(['success'=>true,'totalQty'=>$totalQty]);
