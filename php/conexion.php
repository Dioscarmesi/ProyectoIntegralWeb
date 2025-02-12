<?php
$servername = "localhost";  // El servidor donde está la base de datos, generalmente localhost
$username = "root";         // El nombre de usuario de la base de datos
$password = "";             // La contraseña de la base de datos
$dbname = "mi_base_de_datos"; // El nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres para la conexión (UTF-8)
$conn->set_charset("utf8");

?>
