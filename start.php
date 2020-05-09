<?php
session_start();
require_once __DIR__."/config/config.php";
require_once __DIR__."/fn.php";

try {
    $dbh = new PDO($pdo_dsn);
} catch (PDOException $e) {
    die("Failed to connect to database");
}

$is_logged_in = isset($_SESSION['user']);
if ($is_logged_in)
    $user = get_user_by_id($_SESSION['user']);