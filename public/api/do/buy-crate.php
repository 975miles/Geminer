<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/crate_rarities.php";
require_auth();

if (isset($_POST['rarity'])) {
    $rarity_id = intval($_POST['rarity']);
    if (array_key_exists($rarity_id, $crate_rarities)) {
        $crate_rarity = $crate_rarities[$rarity_id];
        if ($user['money'] < $crate_rarity->price)
            die("\"You don't have enough money to buy that!\"");

        $dbh->prepare("INSERT INTO crates (rarity, owner) VALUES (?, ?)")
            ->execute([$rarity_id, $user['id']]);
        
        die($dbh->lastInsertId());
    } else die("\"That crate rarity doesn't exist.\"");
}