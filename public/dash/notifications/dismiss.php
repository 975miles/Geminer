<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $dbh->prepare("DELETE FROM messages WHERE id = ? AND recipient = ?")->execute([$id, $user['id']]);
    redirect(".");
} else 
    throw_error("id of notification to dismiss is not set");