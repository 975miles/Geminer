<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/crate_rarities.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/crate_rewards.php";
require_auth();

if (isset($_POST['id'])) {
    $crate_id = intval($_POST['id']);
    $sth = $dbh->prepare("SELECT id, rarity FROM crates WHERE id = ? AND owner = ?");
    $sth->execute([$crate_id, $user['id']]);
    $crate = $sth->fetchAll();
    if (count($crate) > 0) {
        $dbh->prepare("DELETE FROM crates WHERE id = ?")
            ->execute([$crate[0]['id']]);

        $rewards = $crate_rewards[$crate[0]['rarity']];
        $reward = $rewards[array_rand($rewards)];
        $reward->rarity = $crate[0]['rarity'];

        switch ($reward->type) {
            case "modifier":
                $dbh->prepare("INSERT INTO modifiers (type, owner) VALUES (?, ?)")
                    ->execute([$reward->data, $user['id']]);
                break;

                
            case "money":
                $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                    ->execute([$reward->data, $user['id']]);
                break;

            case "gems":
                $dbh->prepare("UPDATE users SET `".$reward->data->gem."` = `".$reward->data->gem."` + ? WHERE id = ?")
                    ->execute([round($reward->data->amount*1000), $user['id']]);
                break;
            
            case "pickaxe":
                $dbh->prepare("INSERT INTO pickaxes (handle, head, binding, modifiers, owner, date_created, name) VALUES (?, ?, ?, ?, ?, ?, ?)")
                    ->execute([$reward->data->handle, $reward->data->head, $reward->data->binding, "", $user['id'], time(), $reward->data->name]);
                break;

            case "crate":
                $dbh->prepare("INSERT INTO crates (rarity, owner) VALUES (?, ?)")
                    ->execute([$reward->data, $user['id']]);
                $reward->data = Array(
                    "id" => $dbh->lastInsertId(),
                    "rarity" => $reward->data
                );
                break;
        }


        die(json_encode($reward));
    } else
        die("false");
}

gen_top("Crates");

$sth = $dbh->prepare("SELECT id, rarity FROM crates WHERE owner = ?");
$sth->execute([$user['id']]);
$crates_found = $sth->fetchAll();
?>

<link rel="stylesheet" href="/a/css/inventory.css">
<link rel="stylesheet" href="/a/css/swirly.css">

<h1>Crates</h1>
<p class="lead">Each crate contains one of the following: money, gems, modifier, pickaxe, or a crate of a different rarity. You have a chance to get a crate when you mine (higher chance with a higher luck value on your pickaxe). You can also buy crates with <?=$currency_symbol?> at the bottom of this page.</p>

<hr>
<h3>Your crates</h3>
<div id="inventory"></div>

<hr>
<h3>Crate shop</h3>
<div id="crateShop"></div>

<script>
var myCrates = <?=json_encode($crates_found)?>;
</script>
<script src="/a/js/pickconsts.js"></script>
<script src="/a/js/pickaxe.js"></script>
<script src="/a/js/dash/crates.js"></script>
<script src="/a/js/dash/crate-shop.js"></script>

<?php gen_bottom(); ?>