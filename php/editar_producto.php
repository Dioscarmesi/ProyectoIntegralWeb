<?php
include('conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del producto
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $cantidad = $_POST['cantidad'] ?: null;  // Si no se proporciona cantidad, es NULL
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $estrellas = $_POST['estrellas'];

        // Actualizar el producto
        $sql_update = "UPDATE productos SET nombre = ?, cantidad = ?, precio = ?, descripcion = ?, estrellas = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("siissi", $nombre, $cantidad, $precio, $descripcion, $estrellas, $id);

        if ($stmt_update->execute()) {
            // Si se suben nuevas imágenes
            if (isset($_FILES['imagenes'])) {
                $imagenes = $_FILES['imagenes'];
                for ($i = 0; $i < count($imagenes['name']); $i++) {
                    $imagen_nombre = $imagenes['name'][$i];
                    $imagen_temp = $imagenes['tmp_name'][$i];
                    $ruta_imagen = "imagenes/" . $imagen_nombre;

                    // Mover la imagen a la carpeta del servidor
                    move_uploaded_file($imagen_temp, $ruta_imagen);

                    // Insertar nueva imagen
                    $sql_imagen = "INSERT INTO imagenes_productos (producto_id, imagen) VALUES (?, ?)";
                    $stmt_imagen = $conn->prepare($sql_imagen);
                    $stmt_imagen->bind_param("is", $id, $ruta_imagen);
                    $stmt_imagen->execute();
                }
            }

            echo "Producto actualizado con éxito.";
        } else {
            echo "Error: " . $stmt_update->error;
        }
    }
} else {
    echo "Producto no encontrado.";
}

$conn->close();
?>

<!-- Formulario HTML para editar el producto -->
<form action="editar_producto.php?id=<?php echo $producto['id']; ?>" method="POST" enctype="multipart/form-data">
    <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>" required><br>
    <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>"><br>
    <input type="number" name="precio" value="<?php echo $producto['precio']; ?>" required><br>
    <textarea name="descripcion" required><?php echo $producto['descripcion']; ?></textarea><br>
    <select name="estrellas" required>
        <option value="1" <?php echo $producto['estrellas'] == 1 ? 'selected' : ''; ?>>1 Estrella</option>
        <option value="2" <?php echo $producto['estrellas'] == 2 ? 'selected' : ''; ?>>2 Estrellas</option>
        <option value="3" <?php echo $producto['estrellas'] == 3 ? 'selected' : ''; ?>>3 Estrellas</option>
        <option value="4" <?php echo $producto['estrellas'] == 4 ? 'selected' : ''; ?>>4 Estrellas</option>
        <option value="5" <?php echo $producto['estrellas'] == 5 ? 'selected' : ''; ?>>5 Estrellas</option>
    </select><br>
    <input type="file" name="imagenes[]" multiple><br>
    <button type="submit">Actualizar Producto</button>
</form>
