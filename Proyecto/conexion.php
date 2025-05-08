<?php
/**
 * Archivo: conexion.php
 * Configuración de la conexión a la base de datos mediante PDO.
 */

$host   = '127.0.0.1';    // Servidor MySQL (localhost en entornos locales)
$dbname = 'urbanj';       // Nombre de la base de datos
$user   = 'root';         // Usuario de la base de datos
$pass   = '';             // Contraseña del usuario (en XAMPP suele ir vacía)
$charset = 'utf8mb4';     // Conjunto de caracteres recomendado

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // En producción: registrar en log y mostrar mensaje genérico
    die('Error de conexión: ' . $e->getMessage());
}
