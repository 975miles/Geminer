<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
restrict_to("admin");

if (isset($_POST['announcement'])) {
    $dbh->prepare("INSERT INTO announcements (text, by, date) VALUES (?, ?, ?)")->execute([$_POST['announcement'], $user['id'], time()]);
    redirect("/notifications.php");
} else {
    gen_top();?>
    <h1>Make an announcement</h1>
    <form action="" method="post" autocomplete="off">
        <textarea type="text" name="announcement" class="form-control" placeholder="Announcement text"></textarea>
        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
    </form>
    <?php }
gen_bottom(); ?>