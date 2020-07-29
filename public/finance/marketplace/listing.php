<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/real_gem_amounts.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/msgs_left_to_send.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/send_msg.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";

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
        $gem = $listing['gem'];
        $gem_name = $all_gems[$gem]->name;
        
        if ($is_logged_in) {
            $user_gem_amount = get_real_gem_amounts()[$listing['gem']];
        }

        if (isset($_POST['buy'])) {
            require_auth();
            if ($listing['type'] == 2 or $listing['type'] == 3) {
                if (isset($_POST['amount'])) {
                    $amount = intval($_POST['amount']);
                    if ($amount > $listing['amount'])
                        show_info("There's not enough stock");
                    else {
                        $price = floor($listing['price'] * $amount / 1000);
                        if ($listing['type'] == 2) {
                            if ($user['money'] < $price)
                                throw_error("You need ".display_money($listing['price'])." to buy this.");
                            //take the money from the buyer
                            $dbh->prepare("UPDATE users SET money = money - ? WHERE id = ?")
                                ->execute([$price, $user['id']]);
                            //and give that money to the seller
                            $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                                ->execute([$price, $listing['user']]);
                            //give the sold gems to the buyer (they've already been taken from the seller)
                            $dbh->prepare("UPDATE users SET `$gem` = `$gem` + ? WHERE id = ?")
                                ->execute([$amount, $user['id']]);
                            $user['money'] -= $price;
                        } else if ($listing['type'] == 3) {
                            if ($user_gem_amount < $amount)
                                show_info("You don't have enough to do that");
                            //take the gems from the seller (the person who just clicked sell)
                            $dbh->prepare("UPDATE users SET `$gem` = `$gem` - ? WHERE id = ?")
                                ->execute([$amount, $user['id']]);
                            //and give those gems to the buyer
                            $dbh->prepare("UPDATE users SET `$gem` = `$gem` + ? WHERE id = ?")
                                ->execute([$amount, $listing['user']]);
                            //give the money to the seller (it's already been taken from the buyer)
                            $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                                ->execute([$price, $user['id']]);
                            $user['money'] += $price;
                        }

                        $dbh->prepare("UPDATE marketplace_listings SET amount = amount - ? WHERE id = ?")
                            ->execute([$amount, $listing['id']]);
                        $listing['amount'] -= $amount;

                        if ($listing['amount'] <= 0) {
                            $dbh->prepare("DELETE FROM marketplace_listings WHERE id = ?")
                                ->execute([$listing['id']]);
                            send_msg(0, $listing['user'], implode(",", [$listing['id'], $listing['type'], $listing['gem']]), 3);
                        }
                        show_info("Success!", "Success!");
                    }
                }
            } else {
                $message = implode(",", [dechex($id), $listing['type'], $listing['amount'], $listing['gem'], $listing['price']]);
                if (msgs_left_to_send($user['id'], $listing['user']) > 0) {
                    if ($listing['type'] == 0) {
                        if ($user['money'] < $listing['price'])
                            throw_error("You need ".display_money($listing['price'])." to buy this.");
                        //take the money from the buyer
                        $dbh->prepare("UPDATE users SET money = money - ? WHERE id = ?")
                            ->execute([$listing['price'], $user['id']]);
                        $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                            ->execute([$listing['price'], $listing['user']]);
                        //give the sold gems to the buyer (they've already been taken from the seller)
                        $dbh->prepare("UPDATE users SET `$gem` = `$gem` + ? WHERE id = ?")
                            ->execute([$listing['amount'], $user['id']]);
                    } else if ($listing['type'] == 1) {
                        if ($user_gem_amount < $listing['amount'])
                            throw_error("You need ".$listing['amount']."mpx of $gem_name to do that.");
                        //take the gems from the seller (the person who just clicked sell)
                        $dbh->prepare("UPDATE users SET `$gem` = `$gem` - ? WHERE id = ?")
                            ->execute([$listing['amount'], $user['id']]);
                        //and give those gems to the buyer
                        $dbh->prepare("UPDATE users SET `$gem` = `$gem` + ? WHERE id = ?")
                            ->execute([$listing['amount'], $listing['user']]);
                        //give the money to the seller (it's already been taken from the buyer)
                        $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                            ->execute([$listing['price'], $user['id']]);
                    }
                    $dbh->prepare("DELETE FROM marketplace_listings WHERE id = ?")
                        ->execute([$listing['id']]);
                    send_msg($user['id'], $listing['user'], $message, 2);
                    redirect("/finance/marketplace");
                } else
                    show_info("You can't interact with this user. They might have blocked you or you might have interacted with them too much recently.");
            }
        }
    }
} else
    throw_error("You must specify a collection.");

gen_top("A marketplace listing");
?>
<?=user_background($listing['user'])?>
<p>
    <?php
    user_button($listing['user']);
    if ($listing['type'] == 0 or $listing['type'] == 1) {
    ?>
        has been looking to <?=$listing['type'] == 0 ? "sell" : "buy"?> <?=$listing['amount']?>mpx of <?=gem_displayer($all_gems[$listing['gem']]->id).$gem_name?> for <?=display_money($listing['price'])?> since <span class="unix-ts"><?=$listing['created']?></span>. Thats <?=round($listing['price']/$listing['amount']/100, 8).$currency_symbol?>/mpx or <?=round($listing['amount']/$listing['price']/100, 8)."mpx/$currency_symbol"?>.
        
        <?php if ($is_logged_in) { if ($user['id'] != $listing['user']) { ?>
        <form action="" method="post">
            <button class="btn btn-primary" type="submit" name="buy" value="true"><?=$listing['type'] == 0 ? "Buy" : "Sell"?></button>
        </form>
        <?php } } else { ?>
        <p>Login to interact</p>
        <?php } ?>
    <?php } else if ($listing['type'] == 2 or $listing['type'] == 3) { ?>
        <?=$listing['type'] == 2 ? "'s shop - selling" : "is collecting"?> <?=gem_displayer($all_gems[$listing['gem']]->id).$gem_name?> for <span id="listingPrice"><?=display_money($listing['price']/1000, 3, false)?></span><?=$currency_symbol?>/mpx.
        <?php if ($listing['type'] == 2) { ?>
            There's <?=$listing['amount']?>mpx left.
        <?php } else if ($listing['type'] == 3) { ?>
            They need <?=$listing['amount']?>mpx more.
        <?php } if ($is_logged_in) { if ($user['id'] != $listing['user']) { ?>
        <form class="form-inline" action="" method="post">
            <label><?=$listing['type'] == 2 ? "Buy" : "Sell"?>:</label>
            <div class="input-group mb-2" style="max-width: 200px">
                <input class="form-control" id="gemAmount" type="number" name="amount" min="0" max="<?=$listing['amount']?>" value="1" onchange="updatePrice()">
                <div class="input-group-prepend">
                    <div class="input-group-text">mpx</div>
                </div>
            </div>
            <span>of <?=$gem_name?> for <span id="price"></span>?&nbsp</span>
            <button class="btn btn-primary" type="submit" name="buy" value="true"><?=$listing['type'] == 2 ? "Buy" : "Sell"?></button>
        </form>
        <script>
        function updatePrice() {
            $("#price").html(displayMoney(Number($("#gemAmount").val())*Number($("#listingPrice").html())*100, 0, "floor"));
        }
        updatePrice();
        </script>
        <?php } } else { ?>
        <p>Login to interact</p>
        <?php } ?>
    <?php }
    if ($is_logged_in) { ?>
        <?php if($user['id'] == $listing['user']) { ?>
        <form action="delete-listing.php" method="post">
            <button class="btn btn-outline-danger" type="submit" name="id" value="<?=$listing['id']?>">Delete</button>
            <p>Deleting this will refund your <?=($listing['type'] == 0 or $listing['type'] == 2) ? "gems" : "money"?>.</p>
        </form>
        <?php } else { ?>
        You have <?=$user_gem_amount?>mpx of <?=$gem_name?>.
        <?php }
    } ?>
</p>

<?php gen_bottom(); ?>