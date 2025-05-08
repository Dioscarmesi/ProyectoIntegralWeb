<?php
include('conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Primero, eliminamos las imágenes asociadas al producto
    $sql_imagenes = "SELECT imagen FROM imagenes_productos WHERE producto_id = ?";
    $stmt_imagenes = $conn->prepare($sql_imagenes);
    $stmt_imagenes->bind_param("i", $id);
    $stmt_imagenes->execute();
    $result_imagenes = $stmt_imagenes->get_result();

    while ($img = $result_imagenes->fetch_assoc()) {
        // Eliminar imagen del servidor
        unlink($img['imagen']);
    }

    // Eliminar las imágenes de la base de datos
    $sql_delete_imagenes = "DELETE FROM imagenes_productos WHERE producto_id = ?";
    $stmt_delete_imagenes = $conn->prepare($sql_delete_imagenes);
    $stmt_delete_imagenes->bind_param("i", $id);
    $stmt_delete_imagenes->execute();

    // Ahora eliminamos el producto
    $sql_delete_producto = "DELETE FROM productos WHERE id = ?";
    $stmt_delete_producto = $conn->prepare($sql_delete_producto);
    $stmt_delete_producto->bind_param("i", $id);

    if ($stmt_delete_producto->execute()) {
        echo "Producto y sus imágenes han sido eliminados con éxito.";
    } else {
        echo "Error al eliminar el producto: " . $stmt_delete_producto->error;
    }
} else {
    echo "Producto no encontrado.";
}

$conn->close();
?>
