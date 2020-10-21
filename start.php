<?php
session_set_cookie_params(Array("samesite"=>"Lax"));
session_start();
ob_start("ob_gzhandler");
require_once "vendor/autoload.php";
require_once __DIR__."/config/config.php";
require_once __DIR__."/fn.php";

try {
    $dbh = new PDO("sqlite:".__DIR__."/data.db");
} catch (PDOException $e) {
    die("Failed to connect to database");
}

if (!isset($skip_login)) {
    $is_logged_in = isset($_SESSION['user']);
    if ($is_logged_in) {
        $user = get_user_by_id($_SESSION['user']);
        if($user == null)
            redirect("/log/out");
        
        $last_login = $user['last_login'];
        $now = time();
        //Get the closest hour going back from the current time, and the closest hour going forward from the last time the user logged in, and calculate how many "15 minute on-the-dots" have passed since the user was last on the site
        $energy_gained = ((($now - ($now % $energy_regeneration_interval)) - ($last_login + ($energy_regeneration_interval - ($last_login % $energy_regeneration_interval)))) / $energy_regeneration_interval) + 1;
        $new_energy_amount = $user['energy'] + $energy_gained;
        $energy_amount_limit = ($user['is_premium'] ? $energy_storage_limit_premium : $energy_storage_limit_free) + (get_level($user['shifts_completed']) * $energy_storage_per_level);
        if ($new_energy_amount > $energy_amount_limit)
            $new_energy_amount = $energy_amount_limit;
        //set the user's last login time to the current time (not just current_timestamp because timezone)
        //set the user's energy to the newly calculated number on the database and the script
        $dbh->prepare("UPDATE users SET last_login = ?, energy = ? WHERE id = ?")
            ->execute([time(), $new_energy_amount, $user['id']]);
        $user['energy'] = $new_energy_amount;
    }
}