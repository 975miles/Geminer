<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
gen_top("The richest users");
$show_amount = 100;
$sth = $dbh->prepare("SELECT id, money FROM users ORDER BY money DESC LIMIT $show_amount");
$sth->execute();
$richest = $sth->fetchAll();
?>

<h1>The top <?=$show_amount?> richest Geminer users</h1>
<?php
$place = 1;
foreach ($richest as $rich) {
    ?>
<p>
    <?=$place++?>: <?=user_button($rich['id'])?> with <?=display_money($rich['money'])?>
</p>
<?php } ?>

<?php gen_bottom(); ?>