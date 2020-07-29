<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";
gen_top("Marketplace", "Where miners go to buy and sell their hard-earned gems.");

use JasonGrimes\Paginator;

$listings_shown_per_page = 100;
$page_number = 1;

if (isset($_GET['page']))
    $page_number = intval($_GET['page']);

$listings_are_filtered = false;
if (isset($_GET['listing-type'], $_GET['gem'])) {
    $gem_id = intval($_GET['gem']);
    $listing_type = $_GET['listing-type'];
    $all_types_to_find = Array(
        "buy" => [0, 2],
        "sell" => [1, 3]
    );
    if (!array_key_exists($gem_id, $all_gems))
        throw_error("That gem doesn't exist.");
    else if (!array_key_exists($listing_type, $all_types_to_find))
        throw_error("That's not a valid listing type.");
    $types_to_find = $all_types_to_find[$listing_type];
    $gem = $all_gems[$gem_id];
    $listings_are_filtered = true;
}

$sql = "FROM marketplace_listings".($listings_are_filtered ? " WHERE gem = $gem_id AND (type = ".$types_to_find[0]." OR type = ".$types_to_find[1].") " : "");
$sth = $dbh->prepare("SELECT * $sql ORDER BY created DESC LIMIT $listings_shown_per_page OFFSET ".($page_number-1)*$listings_shown_per_page);
$sth->execute();
$listings = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth = $dbh->prepare("SELECT COUNT(1) $sql");
$sth->execute();
$listing_count = $sth->fetchColumn();
?>

<h1>Marketplace</h1>

<form class="form-inline" action="" method="get">
    <p>
        I want to
        <select class="form-control" name="listing-type" required>
            <option selected="selected" disabled="true">select...</option>
            <option value="buy">buy some</option>
            <option value="sell">sell my</option>
        </select>

        <select class="form-control" name="gem" id="gemSelect" required>
            <option selected="selected" disabled="true">a gem...</option>
        </select>
        .

        <button class="btn btn-primary" type="submit">Search for matching listings</button>
    </p>
</form>

<p class="lead">If none of the below offers work for you, <a href="create-listing">create your own listing</a>.</p>
<p>
    <?php if ($listings_are_filtered) { ?>
    Showing listings <?=$listing_type == 0 ? "selling" : "buying"?> <?=gem_displayer($gem->id)?><?=$gem->name?>
    <?php } else { ?>
    Showing any listings
    <?php } ?>
    (sorted by date created, newest first):
</p>

<?php if ($listings_are_filtered) { ?>
<a class="btn btn-primary" href="?">Clear filters</a>
<?php } ?>
<hr>
<?php
if ($listing_count > 0) {
    foreach($listings as $listing) {
        $gem = $all_gems[$listing['gem']];
        $bg = $profile_backgrounds[get_user_by_id($listing['user'])['profile_background']];
        ?>
        <div class=" rounded border border-dark" style="background: <?=$bg->bgshort?>; color: <?=$bg->text_colour?>;">
            <p style="margin: 0"><?php user_button($listing['user'], true, "sm"); ?> <b><?=$listing['type'] == 0 ? "is selling" : ($listing['type'] == 1 ? "is buying" : ($listing['type'] == 2 ? "has a shop selling" : "is collecting"))?></b> <a href="/finance/marketplace/listing?id=<?=dechex($listing['id'])?>"><?=($listing['type'] == 0 or $listing['type'] == 1) ? $listing['amount']."mpx".($listings_are_filtered ? "" : " of") : ""?> <?=$listings_are_filtered ? "" : "".gem_displayer($gem->id).$gem->name." "?>for <?=($listing['type'] == 0 or $listing['type'] == 1) ? display_money($listing['price']) : display_money($listing['price']/1000, 3)."/mpx"?></a><?=$listing['type'] == 0 ? " (".round($listing['amount']/$listing['price']/100, 8)."mpx/".$currency_symbol.")" : ($listing['type'] == 1 ? " (".display_money($listing['price']/$listing['amount'], 6)."/mpx)" : "")?>.</p>
        </div>
    <?php } ?>
    <hr>

    <?php
    $paginator = new Paginator($listing_count, $listings_shown_per_page, $page_number, "(:num)");
    require $_SERVER['DOCUMENT_ROOT']."/../pages/pagination.php";
    ?>

<?php } else { ?>
<p>There are no listings <?=$listings_are_filtered ? "matching those filters" : "at the moment"?>. <a href="create-listing">Create one</a>, maybe?</p>
<?php } ?>

<script>
    $(document).ready(async ()=>{
        await sortedGems;
        for (gem of sortedGems)
            $("#gemSelect").append($(`<option style="color:#${gem.colour}" value=${gem.id}>${gem.name}</option>`));
    });
</script>

<?php gen_bottom(); ?>
