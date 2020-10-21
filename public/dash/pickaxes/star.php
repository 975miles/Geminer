<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sth = $dbh->prepare("SELECT starred FROM pickaxes WHERE id = ? AND owner = ?");
    $sth->execute([$id, $user['id']]);
    $starred = $sth->fetchAll();
    if (count($starred) > 0) {
        $dbh->prepare("UPDATE pickaxes SET starred = ? WHERE id = ?")
            ->execute([$starred[0]['starred'] == 1 ? 0 : 1, $id]);
        die("true");
    } else
        die("false");
} else
    die("false");