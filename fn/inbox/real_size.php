<?php
function real_inbox_size($user_id) {
    global $dbh;
    $sth = $dbh->prepare("SELECT COUNT(1) FROM messages WHERE recipient = ?");
    $sth->execute([$user_id]);
    $message_amount = $sth->fetchColumn();
    $sth = $dbh->prepare("SELECT COUNT(1) FROM marketplace_listings WHERE user = ?");
    $sth->execute([$user_id]);
    $marketplace_listing_amount = $sth->fetchColumn();
    return $message_amount + $marketplace_listing_amount;
}