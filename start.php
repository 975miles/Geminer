<?php
session_start();
require_once __DIR__."/config/config.php";
require_once __DIR__."/fn.php";

try {
    $dbh = new PDO($pdo_dsn);
} catch (PDOException $e) {
    die("Failed to connect to database");
}

$is_logged_in = isset($_SESSION['user']);
if ($is_logged_in) {
    $user = get_user_by_id($_SESSION['user']);
    
    $last_login = strtotime($user['last_login']);
    $now = time();
    $interval = 900; //15 minutes
    //Get the closest quarter hour going back from the current time, and the closest quarter hour going forward from the last time the user logged in, and calculate how many "15 minute on-the-dots" have passed since the user last mined
    $mines_gained = ((($now - ($now % $interval)) - ($last_login + ($interval - ($last_login % $interval)))) / $interval) + 1;
    $new_mine_amount = $user['mines_left'] + $mines_gained;
    $mine_amount_limit = ($user['is_premium'] ? 3000 : 100);
    if ($new_mine_amount > $mine_amount_limit)
        $new_mine_amount = $mine_amount_limit;
    //set the user's last login time to the current time (not just current_timestamp because timezone)
    //set the user's allowed amount of mines to the newly calculated number on the database and the script
    $dbh->prepare("UPDATE users SET last_login = datetime(strftime('%s','now'), 'unixepoch', 'localtime'), mines_left = ? WHERE id = ?")
        ->execute([$new_mine_amount, $user['id']]);
    $user['mines_left'] = $new_mine_amount;
}