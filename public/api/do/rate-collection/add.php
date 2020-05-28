<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$is_logged_in)
    die("\"must be logged in\"");

if (isset($_POST['collection'], $_POST['is_positive'])) {
    $_POST['collection'] = intval($_POST['collection']);
    if (gettype($_POST['collection']) != "integer" or !($_POST['is_positive'] == 0 or $_POST['is_positive'] == 1))
        die("\"collection must be int, is_positive must be boolean\"");

    $sth = $dbh->prepare("SELECT COUNT(*) FROM collections WHERE id = ?");
    $sth->execute([$_POST['collection']]);
    if ($sth->fetchColumn() == 0)
        die("\"there exists no collection with that id\"");

    $dbh->prepare("INSERT INTO collection_ratings (rater, collection, is_positive) VALUES (?, ?, ?)")
        ->execute([$user['id'], $_POST['collection'], $_POST['is_positive']]);
    
    die("true");
} else
    die("\"not all necessary fields provided\"");