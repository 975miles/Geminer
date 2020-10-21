<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";

if (isset($_POST['handle'], $_POST['head'], $_POST['binding'], $_POST['modifiers'])) {
    $valid_pick = true;
    $new_pick_parts = Array();
    foreach (['handle', 'head', 'binding'] as $partname) {
        $sth = $dbh->prepare("SELECT id, material FROM pickaxe_parts WHERE type = ? AND id = ? AND owner = ?");
        $sth->execute([$partname, intval($_POST[$partname]), $user['id']]);
        $part_found = $sth->fetchAll();

        if (count($part_found) > 0)
            $new_pick_parts[$partname] = $part_found[0];
        else
            $valid_pick = false;
    }
    if (!$valid_pick)
        die("false");
    $handle_id = intval($_POST['handle']);
    $head_id = intval($_POST['head']);
    $binding_id = intval($_POST['binding']);
    $modifiers = Array();
    if ($_POST['modifiers'] != "" && gettype($_POST['modifiers']) == "string") {
        foreach (explode(",", $_POST['modifiers']) as $modifier_id) {
            $modifier_id = intval($modifier_id);
            $sth = $dbh->prepare("SELECT id, type FROM modifiers WHERE id = ? AND owner = ?");
            $sth->execute([$modifier_id, $user['id']]);
            $modifier_found = $sth->fetchAll();
            if (count($modifier_found) > 0)
                array_push($modifiers, $modifier_found[0]);
            else
                $valid_pick = false;
        }
        if (!$valid_pick)
            die("false");
        $modifier_limit = 2;
        $type_list = Array();
        foreach ($modifiers as $modifier) {
            if (in_array($modifier['type'], $type_list, true)) {
                $valid_pick = false;
            }
            array_push($type_list, $modifier['type']);
            if ($modifier['type'] == 4)
                $modifier_limit += 2;
        }
        if (count($modifiers) > $modifier_limit)
            $valid_pick = false;
        if (!$valid_pick)
            die("false");
        sort($type_list);
        $modifier_string = implode(",", $type_list);
    } else
        $modifier_string = "";
    
    foreach ($modifiers as $modifier)
        $dbh->prepare("DELETE FROM modifiers WHERE id = ?")
            ->execute([$modifier['id']]);

    foreach ($new_pick_parts as $new_pick_part)
        $dbh->prepare("DELETE FROM pickaxe_parts WHERE id = ?")
            ->execute([$new_pick_part['id']]);
    
    $dbh->prepare("INSERT INTO pickaxes (handle, head, binding, modifiers, owner, name, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)")
        ->execute([$new_pick_parts['handle']['material'], $new_pick_parts['head']['material'], $new_pick_parts['binding']['material'], $modifier_string, $user['id'], $all_gems[$new_pick_parts['head']['material']]->name." pickaxe", time()]);

    die("true");
}

gen_top("Creating a pickaxe...");
$sth = $dbh->prepare("SELECT id, material, type FROM pickaxe_parts WHERE owner = ?");
$sth->execute([$user['id']]);
$user_parts = $sth->fetchAll();
$sth = $dbh->prepare("SELECT id, type FROM modifiers WHERE owner = ?");
$sth->execute([$user['id']]);
$user_modifiers = $sth->fetchAll();
?>

<link rel="stylesheet" href="/a/css/inventory.css">
<h1>Pickaxe creation</h1>
<a class="btn btn-sm btn-secondary" href="/dash/pickaxes">Back to your pickaxes</a>
<hr>
<h3>Parts</h3>
<div class="form-inline">
    <label>Filter - show only: </label>
    <select class="form-control" id="partFilterSelect" onchange="filterParts(this.value)">
        <option value="none">(everything)</option>
    </select>
</div>
<div id="inventory"></div>
<a class="btn btn-secondary" href="create-part">Create new part</a>
<hr>
<h3 id="pickaxeTitle"></h3>
<img id="pickaxeImg" class="collection-img" height="256">
<h4>Stats:</h4>
<p id="pickaxeStats"></p>
<button class="btn btn-lg btn-primary" onclick="submit()" id="submitButton">Create</button>
<script src="/a/js/pickconsts.js"></script>
<script src="/a/js/pickaxe.js"></script>
<script>
var myParts = <?=json_encode($user_parts)?>;
var myModifiers = <?=json_encode($user_modifiers)?>;
</script>
<script src="/a/js/dash/pickaxes/create.js"></script>
<?php gen_bottom(); ?>