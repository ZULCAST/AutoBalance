<?php
require_once __DIR__ . "/../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $pass   = trim($_POST['password'] ?? '');

    if ($nombre !== '' && $email !== '' && $pass !== '') {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, email, password_hash, rol)
                VALUES (:nombre, :email, :password_hash, 'admin')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':email'  => $email,
            ':password_hash' => $hash
        ]);

        echo "Usuario admin creado. Ya puedes entrar a login.php<br>";
        exit;
    } else {
        echo "Llena todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Admin</title>
</head>
<body>
<h1>Registrar usuario admin</h1>
<form method="post">
    <label>Nombre:<br>
        <input type="text" name="nombre">
    </label><br><br>
    <label>Correo:<br>
        <input type="email" name="email">
    </label><br><br>
    <label>Contraseña:<br>
        <input type="password" name="password">
    </label><br><br>
    <button type="submit">Crear admin</button>
</form>
</body>
</html>
