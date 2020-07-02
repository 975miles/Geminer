<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
gen_top("Announcements", "What's going on with Geminer.");

if ($is_logged_in and !$user['read_announcements']) {
    $dbh->prepare("UPDATE users SET read_announcements = 1 WHERE id = ?")
        ->execute([$user['id']]);
    redirect("");
}

use JasonGrimes\Paginator;

$announcements_shown_per_page = 25;
$page_number = 1;

if (isset($_GET['page']))
    $page_number = intval($_GET['page']);

$sth = $dbh->prepare("SELECT * FROM announcements ORDER BY date DESC LIMIT $announcements_shown_per_page OFFSET ".($page_number-1)*$announcements_shown_per_page);
$sth->execute();
$announcements = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth = $dbh->prepare("SELECT COUNT(1) FROM announcements");
$sth->execute();
$announcement_count = $sth->fetchColumn();
?>
<h1>Announcements</h1>
<?php if ($user['is_admin']) { ?>
<hr>
<a class="btn btn-primary" href="/admin/announce">Make an announcement</a>
<?php } ?>
<hr>
<?php
$position = ($page_number-1)*$announcements_shown_per_page;
foreach ($announcements as $announcement) {
    $bg = $profile_backgrounds[get_user_by_id($announcement['by'])['profile_background']];
    ?>
    <div class="container-fluid rounded border border-dark" style="padding: 1em; background: <?=$bg->bgshort?>; color: <?=$bg->text_colour?>;">
        <p><?=user_button($announcement['by'])?> on <span class="unix-ts"><?=$announcement['date']?></span>:</p>
        <hr>
        <p style="margin: 0">
            <?=linkify(htmlentities($announcement['text']))?>
        </p>
        <?php if ($user['is_admin']) { ?>
        <hr>
        <form action="/admin/delete-announcement.php" method="post">
            <button class="btn btn-outline-danger" type="submit" name="id" value=<?=$announcement['id']?>>Delete</button>
        </form>
        <?php } ?>
    </div>
<?php } ?>
<hr>
<?php
$paginator = new Paginator($announcement_count, $announcements_shown_per_page, $page_number, "(:num)");
require $_SERVER['DOCUMENT_ROOT']."/../pages/pagination.php";
gen_bottom();
?>