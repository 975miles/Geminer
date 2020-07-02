<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/place_collection_image.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
gen_top("Geminer - Collection leaderboard", "The top-rated gem collections.");

use JasonGrimes\Paginator;

$collections_shown_per_page = 50;
$page_number = 1;

if (isset($_GET['page']))
    $page_number = intval($_GET['page']);

$sth = $dbh->prepare("SELECT id, by, name, score FROM collections_by_score LIMIT $collections_shown_per_page OFFSET ".($page_number-1)*$collections_shown_per_page);
$sth->execute();
$collections = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth = $dbh->prepare("SELECT COUNT(1) FROM collections");
$sth->execute();
$collection_count = $sth->fetchColumn();
//SELECT id, by, name, data, coalesce((SELECT sum(r.is_positive = 1) - sum(r.is_positive = 0) FROM collection_ratings r WHERE r.collection = c.id ), 0) as score FROM collections c ORDER BY score DESC LIMIT 100;
?>
<h1>Top Collections</h1>
<hr>
<?php
$position = ($page_number-1)*$collections_shown_per_page;
foreach ($collections as $collection) {
    $position++;
    ?>
    <?=$position?>: 
    <a href="/collection/view?id=<?=dechex($collection['id'])?>">
        <?php place_collection_image($collection['id']); ?>
        <?=htmlentities($collection['name'])?>
    </a>
    with <?=$collection['score']?> point<?=$collection['score'] == 1 ? "" : "s"?>
    by <?php user_button($collection['by'], true, "sm"); ?>
    <br>
<?php } ?>
<hr>
<?php
$paginator = new Paginator($collection_count, $collections_shown_per_page, $page_number, "(:num)");
require $_SERVER['DOCUMENT_ROOT']."/../pages/pagination.php";
gen_bottom();
?>