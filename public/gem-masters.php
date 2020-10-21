<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";

$show_amount = 100;

if (isset($_GET['gem'])) {
    $gem_id = intval($_GET['gem']);
    if (array_key_exists($gem_id, $all_gems)) {
        $gem = $all_gems[$gem_id];
        $sth = $dbh->prepare("SELECT id, `".$gem->id."` FROM users ORDER BY `".$gem->id."` DESC LIMIT $show_amount");
        $sth->execute();
        $masters = $sth->fetchAll();

        gen_top($gem->name." masters");
        ?>
        <h1><?=gem_displayer($gem->id)?><?=$gem->name?> Masters</h1>
        <p>Here are the <?=$show_amount?> users with the most <?=$gem->name?>.</p>
        <?php
        $i = 1;
        foreach ($masters as $master) {
            ?>
            <p>
                <?=$i++?>: <?=user_button($master['id'])?> with <?=number_format($master[$gem->id])?>mpx
            </p>
            <?php
        }
    } else {
        gen_top();
        ?>
        <h2>That's not a valid gem ID!</h2>
        <?php
    }
} else {
    gen_top("Gem masters");
    ?>

    <h1>Gem masters</h1>
    <p>For each gem, here is the user with the highest amount of it. Click on a gem to see the top <?=$show_amount?> for that gem.</p>
    <?php
    usort($all_gems, function($a, $b) {
        return strcmp($a->name, $b->name);
    });
    foreach ($all_gems as $gem) {
        $sth = $dbh->prepare("SELECT id, `".$gem->id."` FROM users ORDER BY `".$gem->id."` DESC LIMIT 1");
        $sth->execute();
        $master = $sth->fetch();
        ?>
        <p>
            <a href="?gem=<?=$gem->id?>"><?=gem_displayer($gem->id)?><?=$gem->name?></a>: <?=user_button($master['id'])?> with <?=number_format($master[$gem->id])?>mpx
        </p>
    <?php } ?>
<?php } gen_bottom(); ?>