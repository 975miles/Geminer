<?php
function user_button($user_id) {
    global $dbh;
    $user_found = get_user_by_id($user_id);
    ?>
<a class="btn btn-secondary" href="/profile?user=<?=htmlentities($user_found['name'])?>">
    <?=generate_collection_image(get_pfp_collection_id($user_id))?>
    <?=htmlentities($user_found['name'])?>
</a>
    <?php
}