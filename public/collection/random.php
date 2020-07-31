<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
$sth = $dbh->prepare("SELECT id FROM collections ORDER BY RANDOM() LIMIT 1");
$sth->execute();
$collection_found = $sth->fetchAll(PDO::FETCH_ASSOC);
if (count($collection_found) == 0)
    throw_error("There are no collections to choose from!");
else
    redirect("/collection/view?id=".dechex($collection_found[0]['id']));