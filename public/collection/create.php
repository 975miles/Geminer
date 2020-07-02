<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/collection-types.php";
gen_top("Geminer - Creating a collection...");

$max_collection_amount = ($user['is_premium'] ? $collection_storage_limit_premium : $collection_storage_limit_free);

$sth = $dbh->prepare("SELECT id FROM collections WHERE by = ?");
$sth->execute([$user['id']]);
$results = $sth->fetchAll(PDO::FETCH_ASSOC);
if (count($results) >= $max_collection_amount)
    throw_error(($user['is_premium'] ? "You can't have more than $max_collection_amount collections." : "You've reached your maximum of $max_collection_amount collections. Want more? <a href='/premium'>Upgrade to premium</a>."));

if (isset($_POST['name']) and isset($_POST['type'])) {
    $_POST['type'] = intval($_POST['type']);
    if (mb_strlen($_POST['name']) > $max_collection_name_length)
        show_info("Collection name must be at most $max_collection_name_length characters.");
    else if (mb_strlen($_POST['name']) <= 0)
        show_info("Collection name can't be empty.");
    else if (!array_key_exists($_POST['type'], $collection_types))
        show_info($_POST['type']." isn't a valid collection size.");
    else if ($_POST['type'] == 3)
        show_info("You can't create new giant collections.");
    else {
        $empty_collection = Array();
        $collection_type = $collection_types[$_POST['type']];
        for ($row = 0; $row < $collection_type->height; $row++) {
            $collection_row = Array();
            for ($column = 0; $column < $collection_type->width; $column++) {
                array_push($collection_row, -1);
            }
            array_push($empty_collection, $collection_row);
        }

        $dbh->prepare("INSERT INTO collections (type, name, by, created_at, data) VALUES (?, ?, ?, ?, ?)")
            ->execute([$_POST['type'], $_POST['name'], $user['id'], time(), json_encode($empty_collection)]);

        redirect("/collection/edit.php?id=".dechex($dbh->lastInsertId()));
    }
}
?>

<h1>Create a new collection</h1>
<form action="" method="post">
    <label>Collection name:</label>
    <input type="text" name="name" class="form-control" value="My Epic Collection" maxlength=<?=$max_collection_name_length?>>
    <label>Size:</label>
    <select class="form-control" name="type">
        <option value=0>Square</option>
        <option value=1>Landscape</option>
        <option value=2>Portrait</option>
    </select>
    <button type="submit" class="btn btn-lg btn-primary">Create</button>
</form>

<hr>
<h2>Sizes</h2>
<label>Square - 32*32</label>
<br>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGEAAABhAQMAAAD8yF3gAAAABlBMVEUAAAD///+l2Z/dAAAAHklEQVQ4y2NABbnbbsMRGm9U5ajKUZWjKkdV4lYJAJeV/hAX6SbbAAAAAElFTkSuQmCC">
<br>
<br>
<label>Landscape - 40*24</label>
<br>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHkAAABJAQMAAAAjThunAAAABlBMVEUAAAD///+l2Z/dAAAAHElEQVQ4y2PAALnbbiMjTIFRLaNaRrWMahncWgC1Qt4wOmc7+QAAAABJRU5ErkJggg==">
<br>
<br>
<label>Portrait - 24*40</label>
<br>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEkAAAB5AQMAAABPx54yAAAABlBMVEUAAAD///+l2Z/dAAAAHUlEQVQ4y2NAArnbbkMQMnNUwaiCUQWjCkYVwAAAxijeMAQTpZkAAAAASUVORK5CYII=">

<?php gen_bottom(); ?>