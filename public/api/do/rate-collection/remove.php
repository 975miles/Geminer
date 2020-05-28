<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";

if (!$is_logged_in)
    die("\"must be logged in\"");

if (isset($_POST['collection'])) {
    $_POST['collection'] = intval($_POST['collection']);
    if (gettype($_POST['collection']) != "integer")
        die("\"collection must be int\"");

    $dbh->prepare("DELETE FROM collection_ratings WHERE rater = ? AND collection = ?")
        ->execute([$user['id'], $_POST['collection']]);
    
    die("true");
} else
    die("\"not all necessary fields provided\"");