<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();

if (isset($_POST['user'])) {
    if (user_exists($_POST['user'])) {
        $sth = $dbh->prepare("SELECT id FROM users WHERE name = ? COLLATE NOCASE");
        $sth->execute([$_POST['user']]);
        $user_found_id = $sth->fetchColumn();

        $sth = $dbh->prepare("SELECT COUNT(1) FROM user_blocks WHERE blocker = ? AND blocked = ?");
        $sth->execute([$user['id'], $user_found_id]);
        if ($sth->fetchColumn() > 0) {
            $dbh->prepare("DELETE FROM user_blocks WHERE blocker = ? AND blocked = ?")
                ->execute([$user['id'], $user_found_id]);
            redirect("/profile?user=".$_POST['user']); //success
        } else
            echo "You haven't blocked that user";
    } else 
        echo "That user doesn't exist";
}