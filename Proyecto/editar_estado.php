<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';

if (empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID de pedido no proporcionado.";
    exit;
}

$id = intval($_GET['id']);

// Obtener el pedido actual
$stmt = $pdo->prepare("SELECT id, estado, paqueteria, guia_seguimiento FROM pedidos WHERE id = ?");
$stmt->execute([$id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo "Pedido no encontrado.";
    exit;
}

// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estado = $_POST['estado'] ?? $pedido['estado'];
    $paqueteria = $_POST['paqueteria'] ?? null;
    $guia = $_POST['guia'] ?? null;

    $stmt = $pdo->prepare("
        UPDATE pedidos 
        SET estado = ?, paqueteria = ?, guia_seguimiento = ?
        WHERE id = ?
    ");
    $stmt->execute([$nuevo_estado, $paqueteria, $guia, $id]);

    header("Location: gestionar_pedidos.php");
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
  <h2 class="section__title">Actualizar Pedido #<?= $pedido['id'] ?></h2>

  <form method="POST">
    <!-- Estado -->
    <label for="estado">Estado del pedido:</label>
    <select name="estado" id="estado" required>
      <?php
      $estados = ['Pendiente', 'Pagado', 'Enviado', 'Cancelado'];
      foreach ($estados as $estado) {
        $selected = $estado === $pedido['estado'] ? 'selected' : '';
        echo "<option value=\"$estado\" $selected>$estado</option>";
      }
      ?>
    </select>

    <!-- Paquetería -->
    <label for="paqueteria">Paquetería:</label>
    <select name="paqueteria" id="paqueteria">
      <option value="">-- Selecciona --</option>
      <?php
      $opciones = ['FedEx', 'Estafeta', 'UPS'];
      foreach ($opciones as $op) {
        $sel = ($pedido['paqueteria'] === $op) ? 'selected' : '';
        echo "<option value=\"$op\" $sel>$op</option>";
      }
      ?>
    </select>

    <!-- Número de seguimiento -->
    <label for="guia">Número de seguimiento:</label>
    <input type="text" name="guia" id="guia" 
           value="<?= htmlspecialchars($pedido['guia_seguimiento']) ?>" 
           placeholder="Ej: 1234567890">

    <button type="submit" class="btn btn--primary">Actualizar</button>
    <a href="gestionar_pedidos.php" class="btn btn--secondary">Cancelar</a>
  </form>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
