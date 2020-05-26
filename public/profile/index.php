<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();


if (isset($_GET['user'])) {
    if (user_exists($_GET['user'])) {
        $sth = $dbh->prepare("SELECT * FROM users WHERE name = ?;");
        $sth->execute([$_GET['user']]);
        $user_found = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
        gen_top("GEMiner - ".$user_found['name']."'s profile");
        ?>
        <h1>
        <a href="/collection/view.php?id=<?=dechex(get_pfp_collection_id($user_found['id']))?>">
            <?=generate_pfp_collection($user_found['id'])?>
        </a>
        <?=htmlentities($user_found['name'])?>
        <?php if ($user_found['is_admin']) { ?>
        <img src="/a/i/profile-badges/admin.png" class="profile-badge" data-toggle="tooltip" title="<?=htmlentities($user_found['name'])?> is a GEMiner admin.">
        <?php } if ($user_found['is_premium']) { ?>
        <img src="/a/i/profile-badges/premium.png" class="profile-badge" id="profile-badge-premium" data-toggle="tooltip" data-html="true">
        <tooltipcontent for="profile-badge-premium"><?=htmlentities($user_found['name'])?> has been a premium member of GEMiner since <span class='unix-ts'><?=$user_found['date_became_premium']?></span>.</tooltipcontent>
        <?php } ?>
        <img src="/a/i/profile-badges/member.png" class="profile-badge" id="profile-badge-member" onclick="console.log($('.tooltip').html())" data-toggle="tooltip" data-html="true">
        <tooltipcontent for="profile-badge-member"><?=htmlentities($user_found['name'])?> has been a member of GEMiner since <span class='unix-ts'><?=$user_found['date_signed_up']?></span>.</tooltipcontent>
        </h1>
        <p><?=htmlentities($user_found['name'])?> has completed <?=$user_found['shifts_completed']?> shifts.</p>

        <hr>
        <?php if ($is_logged_in and $user_found['id'] == $user['id']) { ?>
        <h3>This is you.</h3>
        <a href="edit.php" class="btn btn-primary">Edit your profile</a>
        <br>
        <a href="/collection/create.php" class="btn btn-primary">Create new collection</a>
        <hr>
        <?php } ?>
        <h2>Collections</h2>
        <?php
        $sth = $dbh->prepare("SELECT id, name FROM collections WHERE by = ?;");
        $sth->execute([$user_found['id']]);
        $collections = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($collections as $collection) {
            ?>
            <a href="/collection/view.php?id=<?=dechex($collection['id'])?>">
                <h4>
                    <?=generate_collection_image($collection['id'])?>
                    <?=htmlentities($collection['name'])?>
                </h4>
            </a>
            <?php
        }
    } else {
        gen_top("GEMiner - unknown user");
        ?>
        <h1>User not found</h1>
        <?php
    }
} else {
    if ($is_logged_in) 
        redirect("?user=".$user['name']);
    else {
        gen_top("GEMiner - unknown user");
        ?>
        <h1>No user specified</h1>
        <?php
    }
}

gen_bottom();