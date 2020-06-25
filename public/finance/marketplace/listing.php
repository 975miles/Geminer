<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/send_msg.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
gen_top("Geminer - A marketplace listing");

if (isset($_GET['id'])) {
    $id = hexdec($_GET['id']);
    if ($id == 0)
        throw_error("Invalid ID.");
    $sth = $dbh->prepare("SELECT * FROM marketplace_listings WHERE id = ?");
    $sth->execute([$id]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) == 0)
        throw_error("There's no listing with that ID. If you think there definitely used to be, it's probably been bought.");
    else {
        $listing = $results[0];
        if (isset($_POST['buy'])) {
            require_auth();
            $gem = $listing['gem'];
            $gem_name = $all_gems[$listing['gem']]->name;
            $message = implode(",", [dechex($id), $listing['type'], $listing['amount'], $listing['gem'], $listing['price']]);
            if (send_msg($user['id'], $listing['user'], $message, 2)) {
                if ($listing['type'] == 0) {
                    if ($user['money'] < $listing['price'])
                        throw_error("You need ".display_money($listing['price'])." to buy this.");
                    //take the money from the buyer
                    $dbh->prepare("UPDATE users SET money = money - ? WHERE id = ?")
                        ->execute([$listing['price'], $user['id']]);
                    //and give that money to the seller
                    $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                        ->execute([$listing['price'], $listing['user']]);
                    //give the sold gems to the buyer (they've already been taken from the seller)
                    $gem = $listing['gem'];
                    $dbh->prepare("UPDATE users SET `$gem` = `$gem` + ? WHERE id = ?")
                        ->execute([$listing['amount'], $user['id']]);
                } else if ($listing['type'] == 1) {
                    if ($user[$gem] < $listing['amount'])
                        throw_error("You need ".$listing['amount']."mP of $gem_name to do that.");
                    //take the gems from the seller (the person who just clicked sell)
                    $dbh->prepare("UPDATE users SET `$gem` = `$gem` - ? WHERE id = ?")
                        ->execute([$listing['amount'], $user['id']]);
                    //and give that money to the buyer
                    $dbh->prepare("UPDATE users SET `$gem` = `$gem` + ? WHERE id = ?")
                        ->execute([$listing['amount'], $listing['user']]);
                    //give the money to the seller (it's already been taken from the buyer)
                    $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                        ->execute([$listing['price'], $user['id']]);
                }
                $dbh->prepare("DELETE FROM marketplace_listings WHERE id = ?")
                    ->execute([$listing['id']]);
                redirect("/finance/marketplace");
            } else
                show_info("You can't interact with this user. They might have blocked you or you might have interacted with them too much recently.");
        }
    }
} else
    throw_error("You must specify a collection.");
?>
<?=user_background($listing['user'])?>
<p>
    <?php user_button($listing['user']); ?>
    has been looking to <?=$listing['type'] == 0 ? "sell" : "buy"?> <?=$listing['amount']?>mP of <?=gem_displayer($all_gems[$listing['gem']]->id)?><?=$all_gems[$listing['gem']]->name?> for <?=display_money($listing['price'])?> since <span class="unix-ts"><?=$listing['created']?></span>. Thats <?=round($listing['price']/$listing['amount']/100, 8).$currency_symbol?>/mP or <?=round($listing['amount']/$listing['price']/100, 8)."mP/$currency_symbol"?>.
    <?php if ($is_logged_in and $user['id'] == $listing['user']) { ?>
    <form action="delete-listing.php" method="post">
        <button class="btn btn-outline-danger" type="submit" name="id" value="<?=$listing['id']?>">Delete</button>
        <p>Deleting this will refund your <?=$listing['type'] == 0 ? "gems" : "money"?>.</p>
    </form>
    <?php } else if ($is_logged_in) { ?>
    <form action="" method="post">
        <button class="btn btn-primary" type="submit" name="buy" value="true"><?=$listing['type'] == 0 ? "Buy" : "Sell"?></button>
    </form>
    <?php } ?>
</p>

<?php gen_bottom(); ?>