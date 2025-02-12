<?php
// Iniciar sesión
// ESTO adrian usarlo cuando el usaurio ya tiene articulos en su cuenta guarados y cuando cambie de pagina no borre nada
session_start();

// Tiempo de inactividad (en segundos) antes de cerrar sesión automáticamente
$tiempo_maximo_inactividad = 30 * 60; // 30 minutos

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $tiempo_maximo_inactividad) {
    session_unset();     // Eliminar todas las variables de sesión
    session_destroy();   // Destruir la sesión
    header("Location: login.php"); // Redirigir al usuario al login (puedes cambiar esto si quieres otra página)
    exit();  // Asegúrate de que el script termine aquí
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
