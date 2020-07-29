<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/msgs_left_to_send.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/inbox/send_msg.php";
gen_top("Sending a message...");
require_auth();

if (isset($_GET['to'])) {
    if (user_exists($_GET['to'])) {
        $sth = $dbh->prepare("SELECT * FROM users WHERE name = ? COLLATE NOCASE");
        $sth->execute([$_GET['to']]);
        $user_to_send_to = $sth->fetch(PDO::FETCH_ASSOC);

        $max_message_length = ($user['is_premium'] ? $max_sent_message_length_premium : $max_sent_message_length_free);
        $msgs_left = msgs_left_to_send($user['id'], $user_to_send_to['id']);

        if ($msgs_left == 0)
            show_info("You don't have any messages left to send to this user.");
        else if (isset($_POST['msg'])) {
            $msg = $_POST['msg'];
            if (mb_strlen($msg) < 1)
                show_info("You must send some text.");
            else if (mb_strlen($msg) > $max_message_length)
                show_info("Your message must be at most $max_message_length characters.");
            else {
                send_msg($user['id'], $user_to_send_to['id'], $msg, 0);
                throw_error("Message successfully sent.", "Success!");
            }
        }
    } else
        throw_error("That user doesn't exist. Go to the profile of a user and click the \\\"Message this user\\\" button.");
} else
    throw_error("You haven't provided a user to send the message to. Go to the profile of a user and click the \\\"Message this user\\\" button.");
?>
<h3>Sending a message to <?=htmlentities($user_to_send_to['name'])?>...<h3>
<p class="lead">You can send <?=$msgs_left?> more messages to <?=user_button($user_to_send_to['id'])?>.</p>


<form action="" method="post">
    <textarea class="form-control" name="msg" placeholder="Your message" maxlength="<?=$max_message_length?>" required></textarea>
    <button class="btn btn-primary" type="submit">Send</button>
</form>

<?php gen_bottom(); ?>