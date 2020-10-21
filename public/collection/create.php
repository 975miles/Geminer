<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/collection-types.php";
gen_top("Creating a collection...");

$max_collection_amount = ($user['is_premium'] ? $collection_storage_limit_premium : $collection_storage_limit_free);

$sth = $dbh->prepare("SELECT COUNT(1) FROM collections WHERE by = ?");
$sth->execute([$user['id']]);
$collection_amount = $sth->fetchColumn();
if ($collection_amount >= $max_collection_amount)
    throw_error(($user['is_premium'] ? "You can't have more than $max_collection_amount collections." : "You've reached your maximum of $max_collection_amount collections. Want more? <a href='/premium'>Upgrade to premium</a>."));

if (isset($_POST['name']) and isset($_POST['type'])) {
    $_POST['type'] = intval($_POST['type']);
    if (mb_strlen($_POST['name']) > $max_collection_name_length)
        show_info("Collection name must be at most $max_collection_name_length characters.");
    else if (mb_strlen($_POST['name']) <= 0)
        show_info("Collection name can't be empty.");
    else if (!array_key_exists($_POST['type'], $collection_types))
        show_info($_POST['type']." isn't a valid collection size.");
    else {
        $empty_collection = Array();
        $collection_type = $collection_types[$_POST['type']];
        if ($collection_type->premium and !$user['is_premium'])
            show_info("You need to be premium to use that collection size.");
        else if (isset($collection_type->level) and $collection_type->level > get_level($user['shifts_completed']))
            show_info("You're the wrong level.");
        else {
            for ($row = 0; $row < $collection_type->height; $row++) {
                $collection_row = Array();
                for ($column = 0; $column < $collection_type->width; $column++) {
                    array_push($collection_row, -1);
                }
                array_push($empty_collection, $collection_row);
            }

            $dbh->prepare("INSERT INTO collections (type, name, by, created_at, data) VALUES (?, ?, ?, ?, ?)")
                ->execute([$_POST['type'], $_POST['name'], $user['id'], time(), json_encode($empty_collection)]);

            redirect("/collection/edit?id=".dechex($dbh->lastInsertId()));
        }
    }
}
?>

<h1>Create a new collection</h1>
<p>You currently have <?=$collection_amount?> collection<?=$collection_amount == 1 ? "" : "s"?> out of your maximum of <?=$max_collection_amount?>.<?php if (!$user['is_premium']) { ?> <a href="/premium">Upgrade to premium</a> to increase this maximum.<?php } ?></p>
<form action="" method="post">
    <label>Collection name:</label>
    <input type="text" name="name" class="form-control" value="My Epic Collection" maxlength=<?=$max_collection_name_length?>>
    <label>Size:</label>
    <select class="form-control" name="type" id="typeSelect"></select>
    <button type="submit" class="btn btn-lg btn-primary">Create</button>
</form>

<hr>
<h2>Sizes</h2>
<div id="sizes"></div>

<script>
const tileWidth = 2;
const lineWidth = 1;
const fullWidth = tileWidth + lineWidth;

$.getJSON("/a/data/collection-types.json", types => {
    let level = getLevel().level;
    for (let i in types) {
        let type = types[i];
        let underleveled = type.level != null && type.level > level;
        if (type.premium && !user.is_premium)
            continue;
        let canvas = $('<canvas id="'+i+'">')[0];
        canvas.width = type.width * fullWidth + lineWidth;
        canvas.height = type.height * fullWidth + lineWidth;

        let ctx = canvas.getContext("2d");
        ctx.fillStyle = "black";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = underleveled ? "grey" : "white";
        for (let x = 0; x < canvas.width; x += fullWidth) {
            for (let y = 0; y < canvas.height; y += fullWidth) {
                ctx.fillRect(x+lineWidth, y+lineWidth, tileWidth, tileWidth);
            }
        }

        let typeDisplay = `${type.name} - ${type.width}px*${type.height}px${underleveled ? " - unlocks at level "+type.level : ""}`;
        let div = $(`<div><label>${typeDisplay}</label><br></div>`)
        div.append($(canvas));
        div.append("<br><br>");
        $("#sizes").append(div);
        if (!underleveled)
            $("#typeSelect").append($(`<option value="${i}">${typeDisplay}</option>`));
    }
});
</script>

<?php gen_bottom(); ?>