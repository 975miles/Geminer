<?php
$code_length = 64;
$available_chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
$available_char_amount = mb_strlen($available_chars);

function generate_redemption_code() {
    global $code_length;
    global $available_chars;
    global $available_char_amount;
    
    $code = "";
    for ($k = 0; $k < $code_length; $k++) {
        $code .= $available_chars[mt_rand(0, $available_char_amount - 1)];
    }
    return $code;
}