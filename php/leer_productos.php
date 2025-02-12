<?php
include('conexion.php');

// Obtener todos los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mostrar todos los productos
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h2>" . $row['nombre'] . "</h2>";
        echo "<p>Precio: $" . $row['precio'] . "</p>";
        echo "<p>Cantidad en stock: " . ($row['cantidad'] ? $row['cantidad'] : 'Desconocida') . "</p>";
        echo "<p>Descripción: " . $row['descripcion'] . "</p>";
        echo "<p>Estrellas: " . $row['estrellas'] . " Estrellas</p>";

        // Obtener las imágenes del producto
        $sql_imagenes = "SELECT imagen FROM imagenes_productos WHERE producto_id = ?";
        $stmt_imagenes = $conn->prepare($sql_imagenes);
        $stmt_imagenes->bind_param("i", $row['id']);
        $stmt_imagenes->execute();
        $result_imagenes = $stmt_imagenes->get_result();

        echo "<div>Imágenes: ";
        while ($img = $result_imagenes->fetch_assoc()) {
            echo "<img src='" . $img['imagen'] . "' alt='" . $row['nombre'] . "' width='100'>";
        }
        echo "</div>";

        echo "<a href='editar_producto.php?id=" . $row['id'] . "'>Editar</a> | ";
        echo "<a href='borrar_producto.php?id=" . $row['id'] . "'>Borrar</a>";
        echo "</div>";
    }
} else {
    echo "No hay productos.";
}

$conn->close();
?>
