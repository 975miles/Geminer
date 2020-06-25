<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sth = $dbh->prepare("SELECT * FROM marketplace_listings WHERE id = ? AND user = ?");
    $sth->execute([$id, $user['id']]);
    $listing = $sth->fetch();
    if ($listing) {
        if ($listing['type'] == 1)
            $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                ->execute([$listing['price'], $user['id']]);
        else if ($listing['type'] == 0)
            $dbh->prepare("UPDATE users SET "."`".$listing['gem']."`"." = `".$listing['gem']."` + ? WHERE id = ?")
                ->execute([$listing['amount'], $user['id']]);
        $dbh->prepare("DELETE FROM marketplace_listings WHERE id = ?")
            ->execute([$id]);
        redirect("/finance/marketplace");
    } else
        throw_error("You don't have permission to delete this listing.");
} else
    throw_error("id of notification to dismiss is not set");