<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
gen_top("Geminer", "Mine digital gems for real distraction.");
?>

<h1>Geminer</h1>
<div class="container-fluid" style="padding: 1em">
    <p class="lead" style="margin: 0">
        <?=$installation_info?>
    </p>
</div>
<br>
<?php
$sth = $dbh->prepare("SELECT COUNT(1) FROM announcements");
$sth->execute();
if ($sth->fetchColumn() > 0) {
    $sth = $dbh->prepare("SELECT * FROM announcements ORDER BY date DESC");
    $sth->execute();
    $announcement = $sth->fetch();
    $bg = $profile_backgrounds[get_user_by_id($announcement['by'])['profile_background']];
?>
<div class="container-fluid rounded border border-dark" style="padding: 1em; background: <?=$bg->bgshort?>; color: <?=$bg->text_colour?>;">
    <p><a href="/announcements.php">Announcement</a> by <?=user_button($announcement['by'])?> on <span class="unix-ts"><?=$announcement['date']?></span></p>
    <hr>
    <p style="margin: 0">
        <?=linkify(htmlentities($announcement['text']))?>
    </p>
</div>
<?php } ?>
<br>
<p>
    (put description of geminer here)
</p>

<?php gen_bottom(); ?>