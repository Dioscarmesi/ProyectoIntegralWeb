<?php
session_start();
header('Content-Type: application/json');

$total = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $total = array_sum($_SESSION['cart']);
}

echo json_encode(['total' => $total]);
