<?php
require_once 'db.php';

const CURRENT_USER = 'Иван Петров';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Forbidden');
    }

    $text = trim($_POST['text'] ?? '');
    if ($text !== '') {
        $db = get_db();
        $stmt = $db->prepare('INSERT INTO posts (author, text) VALUES (:author, :text)');
        $stmt->bindValue(':author', CURRENT_USER, SQLITE3_TEXT);
        $stmt->bindValue(':text', $text, SQLITE3_TEXT);
        $stmt->execute();
        $db->close();
    }
}

header('Location: index.php');
exit;
