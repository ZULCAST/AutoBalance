<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . "/../config/db.php";

$servicios_count = $pdo->query("SELECT COUNT(*) FROM servicios")->fetchColumn();
$mensajes_count  = $pdo->query("SELECT COUNT(*) FROM contactos_web")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel | Autobalance</title>
</head>
<body>
<h1>Panel de administración</h1>
<p>Hola, <?php echo htmlspecialchars($_SESSION['usuario_name']); ?></p>

<ul>
    <li>Total de servicios: <?php echo $servicios_count; ?> (<a href="servicios_listar.php">ver</a>)</li>
    <li>Mensajes recibidos: <?php echo $mensajes_count; ?></li>
</ul>

<p><a href="logout.php">Cerrar sesión</a></p>
</body>
</html>
