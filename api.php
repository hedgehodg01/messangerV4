<?php
require 'db.php';
$action = $_GET['action'] ?? '';
$uid = $_SESSION['user_id'] ?? 0;

if (!$uid && !in_array($action, ['login', 'register'])) exit;

switch ($action) {

    case 'update_profile':
            if (isset($_FILES['avatar'])) {
                $path = "uploads/av_" . $uid . "_" . time() . ".jpg";
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $path)) {
                    $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?")->execute([$path, $uid]);
                    echo json_encode(['status' => 'ok', 'path' => $path]);
                }
            }
            break;

    case 'get_chats':
        echo json_encode($pdo->query("SELECT * FROM chats")->fetchAll(PDO::FETCH_ASSOC));
        break;
    case 'create_chat':
        $stmt = $pdo->prepare("INSERT INTO chats (name, creator_id) VALUES (?,?)");
        $stmt->execute([$_POST['name'], $uid]);
        break;
    case 'fetch':
        $stmt = $pdo->prepare("SELECT m.*, u.avatar FROM messages m LEFT JOIN users u ON m.user_id = u.id WHERE m.chat_id = ? ORDER BY m.id ASC");
        $stmt->execute([$_GET['chat_id']]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
    case 'send':
        $file = null;
        if (!empty($_FILES['attachment'])) {
            $file = "uploads/" . time() . "_" . $_FILES['attachment']['name'];
            move_uploaded_file($_FILES['attachment']['tmp_name'], $file);
        }
        $stmt = $pdo->prepare("INSERT INTO messages (chat_id, user_id, username, message, attachment) VALUES (?,?,?,?,?)");
        $stmt->execute([$_POST['chat_id'], $uid, $_SESSION['username'], $_POST['message'], $file]);
        break;
    case 'get_user':
        $stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = ?");
        $stmt->execute([$uid]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        break;
}