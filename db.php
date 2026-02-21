<?php
function get_db(): SQLite3 {
    $db = new SQLite3(__DIR__ . '/wall.db');
    $db->exec('CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        author TEXT NOT NULL,
        text TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    return $db;
}
