<?php
require_once __DIR__."/max_size.php";
require_once __DIR__."/real_size.php";
function msgs_left_to_send($sender_id, $recipient_id) {
    global $dbh;
    global $max_messages_per_person_free;
    global $max_messages_per_person_premium;

    if ($sender_id == 0)
        return 1;
    
    //if the sender is blocked by the recipient, no messages can be sent
    $sth = $dbh->prepare("SELECT COUNT(1) FROM user_blocks WHERE blocker = ? AND blocked = ?");
    $sth->execute([$recipient_id, $sender_id]);
    if ($sth->fetchColumn() != 0)
        return 0;
    
    $sth = $dbh->prepare("SELECT COUNT(1) FROM messages WHERE sender = ? AND recipient = ?");
    $sth->execute([$sender_id, $recipient_id]);
    $current_message_count = $sth->fetchColumn();

    $max_messages = get_user_by_id($recipient_id)['is_premium'] ? $max_messages_per_person_premium : $max_messages_per_person_free;
    
    $msgs_to_send = $max_messages - $current_message_count;
    
    $empty_inbox_slots = max_inbox_size($recipient_id) - real_inbox_size($recipient_id);
    if ($msgs_to_send > $empty_inbox_slots)
        $msgs_to_send = $empty_inbox_slots;

    if ($msgs_to_send < 0)
        $msgs_to_send = 0;
    
    return $msgs_to_send;
}