<?php
// api/cart_remove.php
session_start();
header('Content-Type: application/json');

$in = json_decode(file_get_contents('php://input'), true);
$id = (int)($in['id'] ?? 0);
if ($id && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

// Recalcular totalQty
$totalQty = array_sum($_SESSION['cart'] ?? []);
echo json_encode(['success'=>true, 'totalQty'=>$totalQty]);
