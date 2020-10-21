<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/locations.php";
gen_top($is_logged_in ? $locations[$user['location']]->name : "The mines", "You can mine gems here");
require_auth();

$sth = $dbh->prepare("SELECT id, handle, head, binding, modifiers, uses, name FROM pickaxes WHERE owner = ? AND starred = 1");
$sth->execute([$user['id']]);
$starred_picks = $sth->fetchAll();
?>

<link rel="stylesheet" href="/a/css/inventory.css">

<h1>The Mine</h1>
<p>You're currently in <b><?=$locations[$user['location']]->name?></b>. <a href="/dash/location">Move</a></p>

<hr>
<div style="display: inline-block">
    Mine
    <input id="timesToMine" style="max-width:4em" type="number" name="amount" min="1" max="1000000000" value="1" onchange="showEnergyCost()">
    <p style="display: inline-block">times (costs <span id="miningEnergyCost"><span class="spinner-border spinner-border-sm" role="status"><span class="sr-only"></span></span></span> <img src="/a/i/energy.png" class="energy-icon">)</p>
    <br>
    <div class="custom-control custom-checkbox custom-control-inline">
        <input class="custom-control-input" type="checkbox" value="true" id="animationCheck">
        <label class="custom-control-label" for="animationCheck">
            Play animation
        </label>
    </div>
</div>
<br>
<br>
<div id="inventory"></div>
<hr>

<center><h4>Level <span id="levelNum">...</span></h4></center>
<div class="progress">
  <div class="progress-bar bg-info" id="currentXp" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
  <div class="progress-bar bg-light text-dark" id="xpLeft" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<hr>

<div class="container-fluid rounded border border-dark">
    <br>
    <h3>Your gems</h3>
    <hr>
    <div id="gems"><div class="spinner-border" role="status"><span class="sr-only"></span></div></div>
    <hr>
    <a class="btn btn-primary" href="/finance/sell-gems" style="margin-bottom: 0.8em">Sell gems</a>
</div>

<script src="/a/js/pickconsts.js"></script>
<script src="/a/js/pickaxe.js"></script>
<script>
var starredPicks = <?=json_encode($starred_picks)?>;
</script>
<script src="/a/js/dash/mining.js"></script>

<?php gen_bottom(); ?>