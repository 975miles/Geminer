<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
if (isset($_GET['board'], $_GET['from'])) {
    $time = time();
    $sth = $dbh->prepare("SELECT x, y, gem FROM board_placements WHERE board = ? AND placed_at >= ?");
    $sth->execute([intval($_GET['board']), intval($_GET['from'])]);

    echo json_encode((object) Array(
        "time" => $time,
        "placements" => $sth->fetchAll()
    ));
}