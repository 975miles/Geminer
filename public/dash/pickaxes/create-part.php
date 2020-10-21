<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Creating a part...");
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/parts.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";

if (isset($_POST['type'], $_POST['material'])) {
    $type = $_POST['type'];
    $material = intval($_POST['material']);
    if (!array_key_exists($type, $part_types))
        show_info("That part type doesn't exist.");
    else if (!array_key_exists($material, $all_gems))
        show_info("That gem doesn't exist.");
    else if ($user[$material] < ($part_price = $part_types->$type->price))
        show_info("You need ".($part_price/1000)."px of ".$all_gems[$material]->name." to make that.");
    else {
        $dbh->prepare("INSERT INTO pickaxe_parts (type, material, owner) VALUES (?, ?, ?)")
            ->execute([$type, $material, $user['id']]);
        $dbh->prepare("UPDATE users SET `".$material."` = `".$material."` - ? WHERE id = ?")
            ->execute([$part_price, $user['id']]);
        redirect("/dash/pickaxes/create");
    }
}
?>

<h1>Create a pickaxe part:</h1>
<hr>
<form action="" method="post">
    <div class="form-inline">
        <label>Part:</label>
        <select class="form-control" id="partType" name="type" onchange="visualisePart()"></select>
    </div>
    <div class="form-inline">
        <label>Material:</label>
        <select class="form-control" id="material" name="material" onchange="visualisePart()"></select>
    </div>
    <hr>
    <img id="partImg" class="collection-img" height="256" style="margin-bottom: 2em">
    <h4>Part stats:</h4>
    <p id="partInfo"></p>
    <hr>
    <button class="btn btn-primary" type="submit">Create part</button>
    <p id="partCost"></p>
</form>
<script src="/a/js/pickconsts.js"></script>
<script src="/a/js/pickaxe.js"></script>
<script src="/a/js/dash/pickaxes/create-part.js"></script>
<?php gen_bottom(); ?>