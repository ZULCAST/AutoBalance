<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . "/../config/db.php";

$sql = "SELECT * FROM servicios ORDER BY id ASC";
$servicios = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios | Autobalance</title>
</head>
<body>
<h1>Servicios</h1>
<p><a href="dashboard.php">Volver al panel</a> | <a href="servicios_form.php">Nuevo servicio</a></p>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio desde</th>
        <th>Activo</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($servicios as $s): ?>
        <tr>
            <td><?php echo $s['id']; ?></td>
            <td><?php echo htmlspecialchars($s['nombre']); ?></td>
            <td><?php echo is_null($s['precio_desde']) ? '-' : '$'.number_format($s['precio_desde'],2); ?></td>
            <td><?php echo $s['activo'] ? 'Sí' : 'No'; ?></td>
            <td>
                <a href="servicios_form.php?id=<?php echo $s['id']; ?>">Editar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
