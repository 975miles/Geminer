<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/backgrounds.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/collection-types.php";
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

<h2>Premium features</h2>
<p><i>Premium just includes QoL and cosmetic features, none of them are "pay-to-win"... yet.</i></p>

<ul>
    <?php
    function vein_average($n) {
        $output = 0;
        for ($i = 1; $i <= $n; $i++)
            $output+= $i;
        return $output/$n;
    }
    ?>
    <li>Approximately <?=vein_average($max_veins_per_mine_premium)/vein_average($max_veins_per_mine_free)?>x the amount of gems per mine</li>
    <li>TVWIEMWYTGI can somehow sense the greatness of premium members, and erupts around <?=$free_sell_divisor?>x the amount of money for them</li>
    <li>One massive <?=$collection_types[3]->width?>*<?=$collection_types[3]->height?> collection</li>
    <li>Profile badge showing when you supported Geminer</li>
    <li>No more link nagging you to get premium in your top-right profile dropdown.</li>
    <?php foreach ($extra_premium_features as $extra_premium_feature) { ?>
    <li><?=$extra_premium_feature?></li>
    <?php } ?>
</ul>

<style>
td, th {
  border: 1px solid black;
}
</style>

<table>
    <thead>
        <tr>
            <th></th>
            <th>Free</th>
            <th>Premium</th>
        </tr>
    </thead>


    <tbody>
        <tr>
            <td>Maximum <img src="/a/i/energy.png" class="energy-icon" alt="energy"></td>
            <td><?=$energy_storage_limit_free?></td>
            <td><?=$energy_storage_limit_premium?></td>
        </tr>

        <tr>
            <td>Maximum collections</td>
            <td><?=$collection_storage_limit_free?></td>
            <td><?=$collection_storage_limit_premium?></td>
        </tr>

        <tr>
            <td>Maximum concurrent marketplace listings</td>
            <td><?=$max_marketplace_listings_free?></td>
            <td><?=$max_marketplace_listings_premium?></td>
        </tr>

        <tr>
            <td>Maximum inbox size (messages stored)</td>
            <td><?=$max_inbox_size_free?></td>
            <td><?=$max_inbox_size_premium?></td>
        </tr>

        <tr>
            <td>Maximum length of sent messages</td>
            <td><?=$max_sent_message_length_free?></td>
            <td><?=$max_sent_message_length_premium?></td>
        </tr>

        <tr>
            <td>Allowed username length</td>
            <td><?=$min_username_length_free?>-<?=$max_username_length_free?></td>
            <td><?=$min_username_length_premium?>-<?=$max_username_length_premium?></td>
        </tr>

        <tr>
            <td>Available tag backgrounds</td>
            <td><?=count(array_filter($tag_styles, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($tag_styles)?></td>
        </tr>

        <tr>
            <td>Available tag fonts</td>
            <td><?=count(array_filter($tag_fonts, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($tag_fonts)?></td>
        </tr>

        <tr>
            <td>Available profile backgrounds</td>
            <td><?=count(array_filter($profile_backgrounds, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($profile_backgrounds)?></td>
        </tr>

        <tr>
            <td>Total possible customisation combinations</td>
            <td><?=count(array_filter($tag_styles, function ($i) {return !$i->premium;}))*count(array_filter($tag_fonts, function ($i) {return !$i->premium;}))*count(array_filter($profile_backgrounds, function ($i) {return !$i->premium;}))?></td>
            <td><?=count($tag_styles)*count($tag_fonts)*count($profile_backgrounds)?></td>
        </tr>

        <tr>
            <td>Coolness level</td>
            <td>Kinda</td>
            <td>Very</td>
        </tr>
    </tbody>
</table>

<?php gen_bottom(); ?>