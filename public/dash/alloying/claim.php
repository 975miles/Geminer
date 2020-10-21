<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
if ($is_logged_in and isset($_POST['cast'])) {
    $sth = $dbh->prepare("SELECT * FROM alloy_casts WHERE id = ? AND owner = ?");
    $sth->execute([intval($_POST['cast']), $user['id']]);
    $cast = $sth->fetch();
    if ($cast) {
        if (time() - $cast['time_started'] >= $alloy_cast_time) {
            $dbh->prepare("DELETE FROM alloy_casts WHERE id = ?")
                ->execute([$cast['id']]);
            $dbh->prepare("UPDATE users SET `".$cast['gem']."` = `".$cast['gem']."` + ? WHERE id = ?")
                ->execute([$cast['amount'], $user['id']]);
            echo "true";
        } else echo "false";
    } else echo "false";
} else echo "false";