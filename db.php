<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
    $pdo = new PDO('sqlite:vox_data.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Таблицы пользователей, чатов и сообщений
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT UNIQUE, password TEXT, avatar TEXT DEFAULT '')");
    $pdo->exec("CREATE TABLE IF NOT EXISTS chats (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, creator_id INTEGER)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY AUTOINCREMENT, chat_id INTEGER, user_id INTEGER, username TEXT, message TEXT, attachment TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
} catch (Exception $e) { die("Ошибка БД"); }
?>