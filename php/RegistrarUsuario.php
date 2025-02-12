<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tu_base_de_datos";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $usuario = $_POST['usuario'];
    $pais = $_POST['pais'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];
    $direccion1 = $_POST['direccion1'];
    $direccion2 = $_POST['direccion2'];
    $codigo_postal = $_POST['codigo_postal'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $admin = 0; // Asumimos que el nuevo usuario no es admin. Cambiar a 1 si el usuario es admin.

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, usuario, pais, estado, ciudad, direccion1, direccion2, codigo_postal, correo, password, fecha_nacimiento, admin)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $nombre, $apellido_paterno, $apellido_materno, $usuario, $pais, $estado, $ciudad, $direccion1, $direccion2, $codigo_postal, $correo, $hashed_password, $fecha_nacimiento, $admin);

    if ($stmt->execute()) {
        echo "¡Usuario registrado con éxito!";
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
