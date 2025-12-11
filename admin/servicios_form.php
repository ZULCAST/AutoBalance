<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . "/../config/db.php";

$id = $_GET['id'] ?? null;
$servicio = [
    'nombre' => '',
    'descripcion' => '',
    'precio_desde' => '',
    'activo' => 1
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$servicio) {
        die("Servicio no encontrado");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio_desde = $_POST['precio_desde'] === '' ? null : floatval($_POST['precio_desde']);
    $activo = isset($_POST['activo']) ? 1 : 0;

    if ($id) {
        $sql = "UPDATE servicios
                SET nombre = :nombre, descripcion = :descripcion, precio_desde = :precio_desde, activo = :activo
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio_desde' => $precio_desde,
            ':activo' => $activo,
            ':id' => $id
        ]);
    } else {
        $sql = "INSERT INTO servicios (nombre, descripcion, precio_desde, activo)
                VALUES (:nombre, :descripcion, :precio_desde, :activo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio_desde' => $precio_desde,
            ':activo' => $activo
        ]);
    }
    header('Location: servicios_listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id ? 'Editar' : 'Nuevo'; ?> servicio</title>
</head>
<body>
<h1><?php echo $id ? 'Editar' : 'Nuevo'; ?> servicio</h1>
<p><a href="servicios_listar.php">Volver a la lista</a></p>

<form method="post">
    <label>Nombre:<br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($servicio['nombre']); ?>" required>
    </label><br><br>

    <label>Descripción:<br>
        <textarea name="descripcion" rows="4" cols="40"><?php
            echo htmlspecialchars($servicio['descripcion']);
        ?></textarea>
    </label><br><br>

    <label>Precio desde (opcional):<br>
        <input type="number" step="0.01" name="precio_desde"
               value="<?php echo htmlspecialchars($servicio['precio_desde']); ?>">
    </label><br><br>

    <label>
        <input type="checkbox" name="activo" <?php echo $servicio['activo'] ? 'checked' : ''; ?>>
        Activo
    </label><br><br>

    <button type="submit">Guardar</button>
</form>
</body>
</html>
