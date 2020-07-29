<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sth = $dbh->prepare("SELECT * FROM marketplace_listings WHERE id = ? AND user = ?");
    $sth->execute([$id, $user['id']]);
    $listing = $sth->fetch();
    if ($listing) {
        switch ($listing['type']) {
            case 2:
                //fallthrough
            case 0:
                $dbh->prepare("UPDATE users SET "."`".$listing['gem']."`"." = `".$listing['gem']."` + ? WHERE id = ?")
                    ->execute([$listing['amount'], $user['id']]);
                break;

            case 1:
                $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                    ->execute([$listing['price'], $user['id']]);
                break;
    
            case 3:
                $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                    ->execute([floor($listing['price'] * $listing['amount'] / 1000), $user['id']]);
                break;
        }

        $dbh->prepare("DELETE FROM marketplace_listings WHERE id = ?")
            ->execute([$id]);
        redirect("/finance/marketplace");
    } else
        throw_error("You don't have permission to delete this listing.");
} else
    throw_error("id of notification to dismiss is not set");