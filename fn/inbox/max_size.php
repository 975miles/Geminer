<?php
function max_inbox_size($user_id) {
    global $max_inbox_size_free;
    global $max_inbox_size_premium;
    global $max_inbox_size_admin;
    $user_found = get_user_by_id($user_id);
    return $user_found['is_admin'] ? $max_inbox_size_admin : ($user_found['is_premium'] ? $max_inbox_size_premium : $max_inbox_size_free);
}