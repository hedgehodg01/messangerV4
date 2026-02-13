<?php require 'db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$user, $pass]);
        header("Location: login.php?reg=ok");
    } catch (Exception $e) { $error = "Этот логин уже занят"; }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?v=<?=time()?>">
    <title>VOX - Регистрация</title>
</head>
<body>
    <div class="auth-container">
        <form method="POST" class="auth-box">
            <div class="auth-logo">VOX</div>
            <div class="auth-title">Создание аккаунта</div>
            <?php if($error): ?><p style="color:#ff5252; margin-bottom:15px;"><?= $error ?></p><?php endif; ?>
            <div class="input-group"><input type="text" name="username" placeholder="Придумайте логин" class="auth-input" required></div>
            <div class="input-group"><input type="password" name="password" placeholder="Придумайте пароль" class="auth-input" required></div>
            <button type="submit" class="btn">Зарегистрироваться</button>
            <div class="auth-link">Уже есть аккаунт? <a href="login.php">Войти</a></div>
        </form>
    </div>
</body>
</html>