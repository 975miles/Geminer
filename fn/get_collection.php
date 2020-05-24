<?php
if (isset($_GET['id'])) {
    $id = hexdec($_GET['id']);
    if ($id == 0)
        throw_error("Invalid ID.");
    $sth = $dbh->prepare("SELECT * FROM collections WHERE id = ?");
    $sth->execute([$id]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) == 0)
        throw_error("There's no collection with that ID. If you think there definitely used to be, it's probably been deleted.");
} else
    throw_error("You must specify a collection.");

$collection = $results[0];