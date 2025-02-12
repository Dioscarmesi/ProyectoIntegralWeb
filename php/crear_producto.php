<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'] ?: null;  // Si no se proporciona cantidad, es NULL
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $estrellas = $_POST['estrellas'];

    // Insertar el producto en la base de datos
    $sql = "INSERT INTO productos (nombre, cantidad, precio, descripcion, estrellas)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiss", $nombre, $cantidad, $precio, $descripcion, $estrellas);

    if ($stmt->execute()) {
        $producto_id = $stmt->insert_id;  // Obtener el ID del producto recién creado

        // Subir imágenes y asociarlas al producto
        if (isset($_FILES['imagenes'])) {
            $imagenes = $_FILES['imagenes'];

            for ($i = 0; $i < count($imagenes['name']); $i++) {
                $imagen_nombre = $imagenes['name'][$i];
                $imagen_temp = $imagenes['tmp_name'][$i];
                $ruta_imagen = "imagenes/" . $imagen_nombre;

                // Mover la imagen a la carpeta del servidor
                move_uploaded_file($imagen_temp, $ruta_imagen);

                // Insertar la imagen en la base de datos
                $sql_imagen = "INSERT INTO imagenes_productos (producto_id, imagen) VALUES (?, ?)";
                $stmt_imagen = $conn->prepare($sql_imagen);
                $stmt_imagen->bind_param("is", $producto_id, $ruta_imagen);
                $stmt_imagen->execute();
            }
        }

        echo "Producto creado con éxito.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Formulario HTML para crear un producto -->
<form action="crear_producto.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre del producto" required><br>
    <input type="number" name="cantidad" placeholder="Cantidad en stock (dejar en blanco si desconocido)"><br>
    <input type="number" name="precio" placeholder="Precio" required><br>
    <textarea name="descripcion" placeholder="Descripción del producto" required></textarea><br>
    <select name="estrellas" required>
        <option value="1">1 Estrella</option>
        <option value="2">2 Estrellas</option>
        <option value="3">3 Estrellas</option>
        <option value="4">4 Estrellas</option>
        <option value="5">5 Estrellas</option>
    </select><br>
    
    <!-- Input para múltiples imágenes -->
    <input type="file" name="imagenes[]" multiple><br>
    
    <button type="submit">Crear Producto</button>
</form>
