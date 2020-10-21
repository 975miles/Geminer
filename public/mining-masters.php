<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";
gen_top("Mining masters");
$show_amount = 500;
?>
<h1>Mining masters</h1>
<p>Here are the top <?=$show_amount?> highest leveled users.</p>

<?php
$sth = $dbh->prepare("SELECT id, shifts_completed FROM users ORDER BY shifts_completed DESC LIMIT $show_amount");
$sth->execute();
$masters = $sth->fetchAll();
$i = 1;
foreach ($masters as $master) {
    ?>
    <p>
        <?=$i++?>: <?=user_button($master['id'])?> is level <?=get_level($master['shifts_completed'])?> with <?=number_format($master['shifts_completed'])?> mining shifts
    </p>
<?php } gen_bottom(); ?>