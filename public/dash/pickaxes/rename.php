<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
$available_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890 -_.()+=";
if (isset($_POST['id'], $_POST['name'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    foreach (str_split($name) as $name_character)
        if(strpos($available_chars, $name_character) === false)
            die("You're only allowed to use the following characters in a pickaxe's name: <code>".$available_chars."</code>");
    if (mb_strlen($name) > $max_pick_name_length)
        die("A pickaxe's name may only be ".$max_pick_name_length." characters or fewer.");
    $sth = $dbh->prepare("SELECT COUNT(1) FROM pickaxes WHERE id = ? AND owner = ?");
    $sth->execute([$id, $user['id']]);
    if ($sth->fetchColumn() > 0) {
        $dbh->prepare("UPDATE pickaxes SET name = ? WHERE id = ?")
            ->execute([$name, $id]);
        die("true");
    } else
        die("false");
} else
    die("false");