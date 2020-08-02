<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/backgrounds.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
gen_top("Geminer Premium", "Details about Geminer premium");
?>

<h1>Geminer Premium</h1>
<p>
    Do you crave virtual colours, but you're not getting enough of them?<br>
    Are you somewhat insane?<br>
    Do gradually increasing numbers turn you on?<br>
    Do said numbers need to have little to no effect on anything for them to turn you on <i>effectively</i>?<br>
    You're a Gemini, right?<br>
    <br>
    Well, have I got the solution for you!<br>
    Get Geminer premium to quench all your virtual number-craving needs!<br>
    <br>
    <h3>
    <?php if ($user['is_premium']) { ?>
        You already have Geminer premium.
    </h3>
    <?php } else { ?>
        To get Geminer premium, <a class="btn btn-sm btn-primary" href="<?=$premium_purchase_url?>">buy</a> a code then <a class="btn btn-sm btn-primary" href="redeem">redeem</a> it.
    </h3>
    Oh? You want to know what it is you're buying before you buy what you're buying?<br>
    Fine, here.
    <?php } ?>
    <br>
    <br>
</p>

<hr>
<h2>Premium features</h2>

<ul>
    <?php
    function vein_average($n) {
        $output = 0;
        for ($i = 1; $i <= $n; $i++)
            $output+= $i;
        return $output/$n;
    }
    ?>
    <li>Approximately <?=vein_average($max_veins_per_mine_premium)/vein_average($max_veins_per_mine_free)?>x the amount of gems per mining shift</li>
    <li>Profile badge showing when you supported Geminer</li>
    <li>No more link nagging you to get premium in your top-right profile dropdown.</li>
    <?php foreach ($extra_premium_features as $extra_premium_feature) { ?>
    <li><?=$extra_premium_feature?></li>
    <?php } ?>
</ul>
<hr>

<table class="table table-dark">
    <thead class="thead-light">
        <tr>
            <th scope="col">Feature</th>
            <th scope="col">Free</th>
            <th scope="col">Premium</th>
        </tr>
    </thead>


    <tbody>
        <tr>
            <th scope="row">Maximum <img src="/a/i/energy.png" class="energy-icon" alt="energy"></th>
            <td><?=$energy_storage_limit_free?></td>
            <td><?=$energy_storage_limit_premium?></td>
        </tr>

        <tr>
            <th scope="row">Maximum collections</th>
            <td><?=$collection_storage_limit_free?></td>
            <td><?=$collection_storage_limit_premium?></td>
        </tr>

        <tr>
            <th scope="row">Maximum concurrent marketplace listings</th>
            <td><?=$max_marketplace_listings_free?></td>
            <td><?=$max_marketplace_listings_premium?></td>
        </tr>

        <tr>
            <th scope="row">Maximum inbox size (messages stored)</th>
            <td><?=$max_inbox_size_free?></td>
            <td><?=$max_inbox_size_premium?></td>
        </tr>

        <tr>
            <th scope="row">Maximum length of sent messages</th>
            <td><?=$max_sent_message_length_free?></td>
            <td><?=$max_sent_message_length_premium?></td>
        </tr>

        <tr>
            <th scope="row">Allowed username length</th>
            <td><?=$min_username_length_free?>-<?=$max_username_length_free?></td>
            <td><?=$min_username_length_premium?>-<?=$max_username_length_premium?></td>
        </tr>

        <?php /* 
        <tr>
            <th scope="row">Available tag backgrounds</th>
            <td><?=count(array_filter($tag_styles, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($tag_styles)?></td>
        </tr>

        <tr>
            <th scope="row">Available tag fonts</th>
            <td><?=count(array_filter($tag_fonts, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($tag_fonts)?></td>
        </tr>

        <tr>
            <th scope="row">Available profile backgrounds</th>
            <td><?=count(array_filter($profile_backgrounds, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($profile_backgrounds)?></td>
        </tr>

        <tr>
            <th scope="row">Available navbar backgrounds</th>
            <td><?=count(array_filter($navbar_backgrounds, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($navbar_backgrounds)?></td>
        </tr>

        <tr>
            <th scope="row">Total possible customisation combinations</th>
            <td><?=count(array_filter($tag_styles, function ($i) {return !$i->premium;}))*count(array_filter($tag_fonts, function ($i) {return !$i->premium;}))*count(array_filter($profile_backgrounds, function ($i) {return !$i->premium;}))*count(array_filter($navbar_backgrounds, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($tag_styles)*count($tag_fonts)*count($profile_backgrounds)*count($navbar_backgrounds)?></td>
        </tr>
        */?>

        <tr>
            <th scope="row">Coolness level</th>
            <td>Kinda</td>
            <td>Very</td>
        </tr>
    </tbody>
</table>

<hr>
<h3>Collection sizes</h3>
<p>If the symbol in brackets is a tick (✓), collections of that size can be used as a profile picture.</p>
<h4>Available to everyone:</h4>
<ul id="nonPremiumSizes"></ul>
<h4>Available to premium members only:</h4>
<ul id="premiumSizes"></ul>

<script>
$.getJSON("/a/data/collection-types.json", collectionTypes=>{
    for (let type of collectionTypes)
        $("#"+(type.premium ? "premiumSizes" : "nonPremiumSizes")).append($(`<li>${type.name} - ${type.width}px*${type.height}px (${type.pfp ? "✓" : "✗"})${type.premium ? "" : ` - unlocks at level ${type.level}`}</li>`));
});
</script>

<hr>
<h3>Premium customisation options</h3>
<hr>
<?php if ($is_logged_in) { ?>
<h4>Tag backgrounds</h4>
<?php
foreach ($tag_styles as $tag_style_id => $tag_style) {
    if ($tag_style->premium) {
        user_button($user['id'], true, null, "div", $tag_style_id);
    }
}
?>
<hr>
<h4>Tag fonts</h4>
<?php
foreach ($tag_fonts as $tag_font_id => $tag_font) {
    if ($tag_font->premium) {
        user_button($user['id'], true, null, "div", false, $tag_font_id);
    }
}
?>
<?php } else { ?>
Login to see tag backgrounds and fonts.
<?php } ?>
<hr>
<h4>Profile backgrounds</h4>
<?php
foreach ($profile_backgrounds as $profile_background) {
    if ($profile_background->premium) { ?>
    <span class="bg-displayer" style="background: <?=$profile_background->bgshort?>;"></span>
<?php } } ?>
<hr>
<h4>Navbar backgrounds</h4>
<?php
foreach ($navbar_backgrounds as $navbar_background) {
    if ($navbar_background->premium) { ?>
    <span class="bg-displayer" style="height: 40px; background: <?=$navbar_background->style?>;"></span>
<?php } } ?>

<?php gen_bottom(); ?>