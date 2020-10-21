<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/send_msg.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
gen_top("Welcome!");
?>
<h1>Welcome to Geminer!</h1>
<?php
if ($is_logged_in) {
    ?><hr><?php

    if (is_null($user['referred_by'])) {
        $referral_reward = 500000;

        if (isset($_POST['referrer'])) {
            $referrer = trim($_POST['referrer']);
            if (strtolower($referrer) != strtolower($user['name'])) {
                $sth = $dbh->prepare("SELECT * FROM users WHERE name = ? COLLATE NOCASE");
                $sth->execute([$referrer]);
                $referrer = $sth->fetch();
                if ($referrer) {
                    $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ? OR id = ? COLLATE NOCASE")->execute([$referral_reward, $user['id'], $referrer['id']]);
                    $dbh->prepare("UPDATE users SET referred_by = ? WHERE id = ?")->execute([$referrer['id'], $user['id']]);
                    send_msg($user['id'], $referrer['id'], $referral_reward, 7);
                    redirect("");
                } else {
                    ?><p><i>Could not find a user with that username.</i></p><?php
                }
            } else {
                ?><p><i>You can't refer yourself!</i></p><?php
            }
        }
        ?>
        <h3>Did someone tell you about Geminer?</h3>
        <p>If so, and you know their username, enter it here and you'll both get <?=display_money($referral_reward)?>.</p>
        <form class="input-group mb-3" action="" method="post">
            <input class="form-control" type="text" name="referrer" placeholder="Referrer's username">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
        </form>
    <?php } else { ?>
        <p>You were referred by <?=user_button($user['referred_by'])?>.</p>
    <?php } ?>
    <hr> 
<?php } ?>
<p>hi this is the landing page for new members. there will be an introduction here later, so just explore the site</p>

<?php gen_bottom(); ?>