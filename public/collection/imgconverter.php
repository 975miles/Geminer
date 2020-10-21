<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Convert an image to a collection");
require_auth();

$reached_maximum = true;
$max_collection_amount = ($user['is_premium'] ? $collection_storage_limit_premium : $collection_storage_limit_free);

$sth = $dbh->prepare("SELECT COUNT(1) FROM collections WHERE by = ?");
$sth->execute([$user['id']]);
$collection_amount = $sth->fetchColumn();

if ($collection_amount < $max_collection_amount)
    $reached_maximum = false;
?>

<h1>Image converter</h1>
<div class="form-inline">
    <label>Select a collection size:&nbsp</label>
    <select class="form-control" id="sizeSelect"></select>
</div>
<label>
    <input class="d-none" type="file" onchange="uploadImage(this)">
    <span class="btn btn-primary">Choose image...</span>
</label>
<hr>
<div id="generatedCollection">
    <p>Upload an image and see what it would look like as a collection and what gems you'd need to make it.</p>
</div>

<script>
var reachedMaximum = <?=$reached_maximum ? "true" : "false"?>;
</script>
<script src="/a/js/imgconverter.js"></script>

<?php gen_bottom(); ?>