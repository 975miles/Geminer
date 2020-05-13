<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
gen_top("GEMiner - unknown profile");


if (isset($_GET['user'])) {
    if (user_exists($_GET['user'])) {
        $sth = $dbh->prepare("SELECT * FROM users WHERE name = ?;");
        $sth->execute([$_GET['user']]);
        $user_found = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
        ?>
        <h1><?=$user_found['name']?></h1>
        <p>They've completed <?=$user_found['shifts_completed']?> shifts.</p>

        <?php
        if ($is_logged_in and $user_found['id'] == $user['id']) {
        ?>
        <h3>This is you.</h3>
        <a href="edit.php" class="btn btn-primary">Edit your profile</a>
        <?php
        }
    } else {
        ?>
        <h1>User not found</h1>
        <?php
    }
} else {
    if ($is_logged_in) 
        redirect("?user=".$user['name']);
    else {
        ?>
        <h1>No user specified</h1>
        <?php
    }
}

gen_bottom();