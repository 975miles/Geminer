<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/real_size.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/max_size.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/msgs_left_to_send.php";
gen_top("Notifications", "Stuff that's happened recently involving you");
require_auth();
if (!$user['read_notifications']) {
    $dbh->prepare("UPDATE users SET read_notifications = 1 WHERE id = ?")->execute([$user['id']]);
    redirect("");
}

$sth = $dbh->prepare("SELECT * FROM messages WHERE recipient = ? ORDER BY date DESC");
$sth->execute([$user['id']]);
$notifications = $sth->fetchAll();
?>

<h1>Inbox</h1>
<?php if (!$user['read_announcements']) { ?>
<p class="container-fluid rounded border border-dark bg-dark text-light" style="padding:10px">You have unread announcements. <a class="btn btn-primary" href="/announcements">Read announcements</a></p>
<?php } ?>
<p>Messages <?=hover_about("Getting sent money, your marketplace listing getting bought, and getting sent a message will all count as a message")?> in your inbox:  <?=real_inbox_size($user['id'])?>/<?=max_inbox_size($user['id'])?> <?=hover_about("You get a message whenever someone buys one of your marketplace listings. Because of this, they have to take one of your inbox slots to make sure your inbox doesn't overflow. The total amount of messages in your inbox shown includes these. The amount of REAL messages currently in your inbox is ".count($notifications).".")?>. <?php if (!$user['is_premium']) { ?><a href="/premium">Upgrade to premium</a> to increase your inbox size.<?php } ?></p>
<?php
if (count($notifications) > 0) {
    foreach ($notifications as $notification) {
        if ($notification['sender'] != 0)
            $bg = $profile_backgrounds[get_user_by_id($notification['sender'])['profile_background']];
        $message = explode(",", $notification['message']);
        $msgs_left = msgs_left_to_send($user['id'], $notification['sender']);
        $msgs_left_from = msgs_left_to_send($notification['sender'], $user['id']);
        if ($notification['type'] == 0)
            $action = "sent you a message";
        else if ($notification['type'] == 1)
            $action = "gave you ".display_money($message[0]);
        else if ($notification['type'] == 2)
            //dechex($id), $listing['type'], $listing['amount'], $listing['gem'], $listing['price']
            $action = "bought your marketplace listing (id $message[0]). ".($message[1] == 0 ? display_money($message[4]) : "$message[2]mpx of ".gem_displayer($message[3]).$all_gems[$message[3]]->name)." was ".($message[1] == 0 ? "credited" : "added")." to your account";
        else if ($notification['type'] == 3)
            $action = "your marketplace listing (id $message[0]) which was ".($message[1] == 2 ? "a shop selling " : "collecting ").gem_displayer($message[2]).$all_gems[$message[2]]->name.($message[1] == 2 ? " ran out of stock" : " finished collecting");
        ?>
        <div class="container-fluid rounded border border-dark" style="padding: 1em; overflow: hidden;<?php if ($notification['sender'] != 0) { ?> background: <?=$bg->bgshort?>; color: <?=$bg->text_colour?>;<?php } ?>">
            <div class="row ml-1">
                <p style="margin: 0">
                <?php if ($notification['sender'] != 0) { ?>
                <?=user_button($notification['sender'])?><span class="badge badge-<?=$msgs_left_from <= 0 ? "danger" : "secondary"?>" data-toggle="tooltip" title="This user can interact with you <?=$msgs_left_from?> more time<?=$msgs_left_from == 1 ? "" : "s"?>. Delete/dismiss some of their messages/interactions to allow them to interact with you more."><?=$msgs_left_from?></span>
                <?php } else { ?>
                System:&nbsp
                <?php } ?>
                <?=$action?> on <span class="unix-ts"><?=$notification['date']?></span>.</p>
                <div class="ml-auto">
                    <form class="form-inline" action="dismiss.php" method="post">
                        <?php if ($notification['sender'] != 0) { ?>
                        <a class="btn btn-<?=$msgs_left <= 0 ? "outline-primary disabled" : "primary"?>" href="/message/send?to=<?=htmlentities(get_user_by_id($notification['sender'])['name'])?>">Reply <span class="badge badge-dark"><?=$msgs_left?></span></a>
                        <?php } ?>
                        <button class="btn btn-danger" name="id" value="<?=$notification['id']?>" type="submit"><?=$notification['type'] == 0 ? "Delete" : "Dismiss" ?></button>
                    </form>
                </div>
            </div>
            
            
            <?php if ($notification['type'] == 0) { ?>
                <hr>
                <p style="margin: 0">
                    <?=linkify(htmlentities(implode(",", $message)))?>
                </p>
            <?php } ?>
        </div>
<?php
    }
} else { ?>
<p><i>There's nothing here.</i></p>
<?php } ?>

<?php gen_bottom(); ?>