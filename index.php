<?php require 'db.php'; if (!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>VOX Messenger</title>
    <link rel="stylesheet" href="style.css?v=<?=time()?>">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div style="padding:20px;" class="logo">VOX</div>
            <button class="btn" style="margin: 0 20px 20px;" onclick="createChat()">+ НОВЫЙ ЧАТ</button>
            <div id="chat-list" class="chat-list"></div>
            <div style="margin-top:auto; padding:20px; border-top:1px solid #222; cursor:pointer" onclick="toggleModal(true)">
                Профиль (<?= $_SESSION['username'] ?>)
            </div>
        </aside>

        <main class="main-chat">
            <div id="messages-list"></div>
            <div class="input-area">
                <input type="text" id="msg-input" placeholder="Введите сообщение..." autocomplete="off">
                <button id="send-btn" class="btn">➤</button>
            </div>
        </main>
    </div>

    <div id="profile-modal" class="modal">
        <div class="auth-box">
            <h2>Профиль</h2>
            <button class="btn" onclick="toggleModal(false)" style="width:100%; margin-top:20px">ЗАКРЫТЬ</button>
            <a href="login.php?logout=1" style="display:block; margin-top:20px; color:red">Выйти</a>
        </div>
    </div>

    <script>const MY_ID = <?= $_SESSION['user_id'] ?>;</script>
    <script src="app.js?v=<?=time()?>"></script>
</body>
</html>