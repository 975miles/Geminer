<?php
require_once __DIR__."/real_size.php";
require_once __DIR__."/max_size.php";
function inbox_is_full($user_id) {
    return real_inbox_size($user_id) >= max_inbox_size($user_id);
}