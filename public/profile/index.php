<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/backgrounds.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/send_msg.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/msgs_left_to_send.php";

if (isset($_GET['user'])) {
    if (user_exists($_GET['user'])) {
        $sth = $dbh->prepare("SELECT * FROM users WHERE name = ? COLLATE NOCASE");
        $sth->execute([$_GET['user']]);
        $user_found = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
        if (isset($_POST['paying'])) {
            require_auth();
            $amount_to_pay = intval(floatval($_POST['paying'])*100);
            if ($amount_to_pay < 1 or $amount_to_pay > 1000000000)
                show_info("That's a wrong number >:(. Don't try to cheese this.");
            else if ($amount_to_pay > $user['money']) {
                show_info("You don't have enough $currency_symbol to pay that.");}
            else {
                //notify the recipient that they've recieved money
                $message = $amount_to_pay;
                if (send_msg($user['id'], $user_found['id'], $message, 1)) {
                    //take the money from the payer
                    $dbh->prepare("UPDATE users SET money = money - ? WHERE id = ?")
                        ->execute([$amount_to_pay, $user['id']]);
                    //give the money to the recipient
                    $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")
                        ->execute([$amount_to_pay, $user_found['id']]);
                    //notify the payer of a successful transaction
                    $user['money'] -= $amount_to_pay; //correct the amount of money shown in the navbar in the browser
                    show_info("You paid ".htmlentities($user_found['name'])." ".display_money($amount_to_pay).".", "Success!");
                } else
                    show_info("You can't interact with this user. They might have blocked you or you might have interacted with them too much recently.");
            }
        }
        gen_top($user_found['name']."'s profile");
        ?>
        <?=user_background($user_found['id'])?>
        <h1>
        <a href="/collection/view?id=<?=dechex(get_pfp_collection_id($user_found['id']))?>">
            <?=generate_pfp_collection($user_found['id'])?>
        </a>
        <?php user_button($user_found['id'], false, "lg"); ?>
        <?php if ($user_found['is_admin']) { ?>
        <img src="/a/i/profile-badges/admin.png" class="profile-badge" data-toggle="tooltip" title="<?=htmlentities($user_found['name'])?> is a Geminer admin.">
        <?php } if ($user_found['is_premium']) { ?>
        <img src="/a/i/profile-badges/premium.png" class="profile-badge" id="profile-badge-premium" data-toggle="tooltip" data-html="true">
        <tooltipcontent for="profile-badge-premium"><?=htmlentities($user_found['name'])?> has been a premium member of Geminer since <span class='unix-ts'><?=$user_found['date_became_premium']?></span>.</tooltipcontent>
        <?php } ?>
        <img src="/a/i/profile-badges/member.png" class="profile-badge" id="profile-badge-member" data-toggle="tooltip" data-html="true">
        <tooltipcontent for="profile-badge-member"><?=htmlentities($user_found['name'])?> has been a member of Geminer since <span class='unix-ts'><?=$user_found['date_signed_up']?></span>.</tooltipcontent>
        </h1>
        <p><?=htmlentities($user_found['name'])?> was last online <span class="unix-ts" id="lastOnline"><?=$user_found['last_login']?></span>.</p>
        <script>
            let gap = (Date.now() / 1000) - Number($("#lastOnline").html());
            const msgs = {
                "60": "a minute",
                "300": "5 minutes",
                "600": "10 minutes",
                "1800": "half an hour",
                "3600": "an hour",
                "21600": "6 hours",
                "43200": "12 hours",
                "86400": "24 hours",
            };

            for (let i in msgs) {
                if (gap < Number(i)) {
                    $("#lastOnline").removeClass("unix-ts").html(`less than ${msgs[i]} ago`);
                    break;
                }
            }
            //if ()
            //$("#lastOnline").parent().append("!");
        </script>
        <p><?=htmlentities($user_found['name'])?> has completed <?=$user_found['shifts_completed']?> shifts.</p>
        <hr>
        <?php
        if ($is_logged_in) {
            ?>
            <?php
            if ($user_found['id'] != $user['id']) {
                $msgs_left = msgs_left_to_send($user['id'], $user_found['id']);
                $sth = $dbh->prepare("SELECT COUNT(1) FROM user_blocks WHERE blocker = ? AND blocked = ?");
                $sth->execute([$user['id'], $user_found['id']]);
                $is_blocked = $sth->fetchColumn();
                ?>
                <h3>Actions</h3>
                <?php if ($msgs_left <= 0) { ?>
                    <p>You can't interact with this user. Either they've blocked you, their inbox is full, or you've interacted with them too much lately and they have to delete some of your messages first.</p>
                <?php } else { ?>
                    <p>You can interact with this user <b><?=$msgs_left?></b> more times before they have to delete some of your messages.</p>
                    <a class="btn btn-sm btn-primary" href="/message/send?to=<?=$user_found['name']?>">Message this user</a>
                    <form class="form-inline" action="" method="post">
                        <button class="btn btn-sm btn-primary" type="submit">Pay this user</button>
                        <div class="input-group mb-2" style="max-width: 200px">
                            <input class="form-control" type="number" name="paying" min="0.01" max="10000000" value="0.01" step="0.01">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><?=$currency_symbol?></div>
                            </div>
                        </div>
                    </form>
                <?php } ?>
                <form action="<?=$is_blocked ? "unblock" : "block"?>.php" method="post">
                    <button class="btn btn-sm btn<?=$is_blocked ? "-outline" : ""?>-danger" type="submit" name="user" value="<?=htmlentities($user_found['name'])?>"><?=$is_blocked ? "Unblock" : "Block"?> this user</button>
                </form>
            <?php } else { ?>
                <h3>Actions (this is you)</h3>
                <a href="edit" class="btn btn-primary">Edit your profile</a>
                <br>
                <a href="/collection/create" class="btn btn-primary">Create new collection</a>
        <?php
            }
        } else {
        ?>
        <p>Log in to interact with this user.</p>
        <?php } ?>
        <hr>
        <h2>Collections</h2>
        <?php
        $sth = $dbh->prepare("SELECT id, name FROM collections WHERE by = ?");
        $sth->execute([$user_found['id']]);
        $collections = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($collections as $collection) {
            ?>
            <a href="/collection/view?id=<?=dechex($collection['id'])?>">
                <span class="lead">
                    <?=place_collection_image($collection['id'])?>
                    <?=htmlentities($collection['name'])?>
                </span>
            </a>
            <br>
            <?php
        }
        ?>
        <hr>
        <h2>Marketplace listings</h2>
        <?php
        $sth = $dbh->prepare("SELECT * FROM marketplace_listings WHERE user = ?");
        $sth->execute([$user_found['id']]);
        $listings = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($listings) > 0) {
            foreach ($listings as $listing) {
                $gem = $all_gems[$listing['gem']];
                ?>
                <p><a href="/finance/marketplace/listing?id=<?=dechex($listing['id'])?>">
                    <?=$listing['type'] == 0 ? "Selling" : "Buying" ?> <?=$listing['amount']?>mP of <?=gem_displayer($gem->id)?></span><?=$gem->name?> for <?=display_money($listing['price'])?>.
                </a></p>
                <?php
            }
        } else {
            ?>
            <p><?=htmlentities($user_found['name'])?> isn't buying or selling any gems in the marketplace at the moment.</p>
            <?php
        }
    } else {
        gen_top("Geminer - unknown user");
        ?>
        <h1>User not found</h1>
        <?php
    }
} else {
    if ($is_logged_in) 
        redirect("?user=".$user['name']);
    else {
        gen_top("Geminer - unknown user");
        ?>
        <h1>No user specified</h1>
        <?php
    }
}

gen_bottom();