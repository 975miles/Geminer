<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/collection-types.php";
require_auth();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sth = $dbh->prepare("SELECT * FROM collections WHERE id = ?");
    $sth->execute([$id]);
    $collection = $sth->fetch();
    if ($collection and $collection['by'] == $user['id'] and $collection_types[$collection['type']]->pfp and !$collection['is_pfp']) {
        $dbh->prepare("UPDATE collections SET is_pfp = 0 WHERE by = ?")->execute([$user['id']]); //make existing pfp a normal collection
        $dbh->prepare("UPDATE collections SET is_pfp = 1 WHERE id = ?")->execute([$id]);
        redirect("/collection/view?id=".dechex($id));
    } else
        throw_error("you don't have permission to make that your pfp");
} else 
    throw_error("id of collection to make pfp is not set");