<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
if (isset($_GET['board'], $_GET['x'], $_GET['y'])) {
    $sth = $dbh->prepare("SELECT user FROM board_placements WHERE board = ? AND x = ? AND y = ?");
    $sth->execute([intval($_GET['board']), intval($_GET['x']), intval($_GET['y'])]);
    $user = $sth->fetchColumn();
    echo $user != null ? "\"".get_user_by_id($user)['name']."\"" : "null";
} else
    echo "null";