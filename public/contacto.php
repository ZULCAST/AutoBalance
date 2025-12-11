<?php
require_once __DIR__ . "/../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if ($nombre !== '' && $mensaje !== '') {
        $sql = "INSERT INTO contactos_web (nombre, telefono, email, mensaje)
                VALUES (:nombre, :telefono, :email, :mensaje)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre'   => $nombre,
            ':telefono' => $telefono,
            ':email'    => $email,
            ':mensaje'  => $mensaje
        ]);
        header('Location: index.php?ok=1#contacto');
        
        exit;
    } else {
        header('Location: index.php?error=1#contacto');
        exit;
    }
}
