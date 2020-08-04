<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/real_gem_amounts.php";


if (!$is_logged_in)
    die("false");

if (isset($_POST['board'], $_POST['x'], $_POST['y'], $_POST['gem'])) {
    $board_id = intval($_POST['board']);
    srand($board_id);
    $board_size = rand(1, 256);
    $place_price = rand($board_place_min_price, $board_place_max_price);

    if ($user['money'] < $place_price)
        die("false");

    $gem = intval($_POST['gem']);
    if (!array_key_exists($gem, $all_gems))
        die("false");


    $x = $_POST['x'];
    $y = $_POST['y'];
    if ($x < 0 or $x > $board_size-1)
        die("false");
    if ($y < 0 or $y > $board_size-1)
        die("false");

    if (get_real_gem_amounts()[$gem] < 1000)
        die("false");

    $sth = $dbh->prepare("SELECT gem, user FROM board_placements WHERE board = ? AND x = ? AND y = ?");
    $sth->execute([$board_id, $x, $y]);
    $placement = $sth->fetchAll();
    if (count($placement) == 0)
        $dbh->prepare("INSERT INTO board_placements (board, x, y, gem, user) VALUES (?, ?, ?, ?, ?)")
            ->execute([$board_id, $x, $y, $gem, $user['id']]);
    else {
        if ($placement[0]['user'] == $user['id'] and $placement[0]['gem'] == $gem)
            die("false");
        else
            $dbh->prepare("UPDATE board_placements SET gem = ?, user = ? WHERE board = ? AND x = ? AND y = ?")
                ->execute([$gem, $user['id'], $board_id, $x, $y]);
    }

    $dbh->prepare("UPDATE users SET money = money - ?, `$gem` = `$gem` - 1000 WHERE id = ?")
        ->execute([$place_price, $user['id']]);

    die("true");
} else
    die("false");