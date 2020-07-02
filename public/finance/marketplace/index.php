<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";
gen_top("Geminer - Marketplace", "Where miners go to buy and sell their hard-earned gems.");

use JasonGrimes\Paginator;

$listings_shown_per_page = 100;
$page_number = 1;

if (isset($_GET['page']))
    $page_number = intval($_GET['page']);

$listings_are_filtered = false;
if (isset($_GET['listing-type'], $_GET['gem'])) {
    $gem_id = intval($_GET['gem']);
    $listing_type = intval($_GET['listing-type']);
    if (!array_key_exists($gem_id, $all_gems))
        throw_error("That gem doesn't exist.");
    else if (!($listing_type == 0 or $listing_type == 1))
        throw_error("That's not a valid listing type.");
    
    $gem = $all_gems[$gem_id];
    $listings_are_filtered = true;
}

$sql = "FROM marketplace_listings".($listings_are_filtered ? " WHERE gem = $gem_id AND type = $listing_type " : "");
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
            <option value="0">buy some</option>
            <option value="1">sell my</option>
        </select>

        <select class="form-control" name="gem" id="gemSelect" required>
            <option selected="selected" disabled="true">a gem...</option>
        </select>
        .

        <button class="btn btn-primary" type="submit">Search for matching listings</button>
    </p>
</form>

<p class="lead">If none of the below offers work for you, <a href="create-listing.php">create your own listing</a>.</p>
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
            <p style="margin: 0"><?php user_button($listing['user'], true, "sm"); ?><?=$listings_are_filtered ? " -" : ("is <b>".($listing['type'] == 0 ? "selling" : "buying")."</b>")?> <a href="/finance/marketplace/listing.php?id=<?=dechex($listing['id'])?>"><?=$listing['amount']?>mP <?=$listings_are_filtered ? "" : "of ".gem_displayer($gem->id).$gem->name." "?>for <?=display_money($listing['price'])?></a> (<?=$listing['type'] == 0 ? round($listing['amount']/$listing['price']/100, 8)."mP/$currency_symbol" : round($listing['price']/$listing['amount']/100, 8).$currency_symbol."/mP"?>)</p>
        </div>
    <?php } ?>
    <hr>

    <?php
    $paginator = new Paginator($listing_count, $listings_shown_per_page, $page_number, "(:num)");
    require $_SERVER['DOCUMENT_ROOT']."/../pages/pagination.php";
    ?>

<?php } else { ?>
<p>There are no listings <?=$listings_are_filtered ? "matching those filters" : "at the moment"?>. <a href="create-listing.php">Create one</a>, maybe?</p>
<?php } ?>

<script>
    $(document).ready(async ()=>{
        await sortedGems;
        for (gem of sortedGems)
            $("#gemSelect").append($(`<option style="color:#${gem.colour}" value=${gem.id}>${gem.name}</option>`));
    });
</script>

<?php gen_bottom(); ?>
