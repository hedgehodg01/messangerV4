<?php require 'db.php';
// Выход из системы
if (isset($_GET['logout'])) { session_destroy(); header("Location: login.php"); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id']; // Тот самый ID
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else { $error = 'Неверный логин или пароль'; }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?v=<?=time()?>">
    <title>VOX - Вход</title>
</head>
<body>
    <div class="auth-container">
        <form method="POST" class="auth-box">
            <div class="auth-logo">VOX</div>
            <div class="auth-title">Авторизация</div>
            <?php if(isset($_GET['reg'])): ?><p style="color:#bb86fc; margin-bottom:15px;">Регистрация успешна!</p><?php endif; ?>
            <?php if($error): ?><p style="color:#ff5252; margin-bottom:15px;"><?= $error ?></p><?php endif; ?>
            <div class="input-group"><input type="text" name="username" placeholder="Логин" class="auth-input" required></div>
            <div class="input-group"><input type="password" name="password" placeholder="Пароль" class="auth-input" required></div>
            <button type="submit" class="btn">Войти</button>
            <div class="auth-link">Нет аккаунта? <a href="register.php">Создать аккаунт</a></div>
        </form>
    </div>
</body>
</html>