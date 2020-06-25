<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sth = $dbh->prepare("SELECT * FROM collections WHERE id = ?");
    $sth->execute([$id]);
    $collection = $sth->fetch();
    if ($collection and $collection['by'] == $user['id'] and $collection['type'] != 3 and !$collection['is_pfp']) {
        $dbh->prepare("DELETE FROM collections WHERE id = ?")->execute([$id]);
        $dbh->prepare("DELETE FROM collection_ratings WHERE collection = ?")->execute([$id]);
        redirect("/collection/view.php?id=".dechex($id));
    } else
        throw_error("you don't have permission to delete that collection");
} else 
    throw_error("id of collection to delete is not set");