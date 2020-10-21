<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";

if ($is_logged_in and isset($_POST['cast'])) {
    $sth = $dbh->prepare("SELECT * FROM alloy_casts WHERE id = ? AND owner = ?");
    $sth->execute([intval($_POST['cast']), $user['id']]);
    $cast = $sth->fetch();
    if ($cast) {
        if (time() - $cast['time_started'] < $alloy_cast_time) {
            $dbh->prepare("UPDATE alloy_casts SET time_started = ? WHERE id = ?")
                ->execute([0, $cast['id']]);
            $dbh->prepare("UPDATE users SET energy = energy - ? WHERE id = ?")
                ->execute([$alloy_cast_speed_up_energy_price, $user['id']]);
            echo "true";
        } else echo "That cast is already ready.";
    } else echo "Could not find that cast.";
} else echo "post not set";