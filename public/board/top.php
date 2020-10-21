<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Top gem boards", "The gem boards, sorted by how many gems are placed on them.");

use JasonGrimes\Paginator;

$boards_shown_per_page = 10;
$page_number = 1;

if (isset($_GET['page']))
    $page_number = intval($_GET['page']);
?>

<h1>Top gem boards</h1>
<p>These are all the gem boards sorted by most gems placed.</p>
<hr>
<?php
$sth = $dbh->prepare("SELECT board, COUNT(1) AS placements FROM board_placements GROUP BY board ORDER BY placements DESC LIMIT $boards_shown_per_page OFFSET ".($page_number-1)*$boards_shown_per_page);
$sth->execute();
$boards = $sth->fetchAll();
$n = (($page_number-1)*$boards_shown_per_page);
foreach ($boards as $board) {
    $n++;
    ?>
    <p><?=$n?>: <a href="/board?id=<?=$board['board']?>"><?=$board['board']?></a>: with <?=$board['placements']?> gems</p>
<?php } ?>
<hr>
<?php
$sth = $dbh->prepare("SELECT COUNT(1) FROM (SELECT COUNT(1) FROM board_placements GROUP BY board)");
$sth->execute();
$paginator = new Paginator($sth->fetchColumn(), $boards_shown_per_page, $page_number, "(:num)");
require $_SERVER['DOCUMENT_ROOT']."/../pages/pagination.php";
?>

<?php gen_bottom(); ?>