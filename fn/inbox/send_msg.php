<?php
require_once __DIR__."/msgs_left_to_send.php";
function send_msg($sender_id, $recipient_id, $message, $type) {
    global $dbh;
    if (msgs_left_to_send($sender_id, $recipient_id) == 0)
        return false;
    $dbh->prepare("INSERT INTO messages (type, sender, recipient, message) VALUES (?, ?, ?, ?)")
        ->execute([$type, $sender_id, $recipient_id, $message]);
    return true;
}