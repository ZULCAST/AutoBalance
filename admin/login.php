<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pass, $user['password_hash'])) {
        $_SESSION['usuario_id']   = $user['id'];
        $_SESSION['usuario_name'] = $user['nombre'];
        $_SESSION['usuario_rol']  = $user['rol'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Correo o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | Autobalance</title>
</head>
<body>
<h1>Login Autobalance</h1>
<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="post">
    <label>Correo:<br>
        <input type="email" name="email" required>
    </label><br><br>
    <label>Contraseña:<br>
        <input type="password" name="password" required>
    </label><br><br>
    <button type="submit">Entrar</button>
</form>
</body>
</html>
