<?php
// includes/conexion.php
$host     = '127.0.0.1';    // o 'localhost'
$dbname   = 'urbanj';       // tu base de datos
$user     = 'root';         // usuario por defecto en XAMPP
$password = '';             // contraseña vacía

$charset  = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    exit('Error de conexión a la base de datos: ' . $e->getMessage());
}
