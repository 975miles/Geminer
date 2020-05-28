<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("A gem collection");
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/get_collection.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";

$sth = $dbh->prepare("SELECT COUNT(*) FROM collection_ratings WHERE collection = ? AND is_positive = 1");
$sth->execute([$collection['id']]);
$positive_rating_amount = $sth->fetchColumn();
$sth = $dbh->prepare("SELECT COUNT(*) FROM collection_ratings WHERE collection = ? AND is_positive = 0");
$sth->execute([$collection['id']]);
$negative_rating_amount = $sth->fetchColumn();

$user_rating = null;
if ($is_logged_in) {
    $sth = $dbh->prepare("SELECT is_positive FROM collection_ratings WHERE collection = ? AND rater = ?");
    $sth->execute([$collection['id'], $user['id']]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) > 0)
        $user_rating = ($results[0]['is_positive'] ? true : false);
}
?>

<h1><?=htmlentities($collection['name'])?></h1>
<p>by <?php user_button($collection['by']); ?></p>
<?php generate_collection_image($collection['id'], true); ?>
<hr>
<a id="rate-1" class="btn btn-light"<?php if ($is_logged_in) { ?> onclick="rate(1)"<?php } else { ?> href="/log/in.php"<?php } ?> value="<?=$user_rating === true ? "true" : "false"?>">
    <img src="/a/i/ratings/<?=$user_rating === true ? "" : "in"?>active/positive.png" height=30>
    <?=$positive_rating_amount?>
</a>

<a id="rate-0" class="btn btn-light"<?php if ($is_logged_in) { ?> onclick="rate(0)"<?php } else { ?> href="/log/in.php"<?php } ?> value="<?=$user_rating === false ? "true" : "false"?>">
    <img src="/a/i/ratings/<?=$user_rating === false ? "" : "in"?>active/negative.png" height=30>
    <?=$negative_rating_amount?>
</a>

<script>
    function rate(isPositive) {
        let collectionId = parseInt(new URLSearchParams(window.location.search).get("id"), 16);

        if ($(`#rate-${isPositive}`).attr("value") == "false")
            $.post("/api/do/rate-collection/add.php", {
                collection: collectionId,
                is_positive: isPositive
            }, () => location.reload());
        else
            $.post("/api/do/rate-collection/remove.php", {
                collection: collectionId
            }, () => location.reload());
    }
</script>

<?php if ($is_logged_in and $collection['by'] == $user['id']) { ?>
<hr>
<h2>This collection is yours.</h2>
<a href="/collection/edit.php?id=<?=$_GET['id']?>" class="btn btn-primary">Edit</a>
<?php } ?>
<?php gen_bottom(); ?>