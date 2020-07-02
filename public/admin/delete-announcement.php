<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
restrict_to("admin");

if (isset($_POST['id'])) {
    $dbh->prepare("DELETE FROM announcements WHERE id = ?")->execute([$_POST['id']]);
    redirect("/announcements");
} else
    throw_error("announcement id is not set");