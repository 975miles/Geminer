<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Your alloying casts");
require_auth();
$sth = $dbh->prepare("SELECT id, gem, amount, time_started FROM alloy_casts WHERE owner = ?");
$sth->execute([$user['id']]);
$casts = $sth->fetchAll();
?>

<h1>Alloy Area</h1>
<p>Your alloying casts</p>
<hr>
<style>
.card-title {text-align: center;}
.claim {margin-bottom: 0;}
</style>
<div id="casts"></div>
<hr>
<a class="btn btn-primary" href="select">Buy new cast</a>
<script>
var castTime = <?=$alloy_cast_time?>;
var speedUpPrice = <?=$alloy_cast_speed_up_energy_price?>;
var casts = <?=json_encode($casts)?>;
</script>
<script src="/a/js/dash/alloying/index.js"></script>
<?php gen_bottom(); ?>