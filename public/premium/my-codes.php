<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/crate_rarities.php";
restrict_to("premium");
gen_top();

$sth = $dbh->prepare("SELECT * FROM owned_codes WHERE owner = ?");
$sth->execute([$user['id']]);
$my_codes = $sth->fetchAll();
?>

<h1>Your codes</h1>
<p>You got these codes upon upgrading to premium. Anyone, including you, can use these. Redeem them <a href="/redeem">here</a>.</p>

<?php foreach($my_codes as $code) {
    $sth = $dbh->prepare("SELECT data FROM codes WHERE id = ?");
    $sth->execute([$code['code_id']]);
    $code_data = json_decode($sth->fetchColumn());

    ?><p>Code for <?php

    switch ($code_data->product) {
        case "money":
            echo display_money($code_data->amount);
            break;

        case "energy":
            ?><?=$code_data->amount?><img src="/a/i/energy.png" class="energy-icon" alt="energy"><?php
            break;

        case "crate":
            $crate = $crate_rarities[$code_data->crate];
            ?><?=$code_data->amount?> <?=strtolower($crate->name)?> crates (<img src="/a/i/crate.png" style="filter: <?=$crate->filter?>" height="20">)<?php
            break;
    }

    ?>: <code><?=$code['code']?></code></p><?php
} ?>

<?php gen_bottom(); ?>