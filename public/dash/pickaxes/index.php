<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Pickaxes");
require_auth();
$sth = $dbh->prepare("SELECT id, handle, head, binding, modifiers, uses, name, starred FROM pickaxes WHERE owner = ?");
$sth->execute([$user['id']]);
$pickaxes_found = $sth->fetchAll();
?>

<link rel="stylesheet" href="/a/css/inventory.css">
<h1>Your pickaxes</h1>
<div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" id="starredOnlyCheck" onchange="toggleShowed()">
    <label class="custom-control-label" for="starredOnlyCheck">Show starred picks only</label>
</div>
<div id="inventory"></div>
<a class="btn btn-primary" href="create">Create a new pickaxe</a>
<script src="/a/js/pickconsts.js"></script>
<script src="/a/js/pickaxe.js"></script>
<script>
var myPicks = <?=json_encode($pickaxes_found)?>;
var maxNameLength = <?=$max_pick_name_length?>;
</script>
<script src="/a/js/dash/pickaxes/index.js"></script>
<?php gen_bottom(); ?>